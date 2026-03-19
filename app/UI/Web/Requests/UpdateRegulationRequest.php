<?php

declare(strict_types=1);

namespace App\UI\WEB\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateRegulationRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => ['sometimes', 'string', 'max:500'],
            'description' => ['nullable', 'string'],
            'type' => ['nullable', 'string', 'in:NOM,NMX,PA,CT,OO,N/A'],
            'issuing_authority' => ['nullable', 'string', 'max:100'],
            'scope' => ['nullable', 'string', 'in:Federal,Estatal,Municipal'],
            'effective_date' => ['nullable', 'date'],
            'publication_date' => ['nullable', 'date'],
            'last_amendment_date' => ['nullable', 'date'],
            'url' => ['nullable', 'url', 'max:500'],
            'keywords' => ['nullable', 'array'],
            'is_active' => ['nullable', 'boolean'],
        ];
    }
}
