<?php

namespace App\Notifications;

use App\Models\Lead;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Filament\Notifications\Notification as FilamentNotification;

class NewLeadNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(
        public Lead $lead
    ) {}

    public function via($notifiable): array
    {
        return ['mail', 'database'];
    }

    public function toMail($notifiable): MailMessage
    {
        $tenantName = $this->lead->tenant->name ?? 'tu organización';
        
        // Construir URL correcta con tenant
        $leadUrl = url('/dashboard/' . $this->lead->tenant->uuid . '/leads/' . $this->lead->id . '/edit');
        
        return (new MailMessage)
            ->subject('🎉 Nuevo lead para ' . $tenantName)
            ->greeting('¡Hola ' . $notifiable->name . '!')
            ->line('Se te ha asignado un nuevo lead:')
            ->line('**Nombre:** ' . $this->lead->nombre . ' ' . ($this->lead->apellidos ?? ''))
            ->line('**Email:** ' . ($this->lead->email ?? 'No proporcionado'))
            ->line('**Teléfono:** ' . ($this->lead->telefono ?? 'No proporcionado'))
            ->line('**Curso:** ' . ($this->lead->course?->titulacion ?? 'No asignado'))
            ->action('Ver Lead', $leadUrl)
            ->line('¡No olvides hacer seguimiento pronto!')
            ->salutation('Saludos, ' . config('app.name'));
    }

    public function toDatabase($notifiable): array
    {
        // Construir URL correcta con tenant
        $actionUrl = '/dashboard/' . $this->lead->tenant->uuid . '/leads/' . $this->lead->id . '/edit';
        
        return [
            'title' => 'Nuevo Lead Asignado',
            'message' => 'Se te ha asignado el lead: ' . $this->lead->nombre,
            'lead_id' => $this->lead->id,
            'lead_name' => $this->lead->nombre,
            'lead_email' => $this->lead->email,
            'course_name' => $this->lead->course?->titulacion,
            'action_url' => $actionUrl,
        ];
    }

    public function toFilament($notifiable): FilamentNotification
    {
        return FilamentNotification::make()
            ->title('Nuevo Lead Asignado')
            ->body('Se te ha asignado el lead: ' . $this->lead->nombre . ' ' . ($this->lead->apellidos ?? ''))
            ->icon('heroicon-o-user-plus')
            ->iconColor('success')
            ->persistent();
    }
}
