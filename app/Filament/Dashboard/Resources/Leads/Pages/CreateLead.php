<?php

namespace App\Filament\Dashboard\Resources\Leads\Pages;

use App\Filament\Dashboard\Resources\Leads\LeadResource;
use App\Services\LeadLimitService;
use Filament\Resources\Pages\CreateRecord;
use Filament\Notifications\Notification;
use Filament\Actions\Action;

class CreateLead extends CreateRecord
{
    protected static string $resource = LeadResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    protected function beforeCreate(): void
    {
        $tenant = filament()->getTenant();
        $leadLimitService = app(LeadLimitService::class);

        if (!$leadLimitService->canCreateLead($tenant)) {
            $maxLeads = $leadLimitService->getMaxLeads($tenant);
            $maxLeadsLabel = $maxLeads === PHP_INT_MAX ? 'ilimitados' : $maxLeads;
            
            Notification::make()
                ->danger()
                ->title('Límite de Leads Alcanzado')
                ->body("Has alcanzado el límite máximo de {$maxLeadsLabel} leads para tu plan actual. Actualiza tu suscripción para crear más leads.")
                ->persistent()
                ->actions([
                    Action::make('upgrade')
                        ->label('Ver Planes')
                        ->url(route('filament.dashboard.pages.subscriptions', ['tenant' => $tenant]))
                        ->button(),
                ])
                ->send();

            $this->halt();
        }
    }
}
