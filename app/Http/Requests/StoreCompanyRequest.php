<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreCompanyRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return auth()->check() && auth()->user()->is_admin;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255', 'unique:companies'],
            'description' => ['required', 'string'],
            'image_url' => ['nullable', 'url'],
            'rating' => ['required', 'numeric', 'min:0', 'max:5'],
            'phone' => ['required', 'string', 'max:20'],
            'specialties' => ['required', 'array', 'min:1'],
            'specialties.*' => ['required', 'string', 'max:100'],
        ];
    }

    /**
     * Get custom messages for validator errors.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'name.unique' => 'A company with this name already exists.',
            'specialties.required' => 'At least one specialty is required.',
            'specialties.*.required' => 'Each specialty must have a value.',
            'rating.between' => 'Rating must be between 0 and 5.',
        ];
    }
}
