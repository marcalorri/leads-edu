<?php

namespace App\Console\Commands;

use App\Models\Lead;
use App\Models\LeadEvent;
use App\Models\User;
use App\Notifications\FollowUpReminderNotification;
use Illuminate\Console\Command;
use Filament\Notifications\Notification;

class SendFollowUpReminders extends Command
{
    protected $signature = 'leads:send-reminders';
    protected $description = 'Enviar recordatorios de seguimiento para leads y eventos programados';

    public function handle()
    {
        $this->info('Enviando recordatorios de seguimiento...');

        // 1. Recordatorios de eventos programados
        $this->sendEventReminders();

        // 2. Recordatorios de leads sin seguimiento
        $this->sendLeadFollowUpReminders();

        $this->info('Recordatorios enviados correctamente.');
    }

    private function sendEventReminders()
    {
        // Eventos que deben recordarse (15 minutos antes por defecto)
        $upcomingEvents = LeadEvent::with(['lead', 'lead.asesor', 'lead.tenant'])
            ->where('estado', 'pendiente')
            ->where('requiere_recordatorio', true)
            ->where('fecha_programada', '<=', now()->addMinutes(15))
            ->where('fecha_programada', '>', now())
            ->get();

        foreach ($upcomingEvents as $event) {
            if ($event->lead && $event->lead->asesor) {
                // Notificación por email
                $event->lead->asesor->notify(
                    new FollowUpReminderNotification($event->lead, $event)
                );

                // Notificación Filament
                Notification::make()
                    ->title('⏰ Recordatorio de Evento')
                    ->body('Evento programado: ' . $event->titulo . ' para ' . $event->lead->nombre)
                    ->icon('heroicon-o-clock')
                    ->iconColor('warning')
                    ->sendToDatabase($event->lead->asesor);

                $this->line('Recordatorio enviado para evento: ' . $event->titulo);
            }
        }
    }

    private function sendLeadFollowUpReminders()
    {
        // Leads abiertos sin seguimiento en los últimos 3 días
        // Y que NO hayan recibido un recordatorio en las últimas 24 horas
        $leadsNeedingFollowUp = Lead::with(['asesor', 'tenant'])
            ->where('estado', 'abierto')
            ->whereDoesntHave('events', function ($query) {
                // No tiene eventos creados en los últimos 3 días
                $query->where('created_at', '>=', now()->subDays(3));
            })
            ->whereDoesntHave('events', function ($query) {
                // No tiene eventos futuros programados
                $query->where('fecha_programada', '>', now())
                    ->whereIn('estado', ['pendiente', 'en_progreso']);
            })
            ->where('created_at', '<=', now()->subDays(3))
            ->where(function ($query) {
                // No se ha enviado recordatorio nunca O hace más de 24 horas
                $query->whereNull('last_reminder_sent_at')
                    ->orWhere('last_reminder_sent_at', '<=', now()->subHours(24));
            })
            ->get();

        foreach ($leadsNeedingFollowUp as $lead) {
            if ($lead->asesor) {
                // Notificación por email
                $lead->asesor->notify(
                    new FollowUpReminderNotification($lead)
                );

                // Notificación Filament
                Notification::make()
                    ->title('📋 Seguimiento Pendiente')
                    ->body('Lead sin seguimiento: ' . $lead->nombre . ' (3+ días)')
                    ->icon('heroicon-o-exclamation-triangle')
                    ->iconColor('danger')
                    ->sendToDatabase($lead->asesor);

                // Actualizar timestamp del último recordatorio enviado
                $lead->update(['last_reminder_sent_at' => now()]);

                $this->line('Recordatorio de seguimiento enviado para: ' . $lead->nombre);
            }
        }
    }
}
