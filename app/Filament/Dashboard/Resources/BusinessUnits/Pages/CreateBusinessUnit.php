<?php

namespace App\Filament\Dashboard\Resources\BusinessUnits\Pages;

use App\Filament\Dashboard\Resources\BusinessUnits\BusinessUnitResource;
use Filament\Resources\Pages\CreateRecord;

class CreateBusinessUnit extends CreateRecord
{
    protected static string $resource = BusinessUnitResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
