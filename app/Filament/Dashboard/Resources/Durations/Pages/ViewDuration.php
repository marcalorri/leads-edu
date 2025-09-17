<?php

namespace App\Filament\Dashboard\Resources\Durations\Pages;

use App\Filament\Dashboard\Resources\Durations\DurationResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewDuration extends ViewRecord
{
    protected static string $resource = DurationResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}
