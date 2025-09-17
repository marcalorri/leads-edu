<?php

namespace App\Observers;

use App\Models\Lead;
use App\Models\Contact;
use Illuminate\Support\Facades\Log;

class LeadObserver
{
    /**
     * Handle the Lead "created" event.
     */
    public function creating(Lead $lead): void
    {
        Log::info('LeadObserver: Creating lead', ['email' => $lead->email, 'telefono' => $lead->telefono, 'tenant_id' => $lead->tenant_id]);
        
        // Si ya tiene contacto asignado, no hacer nada
        if ($lead->contact_id) {
            Log::info('LeadObserver: Lead already has contact_id', ['contact_id' => $lead->contact_id]);
            return;
        }

        // Buscar contacto existente por email o teléfono (sin global scope, excluyendo soft-deleted)
        $existingContact = Contact::withoutGlobalScopes()
            ->whereNull('deleted_at')
            ->where('tenant_id', $lead->tenant_id)
            ->where(function ($query) use ($lead) {
                $query->where('email_principal', $lead->email)
                      ->orWhere('telefono_principal', $lead->telefono);
            })
            ->first();

        Log::info('LeadObserver: Contact search result', ['found' => $existingContact ? true : false, 'contact_id' => $existingContact?->id]);

        if ($existingContact) {
            // Asignar contacto existente
            $lead->contact_id = $existingContact->id;
            Log::info('LeadObserver: Assigned existing contact', ['contact_id' => $existingContact->id]);
        } else {
            // Crear nuevo contacto
            $newContact = Contact::create([
                'tenant_id' => $lead->tenant_id,
                'nombre_completo' => $lead->nombre . ' ' . $lead->apellidos,
                'telefono_principal' => $lead->telefono,
                'email_principal' => $lead->email,
                'provincia_id' => $lead->provincia_id,
                'preferencia_comunicacion' => 'email',
            ]);
            
            // Asignar el nuevo contacto al lead
            $lead->contact_id = $newContact->id;
            Log::info('LeadObserver: Created new contact', ['contact_id' => $newContact->id]);
        }
    }

    /**
     * Handle the Lead "updated" event.
     */
    public function updated(Lead $lead): void
    {
        //
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
