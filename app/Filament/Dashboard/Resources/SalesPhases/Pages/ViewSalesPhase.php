<?php

namespace App\Filament\Dashboard\Resources\SalesPhases\Pages;

use App\Filament\Dashboard\Resources\SalesPhases\SalesPhaseResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewSalesPhase extends ViewRecord
{
    protected static string $resource = SalesPhaseResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}
