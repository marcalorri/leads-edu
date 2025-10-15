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
        
        // If there's no tenant, allow access (for global resources)
        if (!$tenant) {
            return true;
        }
        
        // If there's no authenticated user, no access
        if (!$user) {
            return false;
        }
        
        // Always allow access to global admins
        if ($user->is_admin) {
            return true;
        }
        
        // Check if has ANY active subscription or is on trial
        // We pass null as productSlug to accept any product
        return $user->isSubscribed(null, $tenant) || 
               $user->isTrialing(null, $tenant);
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
        return __('An active subscription is required to access CRM features.');
    }
}
