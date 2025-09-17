<?php

namespace App\Filament\Dashboard\Resources\Origins\Pages;

use App\Filament\Dashboard\Resources\Origins\OriginResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewOrigin extends ViewRecord
{
    protected static string $resource = OriginResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}
