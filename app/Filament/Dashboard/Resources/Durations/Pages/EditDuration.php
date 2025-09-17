<?php

namespace App\Filament\Dashboard\Resources\Durations\Pages;

use App\Filament\Dashboard\Resources\Durations\DurationResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ViewAction;
use Filament\Resources\Pages\EditRecord;

class EditDuration extends EditRecord
{
    protected static string $resource = DurationResource::class;

    protected function getHeaderActions(): array
    {
        return [
            ViewAction::make(),
            DeleteAction::make(),
        ];
    }
}
