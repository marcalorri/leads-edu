<?php

namespace App\Filament\Dashboard\Resources\NullReasons\Pages;

use App\Filament\Dashboard\Resources\NullReasons\NullReasonResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewNullReason extends ViewRecord
{
    protected static string $resource = NullReasonResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}
