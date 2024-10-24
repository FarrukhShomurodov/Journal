<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class UsefulInformationRequest extends FormRequest
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
            'description' => 'required|array',
            'description.ru' => 'required|string|max:300',
            'description.en' => 'required|string|max:300',
            'description.uz' => 'required|string|max:300',
            'description.kz' => 'required|string|max:300',
            'description.tj' => 'required|string|max:300',
            'photos' => 'sometimes|array|max:10',
            'photos.*' => 'sometimes|image|mimes:jpg,png',
        ];
    }
}
