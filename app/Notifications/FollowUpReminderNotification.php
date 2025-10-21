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
            ->subject(__('⏰ Follow-up Reminder - :name', ['name' => $this->lead->nombre]))
            ->greeting(__('Hello :name', ['name' => $notifiable->name]))
            ->line(__('You have a pending follow-up:'))
            ->line('**' . __('Name') . ':** ' . $this->lead->nombre . ' ' . ($this->lead->apellidos ?? ''))
            ->line('**' . __('Email') . ':** ' . ($this->lead->email ?? __('Not provided')))
            ->line('**' . __('Phone') . ':** ' . ($this->lead->telefono ?? __('Not provided')))
            ->line('**' . __('Status') . ':** ' . ucfirst($this->lead->estado));

        if ($this->event) {
            $mail->line('**' . __('Scheduled action') . ':** ' . $this->event->titulo)
                ->line('**' . __('Description') . ':** ' . ($this->event->descripcion ?? __('No description')))
                ->line('**' . __('Scheduled date') . ':** ' . $this->event->fecha_programada->format('d/m/Y H:i'));
        } else {
            $daysSinceCreated = (int) $this->lead->created_at->diffInDays(now());
            $mail->line('**' . __('Days without follow-up') . ':** ' . $daysSinceCreated . ' ' . __('days'));
        }

        // Construir URL correcta con tenant
        $leadUrl = url('/dashboard/' . $this->lead->tenant->uuid . '/leads/' . $this->lead->id . '/edit');

        return $mail->action(__('View Lead'), $leadUrl)
            ->line(__('Don\'t miss this opportunity!'))
            ->salutation(__('Regards') . ', ' . config('app.name'));
    }

    public function toDatabase($notifiable): array
    {
        // Construir URL correcta con tenant
        $actionUrl = '/dashboard/' . $this->lead->tenant->uuid . '/leads/' . $this->lead->id . '/edit';
        
        return [
            'title' => __('⏰ Follow-up Reminder'),
            'message' => __('Pending follow-up for: :name', ['name' => $this->lead->nombre]),
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
        $body = __('Pending follow-up for: :name', ['name' => $this->lead->nombre]);
        
        if ($this->event) {
            $body .= ' - ' . $this->event->titulo;
        }

        return FilamentNotification::make()
            ->title(__('⏰ Follow-up Reminder'))
            ->body($body)
            ->icon('heroicon-o-clock')
            ->iconColor('warning')
            ->persistent();
    }
}
