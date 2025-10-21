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
        $tenantName = $this->lead->tenant->name ?? __('your organization');
        
        // Construir URL correcta con tenant
        $leadUrl = url('/dashboard/' . $this->lead->tenant->uuid . '/leads/' . $this->lead->id . '/edit');
        
        return (new MailMessage)
            ->subject(__('🎉 New lead for :tenant', ['tenant' => $tenantName]))
            ->greeting(__('Hello :name!', ['name' => $notifiable->name]))
            ->line(__('You have been assigned a new lead:'))
            ->line('**' . __('Name') . ':** ' . $this->lead->nombre . ' ' . ($this->lead->apellidos ?? ''))
            ->line('**' . __('Email') . ':** ' . ($this->lead->email ?? __('Not provided')))
            ->line('**' . __('Phone') . ':** ' . ($this->lead->telefono ?? __('Not provided')))
            ->line('**' . __('Course') . ':** ' . ($this->lead->course?->titulacion ?? __('Not assigned')))
            ->action(__('View Lead'), $leadUrl)
            ->line(__('Don\'t forget to follow up soon!'))
            ->salutation(__('Regards') . ', ' . config('app.name'));
    }

    public function toDatabase($notifiable): array
    {
        // Construir URL correcta con tenant
        $actionUrl = '/dashboard/' . $this->lead->tenant->uuid . '/leads/' . $this->lead->id . '/edit';
        
        return [
            'title' => __('New Lead Assigned'),
            'message' => __('You have been assigned the lead: :name', ['name' => $this->lead->nombre]),
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
            ->title(__('New Lead Assigned'))
            ->body(__('You have been assigned the lead: :name', ['name' => $this->lead->nombre . ' ' . ($this->lead->apellidos ?? '')]))
            ->icon('heroicon-o-user-plus')
            ->iconColor('success')
            ->persistent();
    }
}
