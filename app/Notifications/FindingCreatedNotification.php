<?php

declare(strict_types=1);

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class FindingCreatedNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(
        private readonly string $findingTitle,
        private readonly string $severity,
        private readonly string $auditTitle,
        private readonly string $assignedTo = ''
    ) {}

    public function via(object $notifiable): array
    {
        return ['mail', 'database'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        $severityLabel = match($this->severity) {
            'critical' => 'CRÍTICO',
            'major' => 'MAYOR',
            'minor' => 'MENOR',
            default => 'OBSERVACIÓN',
        };

        $severityColor = match($this->severity) {
            'critical' => 'danger',
            'major' => 'warning',
            default => 'info',
        };

        $message = (new MailMessage)
            ->subject("Nuevo hallazgo - {$severityLabel} - NormaFlow")
            ->greeting('Hola ' . $notifiable->name . ',')
            ->line('Se ha registrado un nuevo hallazgo:')
            ->line("**Hallazgo:** {$this->findingTitle}")
            ->line("**Severidad:** {$severityLabel}")
            ->line("**Auditoría:** {$this->auditTitle}");

        if ($this->assignedTo) {
            $message->line("**Asignado a:** {$this->assignedTo}");
        }

        return $message
            ->action('Ver Hallazgo', url('/audits/findings'))
            ->line('Por favor revisa el hallazgo y genera un plan de acción.');
    }

    public function toArray(object $notifiable): array
    {
        return [
            'type' => 'finding_created',
            'finding_title' => $this->findingTitle,
            'severity' => $this->severity,
            'audit_title' => $this->auditTitle,
            'assigned_to' => $this->assignedTo,
        ];
    }
}
