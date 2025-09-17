<?php

namespace App\Filament\Dashboard\Resources\NullReasons\Pages;

use App\Filament\Dashboard\Resources\NullReasons\NullReasonResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListNullReasons extends ListRecords
{
    protected static string $resource = NullReasonResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
