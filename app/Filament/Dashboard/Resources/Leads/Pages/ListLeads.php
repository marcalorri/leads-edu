<?php

namespace App\Filament\Dashboard\Resources\Leads\Pages;

use App\Filament\Dashboard\Resources\Leads\LeadResource;
use App\Filament\Dashboard\Imports\LeadImporter;
use App\Models\Lead;
use App\Services\LeadLimitService;
use Filament\Actions\CreateAction;
use Filament\Actions\ImportAction;
use Filament\Actions\Action;
use Filament\Resources\Pages\ListRecords;
use Filament\Schemas\Components\Tabs\Tab;
use Filament\Notifications\Notification;
use Illuminate\Database\Eloquent\Builder;

class ListLeads extends ListRecords
{
    protected static string $resource = LeadResource::class;

    public function mount(): void
    {
        parent::mount();
        
        $tenant = filament()->getTenant();
        $leadLimitService = app(LeadLimitService::class);

        if ($leadLimitService->shouldShowWarning($tenant)) {
            $remaining = $leadLimitService->getRemainingLeads($tenant);
            $percentage = $leadLimitService->getUsagePercentage($tenant);
            $isCritical = $leadLimitService->isCritical($tenant);

            $title = $isCritical ? __('⚠️ Lead Limit Almost Reached') : __('Approaching Lead Limit');
            $body = __('You have used :percentage% of your limit. You have :remaining leads remaining.', [
                'percentage' => number_format($percentage, 1),
                'remaining' => $remaining
            ]);

            Notification::make()
                ->warning()
                ->title($title)
                ->body($body)
                ->actions([
                    Action::make('upgrade')
                        ->label(__('Upgrade Plan'))
                        ->url(route('filament.dashboard.pages.subscriptions', ['tenant' => $tenant]))
                        ->button(),
                ])
                ->send();
        }
    }

    public ?string $activeTab = 'abierto';

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
            ImportAction::make()
                ->importer(LeadImporter::class)
                ->label(__('Import Leads'))
                ->icon('heroicon-o-arrow-up-tray')
                ->color('success'),
        ];
    }

    public function getTabs(): array
    {
        return [
            'all' => Tab::make(__('All'))
                ->badge(Lead::query()->count()),
            
            'abierto' => Tab::make(__('Open'))
                ->modifyQueryUsing(fn (Builder $query) => $query->where('estado', 'abierto'))
                ->badge(Lead::query()->where('estado', 'abierto')->count())
                ->badgeColor('warning'),
            
            'ganado' => Tab::make(__('Won'))
                ->modifyQueryUsing(fn (Builder $query) => $query->where('estado', 'ganado'))
                ->badge(Lead::query()->where('estado', 'ganado')->count())
                ->badgeColor('success'),
            
            'perdido' => Tab::make(__('Lost'))
                ->modifyQueryUsing(fn (Builder $query) => $query->where('estado', 'perdido'))
                ->badge(Lead::query()->where('estado', 'perdido')->count())
                ->badgeColor('danger'),
        ];
    }

}
