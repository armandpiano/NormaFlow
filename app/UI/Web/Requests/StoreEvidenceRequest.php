<?php

declare(strict_types=1);

namespace App\UI\WEB\Requests;

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
            'requirement_id' => ['required', 'string', 'exists:requirements,id'],
            'title' => ['required', 'string', 'max:500'],
            'description' => ['nullable', 'string'],
            'type' => ['nullable', 'string', 'in:document,photo,video,audio,certificate,report,record,other'],
            'file' => ['required', 'file', 'max:51200', 'mimes:pdf,doc,docx,xls,xlsx,png,jpg,jpeg,gif,mp4,mp3,wav'],
            'expiration_date' => ['nullable', 'date', 'after:today'],
            'tags' => ['nullable', 'array'],
            'tags.*' => ['string', 'max:50'],
        ];
    }

    public function messages(): array
    {
        return [
            'requirement_id.required' => 'El requisito es obligatorio.',
            'requirement_id.exists' => 'El requisito no existe.',
            'title.required' => 'El título es obligatorio.',
            'file.required' => 'El archivo es obligatorio.',
            'file.file' => 'Debe seleccionar un archivo válido.',
            'file.max' => 'El archivo no debe exceder 50MB.',
            'file.mimes' => 'El archivo debe ser un documento, imagen, video o audio válido.',
            'expiration_date.after' => 'La fecha de expiración debe ser futura.',
        ];
    }
}
