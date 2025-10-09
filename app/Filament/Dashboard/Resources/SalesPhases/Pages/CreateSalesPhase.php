<?php

namespace App\Filament\Dashboard\Resources\SalesPhases\Pages;

use App\Filament\Dashboard\Resources\SalesPhases\SalesPhaseResource;
use Filament\Resources\Pages\CreateRecord;

class CreateSalesPhase extends CreateRecord
{
    protected static string $resource = SalesPhaseResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
