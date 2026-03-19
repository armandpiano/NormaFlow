<?php

namespace App\UI\API\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CompleteAuditRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'results_summary' => 'nullable|string|max:2000',
            'conclusions' => 'nullable|string|max:2000',
            'recommendations' => 'nullable|string|max:2000',
        ];
    }
}
