<?php

namespace App\Observers;

use App\Models\Lead;
use App\Models\Contact;
use App\Models\User;
use App\Notifications\NewLeadNotification;
use App\Notifications\LeadWonNotification;
use Filament\Notifications\Notification;

class LeadObserver
{
    /**
     * Handle the Lead "created" event.
     */
    public function creating(Lead $lead): void
    {
        
        // Si ya tiene contacto asignado, no hacer nada
        if ($lead->contact_id) {
            return;
        }

        // Buscar contacto existente por email o teléfono (sin global scope, excluyendo soft-deleted)
        // Solo buscar si tenemos email o teléfono válidos
        $existingContact = null;
        
        if (!empty($lead->email) || !empty($lead->telefono)) {
            $existingContact = Contact::withoutGlobalScopes()
                ->whereNull('deleted_at')
                ->where('tenant_id', $lead->tenant_id)
                ->where(function ($query) use ($lead) {
                    if (!empty($lead->email)) {
                        $query->where('email_principal', $lead->email);
                    }
                    if (!empty($lead->telefono)) {
                        $query->orWhere('telefono_principal', $lead->telefono);
                    }
                })
                ->first();
        }

        if ($existingContact) {
            // Asignar contacto existente
            $lead->contact_id = $existingContact->id;
        } else {
            // Crear nuevo contacto solo si tenemos datos mínimos
            if (!empty($lead->nombre)) {
                $newContact = Contact::create([
                    'tenant_id' => $lead->tenant_id,
                    'nombre_completo' => trim($lead->nombre . ' ' . ($lead->apellidos ?? '')),
                    'telefono_principal' => $lead->telefono ?: null,
                    'email_principal' => $lead->email ?: null,
                    'provincia_id' => $lead->provincia_id ?: null,
                    'preferencia_comunicacion' => !empty($lead->email) ? 'email' : 'telefono',
                ]);
                
                // Asignar el nuevo contacto al lead
                $lead->contact_id = $newContact->id;
            }
        }
    }

    /**
     * Handle the Lead "created" event.
     */
    public function created(Lead $lead): void
    {
        // Enviar notificación al asesor asignado
        if ($lead->asesor_id) {
            $asesor = User::find($lead->asesor_id);
            if ($asesor) {
                // Cargar relaciones necesarias para la notificación
                $lead->load(['tenant', 'course']);
                
                // Notificación por email y base de datos
                $asesor->notify(new NewLeadNotification($lead));
                
                // Notificación Filament
                Notification::make()
                    ->title('Nuevo Lead Asignado')
                    ->body('Se te ha asignado el lead: ' . $lead->nombre)
                    ->icon('heroicon-o-user-plus')
                    ->iconColor('success')
                    ->sendToDatabase($asesor);
            }
        }
    }

    /**
     * Handle the Lead "updated" event.
     */
    public function updated(Lead $lead): void
    {
        // Verificar si el estado ha cambiado
        if ($lead->isDirty('estado')) {
            $oldEstado = $lead->getOriginal('estado');
            $newEstado = $lead->estado;
            
            // Si cambió a 'ganado' y no tiene fecha_ganado
            if ($newEstado === 'ganado' && !$lead->fecha_ganado) {
                $lead->fecha_ganado = now();
                
                // Guardar sin disparar eventos para evitar recursión
                $lead->saveQuietly();
                
                // Enviar notificación de conversión
                if ($lead->asesor_id) {
                    $asesor = User::find($lead->asesor_id);
                    if ($asesor) {
                        $asesor->notify(new LeadWonNotification($lead));
                        
                        // Notificación Filament
                        Notification::make()
                            ->title('🎉 ¡Lead Convertido!')
                            ->body('¡Felicidades! Has convertido el lead: ' . $lead->nombre)
                            ->icon('heroicon-o-trophy')
                            ->iconColor('success')
                            ->duration(10000)
                            ->sendToDatabase($asesor);
                    }
                }
            }
            
            // Si cambió a 'perdido' y no tiene fecha_perdido
            if ($newEstado === 'perdido' && !$lead->fecha_perdido) {
                $lead->fecha_perdido = now();
                
                // Guardar sin disparar eventos para evitar recursión
                $lead->saveQuietly();
            }
            
            // Si cambió de 'ganado' a otro estado, limpiar fecha_ganado
            if ($oldEstado === 'ganado' && $newEstado !== 'ganado') {
                $lead->fecha_ganado = null;
                $lead->saveQuietly();
            }
            
            // Si cambió de 'perdido' a otro estado, limpiar fecha_perdido
            if ($oldEstado === 'perdido' && $newEstado !== 'perdido') {
                $lead->fecha_perdido = null;
                $lead->saveQuietly();
            }
        }
    }

    /**
     * Handle the Lead "deleted" event.
     */
    public function deleted(Lead $lead): void
    {
        //
    }

    /**
     * Handle the Lead "restored" event.
     */
    public function restored(Lead $lead): void
    {
        //
    }

    /**
     * Handle the Lead "force deleted" event.
     */
    public function forceDeleted(Lead $lead): void
    {
        //
    }
}
