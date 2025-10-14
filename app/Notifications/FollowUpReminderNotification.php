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
        $tenantName = $this->lead->tenant->name ?? 'tu organización';
        
        $mail = (new MailMessage)
            ->subject('⏰ Nuevo lead para ' . $tenantName)
            ->greeting('Hola ' . $notifiable->name)
            ->line('Tienes un seguimiento pendiente:')
            ->line('**Nombre:** ' . $this->lead->nombre . ' ' . ($this->lead->apellidos ?? ''))
            ->line('**Email:** ' . ($this->lead->email ?? 'No proporcionado'))
            ->line('**Teléfono:** ' . ($this->lead->telefono ?? 'No proporcionado'))
            ->line('**Estado:** ' . ucfirst($this->lead->estado));

        if ($this->event) {
            $mail->line('**Acción programada:** ' . $this->event->titulo)
                ->line('**Descripción:** ' . ($this->event->descripcion ?? 'Sin descripción'))
                ->line('**Fecha programada:** ' . $this->event->fecha_programada->format('d/m/Y H:i'));
        } else {
            $daysSinceCreated = (int) $this->lead->created_at->diffInDays(now());
            $mail->line('**Días sin seguimiento:** ' . $daysSinceCreated . ' días');
        }

        // Construir URL correcta con tenant
        $leadUrl = url('/dashboard/' . $this->lead->tenant->uuid . '/leads/' . $this->lead->id . '/edit');

        return $mail->action('Ver Lead', $leadUrl)
            ->line('¡No dejes pasar esta oportunidad!')
            ->salutation('Saludos, ' . config('app.name'));
    }

    public function toDatabase($notifiable): array
    {
        // Construir URL correcta con tenant
        $actionUrl = '/dashboard/' . $this->lead->tenant->uuid . '/leads/' . $this->lead->id . '/edit';
        
        return [
            'title' => '⏰ Recordatorio de Seguimiento',
            'message' => 'Seguimiento pendiente para: ' . $this->lead->nombre,
            'lead_id' => $this->lead->id,
            'lead_name' => $this->lead->nombre,
            'lead_state' => $this->lead->estado,
            'event_id' => $this->event?->id,
            'event_title' => $this->event?->titulo,
            'scheduled_date' => $this->event?->fecha_programada,
            'action_url' => $actionUrl,
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
