<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class ClinicRequest extends FormRequest
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
            'description.ru' => 'required|string|max:200',
            'description.en' => 'required|string|max:200',
            'description.uz' => 'required|string|max:200',
            'description.kz' => 'required|string|max:200',
            'description.tj' => 'required|string|max:200',
            'working_hours' => 'required|string',
            'location_link' => 'required|string',
            'contacts' => 'required|array',
            'photos' => 'sometimes|array|max:10',
            'photos.*' => 'sometimes|image|mimes:jpg,png',
            'disease_type' => 'sometimes|array',
            'disease_type*' => 'required|exists:disease_types,id',
            'specialization' => 'required|array',
            'specialization*' => 'required|exists:specializations,id',
            'rating' => 'required|numeric|min:1|max:5'
        ];
    }
}
