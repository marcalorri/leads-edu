<?php

namespace App\Filament\Dashboard\Resources\ApiTokenResource\Pages;

use App\Filament\Dashboard\Resources\ApiTokenResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewApiToken extends ViewRecord
{
    protected static string $resource = ApiTokenResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
