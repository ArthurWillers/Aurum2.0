<?php

namespace App\Http\Requests;

use App\Models\Category;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class UpdateTransactionRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Prepare the data for validation.
     *
     * Normalize decimal separators (comma -> dot) so numeric validation accepts
     * values entered with a comma (e.g. 1.234,56 or 123,45).
     */
    protected function prepareForValidation()
    {
        if ($this->has('amount') && is_string($this->input('amount'))) {
            $this->merge([
                'amount' => str_replace(',', '.', $this->input('amount')),
            ]);
        }

        if ($this->has('total_amount') && is_string($this->input('total_amount'))) {
            $this->merge([
                'total_amount' => str_replace(',', '.', $this->input('total_amount')),
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
        return [
            'type' => 'required|in:income,expense',
            'description' => 'required|string|max:255',
            'amount' => 'required|numeric|min:0.01',
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
    }
}
