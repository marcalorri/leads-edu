<?php

namespace App\Filament\Dashboard\Resources\Provinces\Pages;

use App\Filament\Dashboard\Resources\Provinces\ProvinceResource;
use Filament\Resources\Pages\CreateRecord;

class CreateProvince extends CreateRecord
{
    protected static string $resource = ProvinceResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
