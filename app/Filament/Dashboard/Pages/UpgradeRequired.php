<?php

namespace App\Filament\Dashboard\Pages;

use Filament\Pages\Page;
use Filament\Actions\Action;

class UpgradeRequired extends Page
{
    protected string $view = 'filament.dashboard.pages.upgrade-required';
    
    protected static ?string $title = 'Suscripción Requerida';
    
    protected static ?string $navigationLabel = null;
    
    protected static bool $shouldRegisterNavigation = false;
    
    protected static ?string $slug = 'upgrade-required';
    
    public function mount(): void
    {
        // Verificar si ya tiene suscripción y redirigir al dashboard
        $user = auth()->user();
        $tenant = filament()->getTenant();
        
        if ($tenant && ($user->isSubscribed('crm-plan', $tenant) || $user->isTrialing('crm-plan', $tenant))) {
            $tenantId = $tenant->uuid ?? $tenant->id;
            $this->redirect("/dashboard/{$tenantId}");
        }
    }
    
    protected function getHeaderActions(): array
    {
        return [
            Action::make('viewPlans')
                ->label('Ver Planes')
                ->icon('heroicon-o-credit-card')
                ->color('primary')
                ->url(function () {
                    $tenant = filament()->getTenant();
                    $tenantId = $tenant?->uuid ?? $tenant?->id ?? 'default';
                    return "/dashboard/{$tenantId}/subscriptions";
                })
                ->openUrlInNewTab(false),
                
            Action::make('backToDashboard')
                ->label('Volver al Dashboard')
                ->icon('heroicon-o-home')
                ->color('gray')
                ->url(function () {
                    $tenant = filament()->getTenant();
                    $tenantId = $tenant?->uuid ?? $tenant?->id ?? 'default';
                    return "/dashboard/{$tenantId}";
                })
                ->openUrlInNewTab(false),
        ];
    }
}
