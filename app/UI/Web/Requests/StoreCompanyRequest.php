<?php

declare(strict_types=1);

namespace App\UI\WEB\Requests;

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
            'name' => ['required', 'string', 'max:255'],
            'rfc' => ['required', 'string', 'max:20', 'unique:companies'],
            'industry' => ['nullable', 'string', 'max:100'],
            'size' => ['nullable', 'string', 'in:micro,small,medium,large'],
            'address' => ['nullable', 'string', 'max:500'],
            'city' => ['nullable', 'string', 'max:100'],
            'state' => ['nullable', 'string', 'max:100'],
            'country' => ['nullable', 'string', 'max:100'],
            'postal_code' => ['nullable', 'string', 'max:10'],
            'phone' => ['nullable', 'string', 'max:20'],
            'email' => ['nullable', 'email', 'max:255'],
            'website' => ['nullable', 'url', 'max:255'],
            'logo_url' => ['nullable', 'url', 'max:500'],
            'subscription_plan' => ['nullable', 'string', 'in:basic,professional,enterprise'],
            'max_users' => ['nullable', 'integer', 'min:1'],
            'max_sites' => ['nullable', 'integer', 'min:1'],
            'initial_site' => ['nullable', 'array'],
            'initial_site.name' => ['required_with:initial_site', 'string', 'max:255'],
            'initial_site.code' => ['required_with:initial_site', 'string', 'max:50'],
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'El nombre de la empresa es obligatorio.',
            'rfc.required' => 'El RFC es obligatorio.',
            'rfc.unique' => 'Este RFC ya está registrado.',
            'email.email' => 'El correo electrónico debe ser válido.',
            'website.url' => 'El sitio web debe ser una URL válida.',
        ];
    }
}
