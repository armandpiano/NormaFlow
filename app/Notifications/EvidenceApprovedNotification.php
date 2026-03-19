<?php

declare(strict_types=1);

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class EvidenceApprovedNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(
        private readonly string $evidenceTitle,
        private readonly string $approvedBy,
        private readonly ?string $notes = null
    ) {}

    public function via(object $notifiable): array
    {
        return ['mail', 'database'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        $message = (new MailMessage)
            ->subject('Evidencia aprobada - NormaFlow')
            ->greeting('Hola ' . $notifiable->name . ',')
            ->line('Una evidencia ha sido aprobada:')
            ->line("**Evidencia:** {$this->evidenceTitle}")
            ->line("**Aprobada por:** {$this->approvedBy}");

        if ($this->notes) {
            $message->line("**Notas:** {$this->notes}");
        }

        return $message
            ->action('Ver Evidencia', url('/evidence'))
            ->success('¡Sigue así! El cumplimiento normativo es importante.');
    }

    public function toArray(object $notifiable): array
    {
        return [
            'type' => 'evidence_approved',
            'evidence_title' => $this->evidenceTitle,
            'approved_by' => $this->approvedBy,
            'notes' => $this->notes,
        ];
    }
}
