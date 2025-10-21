<?php

namespace App\Notifications;

use App\Models\Lead;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Filament\Notifications\Notification as FilamentNotification;

class LeadWonNotification extends Notification implements ShouldQueue
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
        $daysToWin = $this->lead->fecha_ganado 
            ? $this->lead->created_at->diffInDays($this->lead->fecha_ganado)
            : null;

        return (new MailMessage)
            ->subject(__('🎉 Lead Converted! - :name', ['name' => $this->lead->nombre]))
            ->greeting(__('Congratulations :name!', ['name' => $notifiable->name]))
            ->line(__('You have successfully converted a lead!'))
            ->line('**' . __('Client') . ':** ' . $this->lead->nombre . ' ' . ($this->lead->apellidos ?? ''))
            ->line('**' . __('Email') . ':** ' . ($this->lead->email ?? __('Not provided')))
            ->line('**' . __('Course') . ':** ' . ($this->lead->course?->titulacion ?? __('Not assigned')))
            ->when($daysToWin, function ($mail) use ($daysToWin) {
                return $mail->line('**' . __('Conversion time') . ':** ' . $daysToWin . ' ' . __('days'));
            })
            ->line('**' . __('Conversion date') . ':** ' . ($this->lead->fecha_ganado?->format('d/m/Y H:i') ?? __('Now')))
            ->action(__('View Lead'), url('/dashboard/leads/' . $this->lead->id))
            ->line(__('Excellent work! 🚀'))
            ->salutation(__('Regards') . ', ' . config('app.name'));
    }

    public function toDatabase($notifiable): array
    {
        return [
            'title' => __('🎉 Lead Converted'),
            'message' => __('You have converted the lead: :name!', ['name' => $this->lead->nombre]),
            'lead_id' => $this->lead->id,
            'lead_name' => $this->lead->nombre,
            'course_name' => $this->lead->course?->titulacion,
            'conversion_date' => $this->lead->fecha_ganado,
            'action_url' => '/dashboard/leads/' . $this->lead->id,
        ];
    }

    public function toFilament($notifiable): FilamentNotification
    {
        return FilamentNotification::make()
            ->title(__('🎉 Lead Converted!'))
            ->body(__('Congratulations! You have converted the lead: :name', ['name' => $this->lead->nombre . ' ' . ($this->lead->apellidos ?? '')]))
            ->icon('heroicon-o-trophy')
            ->iconColor('success')
            ->persistent()
            ->duration(10000); // 10 segundos
    }
}
