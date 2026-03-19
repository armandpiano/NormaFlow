<?php

declare(strict_types=1);

namespace App\UI\WEB\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateAuditRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'title' => ['sometimes', 'string', 'max:500'],
            'description' => ['nullable', 'string'],
            'scope' => ['nullable', 'string', 'max:1000'],
            'end_date' => ['nullable', 'date'],
        ];
    }
}
