<?php

namespace App\Filament\Dashboard\Resources\SalesPhases\Pages;

use App\Filament\Dashboard\Resources\SalesPhases\SalesPhaseResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ViewAction;
use Filament\Resources\Pages\EditRecord;

class EditSalesPhase extends EditRecord
{
    protected static string $resource = SalesPhaseResource::class;

    protected function getHeaderActions(): array
    {
        return [
            ViewAction::make(),
            DeleteAction::make(),
        ];
    }
}
