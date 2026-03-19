<?php

declare(strict_types=1);

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class WeeklyDigestNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(
        private readonly array $weeklyStats
    ) {}

    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        $stats = $this->weeklyStats;
        
        return (new MailMessage)
            ->subject('Resumen semanal - NormaFlow')
            ->greeting('Hola ' . $notifiable->name . ',')
            ->line('Este es tu resumen semanal de cumplimiento normativo:')
            ->line('---')
            ->line("**Empresas activas:** {$stats['active_companies'] ?? 0}")
            ->line("**Nuevas evidencias:** {$stats['new_evidence'] ?? 0}")
            ->line("**Evidencias aprobadas:** {$stats['approved_evidence'] ?? 0}")
            ->line("**Evidencias pendientes:** {$stats['pending_evidence'] ?? 0}")
            ->line("**Nuevas auditorías:** {$stats['new_audits'] ?? 0}")
            ->line("**Hallazgos abiertos:** {$stats['open_findings'] ?? 0}")
            ->line("**Hallazgos cerrados:** {$stats['closed_findings'] ?? 0}")
            ->line('---')
            ->action('Ver Dashboard', url('/dashboard'))
            ->line('¡Sigue trabajando en tu cumplimiento normativo!');
    }

    public function toArray(object $notifiable): array
    {
        return [
            'type' => 'weekly_digest',
            'stats' => $this->weeklyStats,
        ];
    }
}
