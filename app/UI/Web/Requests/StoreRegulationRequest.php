<?php

declare(strict_types=1);

namespace App\UI\WEB\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreRegulationRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'code' => ['required', 'string', 'max:50', 'unique:regulations'],
            'name' => ['required', 'string', 'max:500'],
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

    public function messages(): array
    {
        return [
            'code.required' => 'El código de la normatividad es obligatorio.',
            'code.unique' => 'Este código ya está registrado.',
            'name.required' => 'El nombre de la normatividad es obligatorio.',
            'url.url' => 'La URL debe ser válida.',
        ];
    }
}
