<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use ZipArchive;

class DataManagementController extends Controller
{
    public function export()
    {
        $user = Auth::user();

        $tempPath = storage_path('app/temp/'.$user->id.'_'.time());
        if (! file_exists($tempPath)) {
            mkdir($tempPath, 0755, true);
        }

        try {
            $this->exportCategories($user, $tempPath);
            $this->exportTransactions($user, $tempPath);

            $userName = $user->name;
            $userName = iconv('UTF-8', 'ASCII//TRANSLIT//IGNORE', $userName);
            $userName = preg_replace('/[^A-Za-z0-9]/', '_', $userName);
            $userName = preg_replace('/_+/', '_', $userName);
            $userName = trim($userName, '_');

            $zipFileName = 'backup_aurum_'.$userName.'_'.date('Y-m-d_His').'.zip';
            $zipPath = storage_path('app/temp/'.$zipFileName);

            $zip = new ZipArchive;
            if ($zip->open($zipPath, ZipArchive::CREATE) !== true) {
                throw new \Exception('Não foi possível criar o arquivo ZIP.');
            }

            $zip->addFile($tempPath.'/categorias.csv', 'categorias.csv');
            $zip->addFile($tempPath.'/transacoes.csv', 'transacoes.csv');
            $zip->close();

            unlink($tempPath.'/categorias.csv');
            unlink($tempPath.'/transacoes.csv');
            rmdir($tempPath);

            return response()->download($zipPath, $zipFileName)->deleteFileAfterSend(true);

        } catch (\Exception $e) {
            if (file_exists($tempPath.'/categorias.csv')) {
                unlink($tempPath.'/categorias.csv');
            }
            if (file_exists($tempPath.'/transacoes.csv')) {
                unlink($tempPath.'/transacoes.csv');
            }
            if (file_exists($tempPath)) {
                rmdir($tempPath);
            }
            if (isset($zipPath) && file_exists($zipPath)) {
                unlink($zipPath);
            }

            return redirect()->back()->with('error', 'Erro ao exportar dados: '.$e->getMessage());
        }
    }

    private function exportCategories($user, $tempPath)
    {
        $categories = Category::where('user_id', $user->id)
            ->orderBy('type')
            ->orderBy('name')
            ->get(['name', 'type']);

        $csvFile = fopen($tempPath.'/categorias.csv', 'w');

        fputcsv($csvFile, ['name', 'type']);

        foreach ($categories as $category) {
            fputcsv($csvFile, [
                $category->name,
                $category->type,
            ]);
        }

        fclose($csvFile);
    }

    private function exportTransactions($user, $tempPath)
    {
        $csvFile = fopen($tempPath.'/transacoes.csv', 'w');

        fputcsv($csvFile, [
            'description',
            'amount',
            'date',
            'type',
            'category_name',
            'transaction_group_uuid',
            'total_installments',
            'installment_number',
        ]);

        Transaction::with('category:id,name')
            ->where('user_id', $user->id)
            ->orderBy('date', 'desc')
            ->chunk(1000, function ($transactions) use ($csvFile) {
                foreach ($transactions as $transaction) {
                    fputcsv($csvFile, [
                        $transaction->description,
                        number_format($transaction->amount, 2, '.', ''),
                        $transaction->date->format('Y-m-d'),
                        $transaction->type,
                        $transaction->category?->name ?? '',
                        $transaction->transaction_group_uuid ?? '',
                        $transaction->total_installments ?? '',
                        $transaction->installment_number ?? '',
                    ]);
                }
            });

        fclose($csvFile);
    }

