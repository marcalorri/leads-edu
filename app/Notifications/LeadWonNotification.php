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
            ->subject('🎉 ¡Lead Convertido! - ' . $this->lead->nombre)
            ->greeting('¡Felicidades ' . $notifiable->name . '!')
            ->line('¡Has convertido exitosamente un lead!')
            ->line('**Cliente:** ' . $this->lead->nombre . ' ' . ($this->lead->apellidos ?? ''))
            ->line('**Email:** ' . ($this->lead->email ?? 'No proporcionado'))
            ->line('**Curso:** ' . ($this->lead->course?->titulacion ?? 'No asignado'))
            ->when($daysToWin, function ($mail) use ($daysToWin) {
                return $mail->line('**Tiempo de conversión:** ' . $daysToWin . ' días');
            })
            ->line('**Fecha de conversión:** ' . ($this->lead->fecha_ganado?->format('d/m/Y H:i') ?? 'Ahora'))
            ->action('Ver Lead', url('/dashboard/leads/' . $this->lead->id))
            ->line('¡Excelente trabajo! 🚀')
            ->salutation('Saludos, ' . config('app.name'));
    }

    public function toDatabase($notifiable): array
    {
        return [
            'title' => '🎉 Lead Convertido',
            'message' => '¡Has convertido el lead: ' . $this->lead->nombre . '!',
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
            ->title('🎉 ¡Lead Convertido!')
            ->body('¡Felicidades! Has convertido el lead: ' . $this->lead->nombre . ' ' . ($this->lead->apellidos ?? ''))
            ->icon('heroicon-o-trophy')
            ->iconColor('success')
            ->persistent()
            ->duration(10000); // 10 segundos
    }
}
