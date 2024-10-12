<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class EntertainmentRequest extends FormRequest
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
            'description' => 'required|array',
            'description.ru' => 'required|string|max:200',
            'description.en' => 'required|string|max:200',
            'description.uz' => 'required|string|max:200',
            'description.kz' => 'required|string|max:200',
            'description.tj' => 'required|string|max:200',
            'working_hours' => 'required|string',
            'price_from' => 'required|numeric',
            'price_to' => 'required|numeric',
            'location_link' => 'required|string',
            'contacts' => 'required|array',
            'photos' => 'sometimes|array|max:10',
            'photos.*' => 'sometimes|image|mimes:jpg,png',
        ];
    }
}
