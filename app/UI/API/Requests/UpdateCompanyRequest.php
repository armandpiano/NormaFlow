<?php

namespace App\UI\API\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateCompanyRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => 'sometimes|string|max:255',
            'industry' => 'nullable|string|max:100',
            'address' => 'nullable|string|max:500',
            'city' => 'nullable|string|max:100',
            'state' => 'nullable|string|max:100',
            'zip_code' => 'nullable|string|max:10',
            'phone' => 'nullable|string|max:20',
            'email' => 'sometimes|email|max:255',
            'website' => 'nullable|url|max:255',
            'employee_count' => 'nullable|integer|min:1',
        ];
    }
}
