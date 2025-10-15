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
     * Handle the Lead "creating" event.
     * Executes BEFORE saving the lead to the database.
     */
    public function creating(Lead $lead): void
    {
        // ========================================
        // STEP 1: Contact Management
        // ========================================
        
        // If already has assigned contact, do nothing
        if (!$lead->contact_id) {
            // Search for existing contact by email or phone (without global scope, excluding soft-deleted)
            // Only search if we have valid email or phone
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
                // Assign existing contact
                $lead->contact_id = $existingContact->id;
            } else {
                // Create new contact only if we have minimum data
                if (!empty($lead->nombre)) {
                    $newContact = Contact::create([
                        'tenant_id' => $lead->tenant_id,
                        'nombre_completo' => trim($lead->nombre . ' ' . ($lead->apellidos ?? '')),
                        'telefono_principal' => $lead->telefono ?: null,
                        'email_principal' => $lead->email ?: null,
                        'provincia_id' => $lead->provincia_id ?: null,
                        'preferencia_comunicacion' => !empty($lead->email) ? 'email' : 'telefono',
                    ]);
                    
                    // Assign the new contact to the lead
                    $lead->contact_id = $newContact->id;
                }
            }
        }
        
        // ========================================
        // STEP 2: Advisor Assignment (Priority Logic)
        // ========================================
        
        // If advisor was NOT explicitly specified, apply priority logic
        if (!$lead->asesor_id) {
            // Priority 1: Existing contact's advisor
            if ($lead->contact_id) {
                $contact = Contact::withoutGlobalScopes()->find($lead->contact_id);
                if ($contact && $contact->asesor_id) {
                    $lead->asesor_id = $contact->asesor_id;
                    return; // Already have advisor, exit
                }
            }
            
            // Priority 2: User creating the lead (if authentication context exists)
            if (auth()->check()) {
                $lead->asesor_id = auth()->id();
                return; // Already have advisor, exit
            }
            
            // Priority 3: NULL (assigned manually later)
            // Do nothing, leave asesor_id as null
        }
        // If already has explicitly specified asesor_id, respect it
    }

    /**
     * Handle the Lead "created" event.
     * Executes AFTER saving the lead to the database.
     */
    public function created(Lead $lead): void
    {
        // Sync advisor with contact if contact does NOT have advisor
        if ($lead->contact_id && $lead->asesor_id) {
            $contact = Contact::withoutGlobalScopes()->find($lead->contact_id);
            if ($contact && !$contact->asesor_id) {
                $contact->update(['asesor_id' => $lead->asesor_id]);
            }
        }
        
        // Send notification to assigned advisor
        if ($lead->asesor_id) {
            $asesor = User::find($lead->asesor_id);
            if ($asesor) {
                // Load necessary relationships for notification
                $lead->load(['tenant', 'course']);
                
                // Email and database notification
                $asesor->notify(new NewLeadNotification($lead));
                
                // Filament notification
                Notification::make()
                    ->title(__('New Lead Assigned'))
                    ->body(__('You have been assigned the lead: :name', ['name' => $lead->nombre]))
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
        // Sync advisor with contact if changed
        if ($lead->isDirty('asesor_id') && $lead->contact_id && $lead->asesor_id) {
            $contact = Contact::find($lead->contact_id);
            if ($contact) {
                $contact->update(['asesor_id' => $lead->asesor_id]);
            }
        }
        
        // Check if status has changed
        if ($lead->isDirty('estado')) {
            $oldEstado = $lead->getOriginal('estado');
            $newEstado = $lead->estado;
            
            // If changed to 'ganado' and doesn't have fecha_ganado
            if ($newEstado === 'ganado' && !$lead->fecha_ganado) {
                $lead->fecha_ganado = now();
                
                // Save without triggering events to avoid recursion
                $lead->saveQuietly();
                
                // Send conversion notification
                if ($lead->asesor_id) {
                    $asesor = User::find($lead->asesor_id);
                    if ($asesor) {
                        $asesor->notify(new LeadWonNotification($lead));
                        
                        // Filament notification
                        Notification::make()
                            ->title(__('🎉 Lead Converted!'))
                            ->body(__('Congratulations! You have converted the lead: :name', ['name' => $lead->nombre]))
                            ->icon('heroicon-o-trophy')
                            ->iconColor('success')
                            ->duration(10000)
                            ->sendToDatabase($asesor);
                    }
                }
            }
            
            // If changed to 'perdido' and doesn't have fecha_perdido
            if ($newEstado === 'perdido' && !$lead->fecha_perdido) {
                $lead->fecha_perdido = now();
                
                // Save without triggering events to avoid recursion
                $lead->saveQuietly();
            }
            
            // If changed from 'ganado' to another status, clear fecha_ganado
            if ($oldEstado === 'ganado' && $newEstado !== 'ganado') {
                $lead->fecha_ganado = null;
                $lead->saveQuietly();
            }
            
            // If changed from 'perdido' to another status, clear fecha_perdido
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
