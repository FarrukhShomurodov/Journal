<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class CityRequest extends FormRequest
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
            'name.ru' => 'required|string|max:300',
            'name.en' => 'required|string|max:300',
            'name.uz' => 'required|string|max:300',
            'name.kz' => 'required|string|max:300',
            'name.tj' => 'required|string|max:300',
            'country_id' => 'required|integer|exists:countries,id',
        ];
    }
}
