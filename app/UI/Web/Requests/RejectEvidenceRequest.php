<?php

declare(strict_types=1);

namespace App\UI\WEB\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RejectEvidenceRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'reason' => ['required', 'string', 'max:1000'],
        ];
    }

    public function messages(): array
    {
        return [
            'reason.required' => 'El motivo del rechazo es obligatorio.',
        ];
    }
}