    public function import(Request $request)
    {
        $request->validate([
            'backup_file' => 'required|file|mimes:zip|max:10240',
        ]);

        $user = Auth::user();
        $file = $request->file('backup_file');

        $tempPath = storage_path('app/temp/import_'.$user->id.'_'.time());
        if (! file_exists($tempPath)) {
            mkdir($tempPath, 0755, true);
        }

        $errors = [];
        $stats = [
            'categories_imported' => 0,
            'categories_skipped' => 0,
            'categories_total' => 0,
            'transactions_imported' => 0,
            'transactions_total' => 0,
        ];

        try {
            $zip = new ZipArchive;
            if ($zip->open($file->getRealPath()) !== true) {
                throw new \Exception('Arquivo ZIP inválido.');
            }

            $zip->extractTo($tempPath);
            $zip->close();

            if (! file_exists($tempPath.'/categorias.csv') || ! file_exists($tempPath.'/transacoes.csv')) {
                throw new \Exception('Arquivo inválido. Por favor, utilize o arquivo .zip gerado pela função de exportação.');
            }

            DB::beginTransaction();

            $categoriesResult = $this->importCategories($user, $tempPath, $errors);
            $stats['categories_imported'] = $categoriesResult['imported'];
            $stats['categories_skipped'] = $categoriesResult['skipped'];
            $stats['categories_total'] = $categoriesResult['total'];

            $transactionsResult = $this->importTransactions($user, $tempPath, $errors);
            $stats['transactions_imported'] = $transactionsResult['imported'];
            $stats['transactions_total'] = $transactionsResult['total'];

            DB::commit();

            $this->cleanupTempFiles($tempPath);

            if (count($errors) > 0) {
                $message = 'Importação concluída com alguns problemas. ';
                $message .= "{$stats['categories_imported']} categorias novas";
                if ($stats['categories_skipped'] > 0) {
                    $message .= " ({$stats['categories_skipped']} já existentes)";
                }
                $message .= " e {$stats['transactions_imported']} transações foram importadas. ";
                $message .= count($errors).' itens não puderam ser importados.';

                return redirect()->back()->with([
                    'import_status' => 'partial',
                    'import_stats' => $stats,
                    'import_errors' => $errors,
                    'warning' => $message,
                ]);
            }

            $message = 'Importação concluída com sucesso! ';
            $message .= "{$stats['categories_imported']} categorias novas";
            if ($stats['categories_skipped'] > 0) {
                $message .= " ({$stats['categories_skipped']} já existentes)";
            }
            $message .= " e {$stats['transactions_imported']} transações foram importadas.";

            return redirect()->back()->with([
                'import_status' => 'success',
                'import_stats' => $stats,
                'success' => $message,
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            $this->cleanupTempFiles($tempPath);

            return redirect()->back()->with('error', 'Erro ao importar dados: '.$e->getMessage());
        }
    }

    private function importCategories($user, $tempPath, &$errors)
    {
        $csvFile = fopen($tempPath.'/categorias.csv', 'r');
        fgetcsv($csvFile);

        $existingCategories = Category::where('user_id', $user->id)
            ->get()
            ->mapWithKeys(function ($category) {
                return ["{$category->name}|{$category->type}" => true];
            });

        $imported = 0;
        $skipped = 0;
        $total = 0;
        $lineNumber = 1;
        $categoriesToInsert = [];

        while (($data = fgetcsv($csvFile)) !== false) {
            $lineNumber++;
            $total++;

            if (count($data) < 2) {
                $errors[] = "Linha {$lineNumber} do arquivo de categorias: formato inválido.";

                continue;
            }

            $name = trim($data[0]);
            $type = trim($data[1]);

            if (! in_array($type, ['income', 'expense'])) {
                $errors[] = "Linha {$lineNumber} do arquivo de categorias: tipo '{$type}' inválido.";

                continue;
            }

            $key = "{$name}|{$type}";
            if (isset($existingCategories[$key])) {
                $skipped++;
            } else {
                $categoriesToInsert[] = [
                    'user_id' => $user->id,
                    'name' => $name,
                    'type' => $type,
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
                $existingCategories[$key] = true;
                $imported++;
            }
        }

        fclose($csvFile);

        if (! empty($categoriesToInsert)) {
            try {
                Category::insert($categoriesToInsert);
            } catch (\Exception $e) {
                $errors[] = "Erro ao inserir categorias em lote: {$e->getMessage()}";
                $imported = 0;
            }
        }

        return ['imported' => $imported, 'skipped' => $skipped, 'total' => $total];
    }

    private function importTransactions($user, $tempPath, &$errors)
    {
        $csvFile = fopen($tempPath.'/transacoes.csv', 'r');
        fgetcsv($csvFile);

        $categories = Category::where('user_id', $user->id)
            ->get()
            ->mapWithKeys(function ($category) {
                return ["{$category->name}|{$category->type}" => $category->id];
            });

        $imported = 0;
        $total = 0;
        $lineNumber = 1;
        $transactionsToInsert = [];
        $batchSize = 500;

        while (($data = fgetcsv($csvFile)) !== false) {
            $lineNumber++;
            $total++;

            if (count($data) < 5) {
                $errors[] = "Linha {$lineNumber} do arquivo de transações: formato inválido.";

                continue;
            }

            $description = trim($data[0]);
            $amount = trim($data[1]);
            $date = trim($data[2]);
            $type = trim($data[3]);
            $categoryName = trim($data[4]);
            $groupUuid = isset($data[5]) && ! empty($data[5]) ? trim($data[5]) : null;
            $totalInstallments = isset($data[6]) && ! empty($data[6]) ? trim($data[6]) : null;
            $currentInstallment = isset($data[7]) && ! empty($data[7]) ? trim($data[7]) : null;

            if (! in_array($type, ['income', 'expense'])) {
                $errors[] = "Linha {$lineNumber} do arquivo de transações: tipo '{$type}' inválido ('{$description}').";

                continue;
            }

            if (! is_numeric($amount) || $amount < 0) {
                $errors[] = "Linha {$lineNumber} do arquivo de transações: valor inválido ('{$description}').";

                continue;
            }

            $dateObj = \DateTime::createFromFormat('Y-m-d', $date);
            if (! $dateObj || $dateObj->format('Y-m-d') !== $date) {
                $errors[] = "Linha {$lineNumber} do arquivo de transações: data inválida ('{$description}').";

                continue;
            }

            $categoryId = null;
            if (! empty($categoryName)) {
                $key = "{$categoryName}|{$type}";
                if (isset($categories[$key])) {
                    $categoryId = $categories[$key];
                } else {
                    $errors[] = "Linha {$lineNumber} do arquivo de transações: categoria '{$categoryName}' não encontrada ('{$description}').";

                    continue;
                }
            }

            $transactionData = [
                'user_id' => $user->id,
                'description' => $description,
                'amount' => $amount,
                'date' => $date,
                'type' => $type,
                'category_id' => $categoryId,
                'transaction_group_uuid' => $groupUuid,
                'total_installments' => $totalInstallments ? (int) $totalInstallments : null,
                'installment_number' => $currentInstallment ? (int) $currentInstallment : null,
                'created_at' => now(),
                'updated_at' => now(),
            ];

            $transactionsToInsert[] = $transactionData;
            $imported++;

            if (count($transactionsToInsert) >= $batchSize) {
                try {
                    Transaction::insert($transactionsToInsert);
                    $transactionsToInsert = [];
                } catch (\Exception $e) {
                    $errors[] = "Erro ao inserir lote de transações: {$e->getMessage()}";
                }
            }
        }

        if (! empty($transactionsToInsert)) {
            try {
                Transaction::insert($transactionsToInsert);
            } catch (\Exception $e) {
                $errors[] = "Erro ao inserir lote final de transações: {$e->getMessage()}";
            }
        }

        fclose($csvFile);

        return ['imported' => $imported, 'total' => $total];
    }

    private function cleanupTempFiles($path)
    {
        if (! file_exists($path)) {
            return;
        }

        $files = glob($path.'/*');
        foreach ($files as $file) {
            if (is_file($file)) {
                unlink($file);
            }
        }
        rmdir($path);
    }
}
