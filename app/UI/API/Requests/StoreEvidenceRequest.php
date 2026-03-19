<?php

namespace App\UI\API\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreEvidenceRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'requirement_id' => 'required|integer|exists:requirements,id',
            'site_id' => 'nullable|integer|exists:sites,id',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
            'file' => 'required|file|mimes:pdf,doc,docx,xls,xlsx,jpg,jpeg,png|max:10240',
            'document_date' => 'nullable|date',
            'valid_from' => 'nullable|date',
            'valid_until' => 'nullable|date|after_or_equal:valid_from',
        ];
    }

    public function messages(): array
    {
        return [
            'file.mimes' => 'El archivo debe ser PDF, DOC, DOCX, XLS, XLSX, JPG, JPEG o PNG',
            'file.max' => 'El archivo no debe superar los 10MB',
        ];
    }
}
