<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class CurrencyRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'name' => 'required|array',
            'name.ru' => 'required|string|max:100',
            'name.en' => 'required|string|max:100',
            'name.uz' => 'required|string|max:100',
            'name.kz' => 'required|string|max:100',
            'name.tj' => 'required|string|max:100',
            'code' => 'required|integer',
            'ccy' => 'required|string',
            'rate' => 'required|numeric',
            'buying_price' => 'required|numeric',
            'relevance_date' => 'required|date',
        ];
    }
}
