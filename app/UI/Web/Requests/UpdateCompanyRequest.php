<?php

declare(strict_types=1);

namespace App\UI\WEB\Requests;

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
            'name' => ['sometimes', 'string', 'max:255'],
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
        ];
    }
}
