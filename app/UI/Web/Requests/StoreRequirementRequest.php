<?php

declare(strict_types=1);

namespace App\UI\WEB\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreRequirementRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'code' => ['required', 'string', 'max:50'],
            'description' => ['required', 'string'],
            'category' => ['nullable', 'string', 'max:100'],
            'subcategory' => ['nullable', 'string', 'max:100'],
            'compliance_criteria' => ['nullable', 'string'],
            'evidence_type' => ['nullable', 'string', 'max:100'],
            'evidence_description' => ['nullable', 'string'],
            'verification_frequency' => ['nullable', 'string', 'in:daily,weekly,monthly,quarterly,semiannual,annual'],
            'responsible_role' => ['nullable', 'string', 'max:100'],
            'is_mandatory' => ['nullable', 'boolean'],
            'penalty_description' => ['nullable', 'string'],
            'additional_notes' => ['nullable', 'string'],
            'version' => ['nullable', 'string', 'max:20'],
            'is_active' => ['nullable', 'boolean'],
        ];
    }

    public function messages(): array
    {
        return [
            'code.required' => 'El código del requisito es obligatorio.',
            'description.required' => 'La descripción del requisito es obligatoria.',
        ];
    }
}
