<?php

namespace App\UI\API\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ApproveEvidenceRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [];
    }
}
