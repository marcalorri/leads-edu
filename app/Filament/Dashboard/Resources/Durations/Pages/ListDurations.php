<?php

namespace App\Filament\Dashboard\Resources\Durations\Pages;

use App\Filament\Dashboard\Resources\Durations\DurationResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListDurations extends ListRecords
{
    protected static string $resource = DurationResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
