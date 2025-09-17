<?php

namespace App\Filament\Dashboard\Resources\Modalities\Pages;

use App\Filament\Dashboard\Resources\Modalities\ModalityResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewModality extends ViewRecord
{
    protected static string $resource = ModalityResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}
