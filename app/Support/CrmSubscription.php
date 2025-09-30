<?php

namespace App\Support;

use Illuminate\Support\Facades\Auth;

class CrmSubscription
{
    /**
     * Check if the current user has an active CRM subscription
     */
    public static function isActive(): bool
    {
        $user = Auth::user();
        $tenant = filament()->getTenant();
        
        // Si no hay tenant, permitir acceso (para recursos globales)
        if (!$tenant) {
            return true;
        }
        
        // Si no hay usuario autenticado, no tiene acceso
        if (!$user) {
            return false;
        }
        
        // Permitir acceso a admins globales siempre
        if ($user->is_admin) {
            return true;
        }
        
        // Verificar si tiene suscripción CRM activa o está en trial
        return $user->isSubscribed('crm-plan', $tenant) || 
               $user->isTrialing('crm-plan', $tenant);
    }
    
    /**
     * Check if the current user does NOT have an active CRM subscription
     */
    public static function isInactive(): bool
    {
        return !static::isActive();
    }
    
    /**
     * Get the subscriptions URL for the current tenant
     */
    public static function getUpgradeUrl(): string
    {
        $tenant = filament()->getTenant();
        $tenantId = $tenant?->uuid ?? $tenant?->id ?? 'default';
        
        return "/dashboard/{$tenantId}/subscriptions";
    }
    
    /**
     * Get the CRM subscription status message
     */
    public static function getStatusMessage(): string
    {
        return 'Se requiere una suscripción activa para acceder a las funcionalidades del CRM.';
    }
}
