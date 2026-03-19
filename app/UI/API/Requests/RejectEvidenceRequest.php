<?php

namespace App\UI\API\Requests;

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
            'reason' => 'required|string|max:1000',
        ];
    }
}
