<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Models\Transaction;
use App\Models\Category;
use Illuminate\Support\Facades\Auth;

class StoreTransactionRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return Auth::user()->can('create', Transaction::class);
    }

    /**
     * Prepare the data for validation.
     */
    protected function prepareForValidation()
    {
        $transactionType = request('transaction_type');

        // Define qual campo de data usar baseado no tipo de transação
        $dateField = match ($transactionType) {
            'single' => 'single_date',
            'recurring' => 'recurring_date',
            'installment' => 'installment_date',
            default => null
        };

        // Se encontrou um campo de data válido, copia o valor para o campo 'date'
        if ($dateField && request()->has($dateField)) {
            $this->merge([
                'date' => request($dateField)
            ]);
        }
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $rules = [
            'transaction_type' => 'required|in:single,recurring,installment',
            'type' => 'required|in:income,expense',
            'description' => 'required|string|max:255',
            'date' => 'required|date',
            'category_id' => [
                'required',
                'exists:categories,id',
                function ($attribute, $value, $fail) {
                    $category = Category::find($value);
                    $transactionType = request('type');

                    if ($category && $category->type !== $transactionType) {
                        $fail('A categoria deve ser do mesmo tipo da transação.');
                    }

                    if ($category && $category->user_id !== Auth::id()) {
                        $fail('A categoria selecionada não pertence ao usuário.');
                    }
                },
            ],
        ];

        $transactionType = request('transaction_type');

        switch ($transactionType) {
            case 'single':
                $rules['amount'] = [
                    'required',
                    function ($attribute, $value, $fail) {
                        $cleanValue = str_replace(',', '.', $value);
                        if (!is_numeric($cleanValue)) {
                            $fail('O valor deve ser um número válido.');
                        } elseif ($cleanValue < 0.01) {
                            $fail('O valor deve ser maior que zero.');
                        }
                    }
                ];
                break;

            case 'recurring':
                $rules['amount'] = [
                    'required',
                    function ($attribute, $value, $fail) {
                        $cleanValue = str_replace(',', '.', $value);
                        if (!is_numeric($cleanValue)) {
                            $fail('O valor deve ser um número válido.');
                        } elseif ($cleanValue < 0.01) {
                            $fail('O valor deve ser maior que zero.');
                        }
                    }
                ];
                $rules['recurring_months'] = 'required|integer|min:2|max:300';
                break;

            case 'installment':
                $rules['total_amount'] = [
                    'required',
                    function ($attribute, $value, $fail) {
                        $cleanValue = str_replace(',', '.', $value);
                        if (!is_numeric($cleanValue)) {
                            $fail('O valor total deve ser um número válido.');
                        } elseif ($cleanValue < 0.01) {
                            $fail('O valor total deve ser maior que zero.');
                        }
                    }
                ];
                $rules['installments'] = 'required|integer|min:2|max:300';
                break;
        }

        return $rules;
    }

    /**
     * Get the validated data with decimal conversion.
     */
    public function validated($key = null, $default = null)
    {
        $validated = parent::validated($key, $default);

        // Converte vírgulas para pontos nos campos numéricos apenas após validação
        if (isset($validated['amount'])) {
            $validated['amount'] = str_replace(',', '.', $validated['amount']);
        }

        if (isset($validated['total_amount'])) {
            $validated['total_amount'] = str_replace(',', '.', $validated['total_amount']);
        }

        return $validated;
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'transaction_type.required' => 'O tipo da transação é obrigatório.',
            'transaction_type.in' => 'O tipo de transação deve ser única, recorrente ou parcelada.',
            'type.required' => 'O tipo da transação é obrigatório.',
            'type.in' => 'O tipo deve ser receita ou despesa.',
            'description.required' => 'A descrição é obrigatória.',
            'amount.required_if' => 'O valor é obrigatório.',
            'amount.numeric' => 'O valor deve ser um número válido.',
            'amount.min' => 'O valor deve ser maior que zero.',
            'total_amount.required_if' => 'O valor total é obrigatório para transações parceladas.',
            'total_amount.numeric' => 'O valor total deve ser um número válido.',
            'total_amount.min' => 'O valor total deve ser maior que zero.',
            'date.required' => 'A data é obrigatória.',
            'date.date' => 'A data deve ser uma data válida.',
            'category_id.required' => 'A categoria é obrigatória.',
            'category_id.exists' => 'A categoria selecionada não existe.',
            'installments.required_if' => 'O número de parcelas é obrigatório para transações parceladas.',
            'installments.integer' => 'O número de parcelas deve ser um número inteiro.',
            'installments.min' => 'O número mínimo de parcelas é 2.',
            'installments.max' => 'O número máximo de parcelas é 300.',
            'recurring_months.required_if' => 'A duração em meses é obrigatória para transações recorrentes.',
            'recurring_months.integer' => 'A duração deve ser um número inteiro.',
            'recurring_months.min' => 'A duração mínima é 2 meses.',
            'recurring_months.max' => 'A duração máxima é 300 meses.',
        ];
    }
}
