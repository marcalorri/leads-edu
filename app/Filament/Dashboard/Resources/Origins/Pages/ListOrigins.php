<?php

namespace App\Filament\Dashboard\Resources\Origins\Pages;

use App\Filament\Dashboard\Resources\Origins\OriginResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListOrigins extends ListRecords
{
    protected static string $resource = OriginResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
