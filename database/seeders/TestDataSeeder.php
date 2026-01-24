<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Transaction;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class TestDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Pega o primeiro usuário (ou cria um se não existir)
        $user = User::first();

        if (! $user) {
            $user = User::create([
                'name' => 'Usuário Teste',
                'email' => 'teste@exemplo.com',
                'password' => bcrypt('password'),
            ]);
        }

        // Limpa dados anteriores do usuário
        $user->categories()->delete();
        $user->transactions()->delete();

        // Criar categorias de receita
        $incomeCategories = [
            'Salário',
            'Freelance',
            'Investimentos',
            'Bônus',
            'Aluguel Recebido',
        ];

        $createdIncomeCategories = [];
        foreach ($incomeCategories as $name) {
            $createdIncomeCategories[] = Category::create([
                'user_id' => $user->id,
                'name' => $name,
                'type' => 'income',
            ]);
        }

        // Criar categorias de despesa
        $expenseCategories = [
            'Alimentação',
            'Transporte',
            'Moradia',
            'Saúde',
            'Educação',
            'Lazer',
            'Compras',
            'Contas',
            'Outros',
        ];

        $createdExpenseCategories = [];
        foreach ($expenseCategories as $name) {
            $createdExpenseCategories[] = Category::create([
                'user_id' => $user->id,
                'name' => $name,
                'type' => 'expense',
            ]);
        }

        // Função auxiliar para criar transações
        $createTransactions = function ($month, $multiplier = 1) use ($user, $createdIncomeCategories, $createdExpenseCategories) {
            $date = Carbon::parse($month);

            // Receitas
            Transaction::create([
                'user_id' => $user->id,
                'category_id' => $createdIncomeCategories[0]->id, // Salário
                'type' => 'income',
                'amount' => 5000 * $multiplier,
                'description' => 'Salário mensal',
                'date' => $date->copy()->day(5),
            ]);

            if ($multiplier > 1) {
                Transaction::create([
                    'user_id' => $user->id,
                    'category_id' => $createdIncomeCategories[3]->id, // Bônus
                    'type' => 'income',
                    'amount' => 3000 * $multiplier,
                    'description' => 'Bônus especial',
                    'date' => $date->copy()->day(10),
                ]);
            }

            Transaction::create([
                'user_id' => $user->id,
                'category_id' => $createdIncomeCategories[1]->id, // Freelance
                'type' => 'income',
                'amount' => rand(800, 2000) * $multiplier,
                'description' => 'Projeto freelance',
                'date' => $date->copy()->day(15),
            ]);

            Transaction::create([
                'user_id' => $user->id,
                'category_id' => $createdIncomeCategories[2]->id, // Investimentos
                'type' => 'income',
                'amount' => rand(200, 800) * $multiplier,
                'description' => 'Dividendos',
                'date' => $date->copy()->day(20),
            ]);

            // Despesas fixas
            Transaction::create([
                'user_id' => $user->id,
                'category_id' => $createdExpenseCategories[2]->id, // Moradia
                'type' => 'expense',
                'amount' => 1200 * $multiplier,
                'description' => 'Aluguel',
                'date' => $date->copy()->day(1),
            ]);

            Transaction::create([
                'user_id' => $user->id,
                'category_id' => $createdExpenseCategories[7]->id, // Contas
                'type' => 'expense',
                'amount' => 350 * $multiplier,
                'description' => 'Contas de água, luz e internet',
                'date' => $date->copy()->day(5),
            ]);

            // Despesas variáveis
            for ($i = 1; $i <= rand(3, 6); $i++) {
                Transaction::create([
                    'user_id' => $user->id,
                    'category_id' => $createdExpenseCategories[0]->id, // Alimentação
                    'type' => 'expense',
                    'amount' => rand(50, 300) * $multiplier,
                    'description' => 'Supermercado',
                    'date' => $date->copy()->day(rand(1, 28)),
                ]);
            }

            for ($i = 1; $i <= rand(2, 4); $i++) {
                Transaction::create([
                    'user_id' => $user->id,
                    'category_id' => $createdExpenseCategories[1]->id, // Transporte
                    'type' => 'expense',
                    'amount' => rand(30, 200) * $multiplier,
                    'description' => 'Combustível / Uber',
                    'date' => $date->copy()->day(rand(1, 28)),
                ]);
            }

            Transaction::create([
                'user_id' => $user->id,
                'category_id' => $createdExpenseCategories[5]->id, // Lazer
                'type' => 'expense',
                'amount' => rand(100, 500) * $multiplier,
                'description' => 'Cinema / Restaurante',
                'date' => $date->copy()->day(rand(10, 28)),
            ]);

            if (rand(0, 1)) {
                Transaction::create([
                    'user_id' => $user->id,
                    'category_id' => $createdExpenseCategories[6]->id, // Compras
                    'type' => 'expense',
                    'amount' => rand(100, 800) * $multiplier,
                    'description' => 'Roupas / Eletrônicos',
                    'date' => $date->copy()->day(rand(1, 28)),
                ]);
            }

            if (rand(0, 1)) {
                Transaction::create([
                    'user_id' => $user->id,
                    'category_id' => $createdExpenseCategories[3]->id, // Saúde
                    'type' => 'expense',
                    'amount' => rand(80, 400) * $multiplier,
                    'description' => 'Farmácia / Consulta',
                    'date' => $date->copy()->day(rand(1, 28)),
                ]);
            }
        };

        // Criar transações para os últimos 6 meses
        $now = Carbon::now();

        // 5 meses atrás (valores baixos)
        $createTransactions($now->copy()->subMonths(5)->startOfMonth(), 0.7);

        // 4 meses atrás (valores normais)
        $createTransactions($now->copy()->subMonths(4)->startOfMonth(), 1);

        // 3 meses atrás (valores altos - MÊS ESPECIAL)
        $createTransactions($now->copy()->subMonths(3)->startOfMonth(), 2.5);

        // 2 meses atrás (valores normais)
        $createTransactions($now->copy()->subMonths(2)->startOfMonth(), 1);

        // Mês passado (valores um pouco maiores)
        $createTransactions($now->copy()->subMonths(1)->startOfMonth(), 1.3);

        // Mês atual (valores normais)
        $createTransactions($now->copy()->startOfMonth(), 1);

        $this->command->info('✅ Dados de teste criados com sucesso!');
        $this->command->info('📊 Categorias criadas: '.(count($createdIncomeCategories) + count($createdExpenseCategories)));
        $this->command->info('💰 Transações criadas para os últimos 6 meses');
        $this->command->info('🚀 Mês '.$now->copy()->subMonths(3)->format('m/Y').' possui valores altos para teste');
    }
}
