<?php

namespace App\Filament\Dashboard\Resources\SalesPhases\Pages;

use App\Filament\Dashboard\Resources\SalesPhases\SalesPhaseResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListSalesPhases extends ListRecords
{
    protected static string $resource = SalesPhaseResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
