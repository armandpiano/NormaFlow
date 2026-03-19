<?php

declare(strict_types=1);

namespace App\UI\WEB\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreAuditRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'company_id' => ['required', 'string', 'exists:companies,id'],
            'site_id' => ['nullable', 'string', 'exists:sites,id'],
            'title' => ['required', 'string', 'max:500'],
            'description' => ['nullable', 'string'],
            'type' => ['nullable', 'string', 'in:internal,external,follow-up'],
            'scope' => ['nullable', 'string', 'max:1000'],
            'start_date' => ['required', 'date'],
            'end_date' => ['nullable', 'date', 'after_or_equal:start_date'],
            'lead_auditor_id' => ['nullable', 'string', 'exists:users,id'],
            'participant_ids' => ['nullable', 'array'],
            'participant_ids.*' => ['string', 'exists:users,id'],
            'requirement_ids' => ['nullable', 'array'],
            'requirement_ids.*' => ['string', 'exists:requirements,id'],
        ];
    }

    public function messages(): array
    {
        return [
            'company_id.required' => 'La empresa es obligatoria.',
            'company_id.exists' => 'La empresa no existe.',
            'title.required' => 'El título de la auditoría es obligatorio.',
            'start_date.required' => 'La fecha de inicio es obligatoria.',
            'start_date.date' => 'La fecha de inicio debe ser una fecha válida.',
            'end_date.after_or_equal' => 'La fecha de fin debe ser posterior a la fecha de inicio.',
        ];
    }
}
