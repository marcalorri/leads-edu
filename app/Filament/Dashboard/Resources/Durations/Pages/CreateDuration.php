<?php

namespace App\Filament\Dashboard\Resources\Durations\Pages;

use App\Filament\Dashboard\Resources\Durations\DurationResource;
use Filament\Resources\Pages\CreateRecord;

class CreateDuration extends CreateRecord
{
    protected static string $resource = DurationResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
