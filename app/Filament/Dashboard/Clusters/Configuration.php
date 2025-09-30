<?php

namespace App\Filament\Dashboard\Clusters;

use BackedEnum;
use Filament\Clusters\Cluster;

class Configuration extends Cluster
{
    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-cog-6-tooth';

    protected static ?string $navigationLabel = 'Configuración';

    protected static ?int $navigationSort = 9999;

    protected static bool $shouldRegisterNavigation = true;

    public static function shouldRegisterNavigation(): bool
    {
        $user = auth()->user();
        $tenant = filament()->getTenant();
        
        if (!$tenant || !$user) {
            return false;
        }
        
        // Admins globales siempre ven la configuración
        if ($user->is_admin) {
            return true;
        }
        
        // Solo mostrar configuración si tiene suscripción CRM o puede gestionar configuración
        return $user->isSubscribed('crm-plan', $tenant) || 
               $user->isTrialing('crm-plan', $tenant) ||
               $user->canManageConfiguration($tenant);
    }

    public static function getNavigationGroup(): ?string
    {
        return null;
    }

    public static function getNavigationSort(): ?int
    {
        return 9999;
    }

    public static function getNavigationBadge(): ?string
    {
        return null;
    }

    public static function getNavigationBadgeColor(): string|array|null
    {
        return null;
    }
}
