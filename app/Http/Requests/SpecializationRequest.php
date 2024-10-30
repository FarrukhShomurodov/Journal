<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class SpecializationRequest extends FormRequest
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
            'name.ru' => 'required|string|max:200',
            'name.en' => 'required|string|max:200',
            'name.uz' => 'required|string|max:200',
            'name.kz' => 'required|string|max:200',
            'name.tj' => 'required|string|max:200',
            'rating' => 'required|numeric|min:1|max:5'
        ];
    }
}
