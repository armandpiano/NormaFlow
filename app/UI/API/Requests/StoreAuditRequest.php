<?php

namespace App\UI\API\Requests;

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
            'site_id' => 'required|integer|exists:sites,id',
            'user_id' => 'required|integer|exists:users,id',
            'company_id' => 'required|integer|exists:companies,id',
            'name' => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
            'audit_type' => 'nullable|in:interna,externa,certificacion',
            'planned_start_date' => 'required|date',
            'planned_end_date' => 'nullable|date|after_or_equal:planned_start_date',
            'scope' => 'nullable|string|max:500',
            'methodology' => 'nullable|string|max:500',
        ];
    }
}
