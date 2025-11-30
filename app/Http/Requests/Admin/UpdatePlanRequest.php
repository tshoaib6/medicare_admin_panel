<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdatePlanRequest extends FormRequest
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
        $planId = $this->route('plan')->id;

        return [
            'slug' => ['required', 'string', 'max:255', 'regex:/^[a-z0-9-]+$/', Rule::unique('plans', 'slug')->ignore($planId)],
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string', 'max:1000'],
            'icon' => ['nullable', 'string', 'max:100', 'regex:/^fa[srb]?\s+fa-[a-z0-9-]+$/'],
            'color' => ['nullable', 'string', 'regex:/^#[0-9A-Fa-f]{6}$/'],
            'benefits' => ['nullable', 'array'],
            'benefits.*' => ['nullable', 'string', 'max:255'],
            'is_available' => ['nullable', 'boolean'],
        ];
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'slug.required' => 'Plan slug is required.',
            'slug.unique' => 'A plan with this slug already exists.',
            'slug.regex' => 'Slug can only contain lowercase letters, numbers, and hyphens.',
            'title.required' => 'Plan title is required.',
            'icon.regex' => 'Icon must be a valid Font Awesome class (e.g., fas fa-heart).',
            'color.regex' => 'Color must be a valid hex color (e.g., #007bff).',
            'benefits.*.max' => 'Each benefit must not exceed 255 characters.',
        ];
    }

    /**
     * Prepare the data for validation.
     */
    protected function prepareForValidation(): void
    {
        // Remove empty benefits
        if ($this->has('benefits')) {
            $this->merge([
                'benefits' => array_filter($this->benefits ?? [], function($benefit) {
                    return !empty(trim($benefit));
                })
            ]);
        }

        // Convert is_available to boolean
        $this->merge([
            'is_available' => (bool) $this->is_available,
        ]);
    }
}
