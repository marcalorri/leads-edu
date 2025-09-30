<?php

namespace App\Filament\Dashboard\Resources\Leads\Pages;

use App\Filament\Dashboard\Resources\Leads\LeadResource;
use App\Filament\Dashboard\Resources\Leads\Widgets\LeadEventsCalendarWidget;
use Filament\Actions\EditAction;
use Filament\Actions\Action;
use Filament\Resources\Pages\ViewRecord;

class ViewLead extends ViewRecord
{
    protected static string $resource = LeadResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
    
    protected function getFooterWidgets(): array
    {
        return [
            LeadEventsCalendarWidget::make([
                'record' => $this->record,
            ]),
        ];
    }
}
