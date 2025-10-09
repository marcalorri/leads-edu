<?php

namespace App\Filament\Dashboard\Resources\Origins\Pages;

use App\Filament\Dashboard\Resources\Origins\OriginResource;
use Filament\Resources\Pages\CreateRecord;

class CreateOrigin extends CreateRecord
{
    protected static string $resource = OriginResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
