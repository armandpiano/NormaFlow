<?php

namespace App\UI\API\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreCompanyRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'tenant_id' => 'required|integer|exists:tenants,id',
            'name' => 'required|string|max:255',
            'rfc' => 'required|string|size:12|unique:companies,rfc',
            'tax_id' => 'nullable|string|max:20',
            'industry' => 'nullable|string|max:100',
            'address' => 'nullable|string|max:500',
            'city' => 'nullable|string|max:100',
            'state' => 'nullable|string|max:100',
            'zip_code' => 'nullable|string|max:10',
            'phone' => 'nullable|string|max:20',
            'email' => 'required|email|max:255',
            'website' => 'nullable|url|max:255',
            'employee_count' => 'nullable|integer|min:1',
        ];
    }

    public function messages(): array
    {
        return [
            'rfc.size' => 'El RFC debe tener 12 caracteres (persona moral)',
            'rfc.unique' => 'Este RFC ya está registrado',
        ];
    }
}
