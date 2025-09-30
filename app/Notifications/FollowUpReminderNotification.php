<?php

namespace App\Notifications;

use App\Models\Lead;
use App\Models\LeadEvent;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Filament\Notifications\Notification as FilamentNotification;

class FollowUpReminderNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(
        public Lead $lead,
        public ?LeadEvent $event = null
    ) {}

    public function via($notifiable): array
    {
        return ['mail', 'database'];
    }

    public function toMail($notifiable): MailMessage
    {
        $mail = (new MailMessage)
            ->subject('⏰ Recordatorio de Seguimiento - ' . $this->lead->nombre)
            ->greeting('Hola ' . $notifiable->name)
            ->line('Tienes un seguimiento pendiente:')
            ->line('**Lead:** ' . $this->lead->nombre . ' ' . ($this->lead->apellidos ?? ''))
            ->line('**Email:** ' . ($this->lead->email ?? 'No proporcionado'))
            ->line('**Teléfono:** ' . ($this->lead->telefono ?? 'No proporcionado'))
            ->line('**Estado:** ' . ucfirst($this->lead->estado));

        if ($this->event) {
            $mail->line('**Acción programada:** ' . $this->event->titulo)
                ->line('**Descripción:** ' . ($this->event->descripcion ?? 'Sin descripción'))
                ->line('**Fecha programada:** ' . $this->event->fecha_programada->format('d/m/Y H:i'));
        } else {
            $daysSinceCreated = $this->lead->created_at->diffInDays(now());
            $mail->line('**Días sin seguimiento:** ' . $daysSinceCreated . ' días');
        }

        return $mail->action('Ver Lead', url('/dashboard/leads/' . $this->lead->id))
            ->line('¡No dejes pasar esta oportunidad!')
            ->salutation('Saludos, ' . config('app.name'));
    }

    public function toDatabase($notifiable): array
    {
        return [
            'title' => '⏰ Recordatorio de Seguimiento',
            'message' => 'Seguimiento pendiente para: ' . $this->lead->nombre,
            'lead_id' => $this->lead->id,
            'lead_name' => $this->lead->nombre,
            'lead_state' => $this->lead->estado,
            'event_id' => $this->event?->id,
            'event_title' => $this->event?->titulo,
            'scheduled_date' => $this->event?->fecha_programada,
            'action_url' => '/dashboard/leads/' . $this->lead->id,
        ];
    }

    public function toFilament($notifiable): FilamentNotification
    {
        $body = 'Seguimiento pendiente para: ' . $this->lead->nombre;
        
        if ($this->event) {
            $body .= ' - ' . $this->event->titulo;
        }

        return FilamentNotification::make()
            ->title('⏰ Recordatorio de Seguimiento')
            ->body($body)
            ->icon('heroicon-o-clock')
            ->iconColor('warning')
            ->persistent();
    }
}
