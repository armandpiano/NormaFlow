<?php

namespace App\Domain\Shared\ValueObjects;

enum EvidenceType: string
{
    case DOCUMENT = 'documento';
    case PHOTO = 'fotografia';
    case VIDEO = 'video';
    case RECORD = 'registro';
    case CERTIFICATE = 'certificado';
    case REPORT = 'informe';
    case FORM = 'formato';
    case LIST = 'listado';

    public function label(): string
    {
        return match($this) {
            self::DOCUMENT => 'Documento',
            self::PHOTO => 'Fotografía',
            self::VIDEO => 'Video',
            self::RECORD => 'Registro',
            self::CERTIFICATE => 'Certificado',
            self::REPORT => 'Informe',
            self::FORM => 'Formato',
            self::LIST => 'Listado',
        };
    }

    public function acceptedExtensions(): array
    {
        return match($this) {
            self::DOCUMENT => ['pdf', 'doc', 'docx', 'xls', 'xlsx', 'ppt', 'pptx'],
            self::PHOTO => ['jpg', 'jpeg', 'png', 'gif', 'bmp', 'webp'],
            self::VIDEO => ['mp4', 'avi', 'mov', 'wmv', 'webm'],
            self::RECORD => ['pdf', 'xls', 'xlsx', 'csv'],
            self::CERTIFICATE => ['pdf'],
            self::REPORT => ['pdf', 'doc', 'docx'],
            self::FORM => ['pdf', 'doc', 'docx'],
            self::LIST => ['pdf', 'xls', 'xlsx', 'csv'],
        };
    }
}
