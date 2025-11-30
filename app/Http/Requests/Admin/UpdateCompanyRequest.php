<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateCompanyRequest extends FormRequest
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
        $companyId = $this->route('company')->id;

        return [
            'name' => ['required', 'string', 'max:255', Rule::unique('companies', 'name')->ignore($companyId)],
            'description' => ['nullable', 'string', 'max:1000'],
            'image_url' => ['nullable', 'url', 'max:500'],
            'rating' => ['nullable', 'numeric', 'min:1', 'max:5'],
            'phone' => ['nullable', 'string', 'max:20'],
            'specialties' => ['nullable', 'array'],
            'specialties.*' => ['nullable', 'string', 'in:medicare_advantage,supplement,prescription_drugs,dental,vision,long_term_care,life_insurance'],
            'logo' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif', 'max:2048'],
            'remove_logo' => ['nullable', 'boolean'],
            'email' => ['required', 'email', 'max:255', Rule::unique('companies', 'email')->ignore($companyId)],
            'website' => ['nullable', 'url', 'max:255'],
            'address' => ['nullable', 'string', 'max:500'],
            'is_active' => ['nullable', 'boolean'],
        ];
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'name.required' => 'Company name is required.',
            'name.unique' => 'A company with this name already exists.',
            'email.required' => 'Email address is required.',
            'email.unique' => 'A company with this email already exists.',
            'logo.image' => 'Logo must be an image file.',
            'logo.max' => 'Logo file size must not exceed 2MB.',
            'rating.numeric' => 'Rating must be a number.',
            'rating.min' => 'Rating must be at least 1 star.',
            'rating.max' => 'Rating cannot exceed 5 stars.',
            'specialties.*.in' => 'Please select a valid specialty.',
        ];
    }

    /**
     * Prepare the data for validation.
     */
    protected function prepareForValidation(): void
    {
        // Remove empty specialties
        if ($this->has('specialties')) {
            $this->merge([
                'specialties' => array_filter($this->specialties ?? [], function($specialty) {
                    return !empty($specialty);
                })
            ]);
        }

        // Convert checkboxes to boolean
        $this->merge([
            'is_active' => (bool) $this->is_active,
            'remove_logo' => (bool) $this->remove_logo,
        ]);
    }
}
