<?php

declare(strict_types=1);

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class EvidenceExpirationNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(
        private readonly string $evidenceTitle,
        private readonly string $requirementCode,
        private readonly \DateTime $expirationDate,
        private readonly int $daysUntilExpiration
    ) {}

    public function via(object $notifiable): array
    {
        return ['mail', 'database'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        $message = (new MailMessage)
            ->subject('Evidencia por vencer - NormaFlow')
            ->greeting('Hola ' . $notifiable->name . ',')
            ->line('Una evidencia está por vencer y requiere atención:')
            ->line("**Evidencia:** {$this->evidenceTitle}")
            ->line("**Requisito:** {$this->requirementCode}")
            ->line("**Días restantes:** {$this->daysUntilExpiration}")
            ->line("**Fecha de vencimiento:** {$this->expirationDate->format('d/m/Y')}");

        if ($this->daysUntilExpiration <= 7) {
            $message->warning('Esta evidencia vencerá en menos de 7 días. Es urgente renovarla.');
        }

        return $message
            ->action('Ver Evidencia', url('/evidence'))
            ->line('No olvides actualizar la evidencia para mantener tu cumplimiento normativo.');
    }

    public function toArray(object $notifiable): array
    {
        return [
            'type' => 'evidence_expiration',
            'evidence_title' => $this->evidenceTitle,
            'requirement_code' => $this->requirementCode,
            'expiration_date' => $this->expirationDate->format('Y-m-d'),
            'days_until_expiration' => $this->daysUntilExpiration,
        ];
    }
}
