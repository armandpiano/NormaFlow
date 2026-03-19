<?php

namespace App\Domain\Shared\ValueObjects;

enum ComplianceStatus: string
{
    case PENDING = 'pending';
    case IN_PROGRESS = 'in_progress';
    case COMPLIANT = 'compliant';
    case NON_COMPLIANT = 'non_compliant';
    case NOT_APPLICABLE = 'not_applicable';
    case EXPIRED = 'expired';

    public function label(): string
    {
        return match($this) {
            self::PENDING => 'Pendiente',
            self::IN_PROGRESS => 'En Progreso',
            self::COMPLIANT => 'Cumplido',
            self::NON_COMPLIANT => 'No Cumplido',
            self::NOT_APPLICABLE => 'No Aplica',
            self::EXPIRED => 'Vencido',
        };
    }

    public function color(): string
    {
        return match($this) {
            self::PENDING => 'gray',
            self::IN_PROGRESS => 'blue',
            self::COMPLIANT => 'green',
            self::NON_COMPLIANT => 'red',
            self::NOT_APPLICABLE => 'gray',
            self::EXPIRED => 'orange',
        };
    }

    public function isActive(): bool
    {
        return in_array($this, [
            self::PENDING,
            self::IN_PROGRESS,
            self::COMPLIANT,
            self::NON_COMPLIANT,
        ]);
    }
}
