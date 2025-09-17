<?php

namespace App\Filament\Dashboard\Resources\Modalities\Pages;

use App\Filament\Dashboard\Resources\Modalities\ModalityResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ViewAction;
use Filament\Resources\Pages\EditRecord;

class EditModality extends EditRecord
{
    protected static string $resource = ModalityResource::class;

    protected function getHeaderActions(): array
    {
        return [
            ViewAction::make(),
            DeleteAction::make(),
        ];
    }
}
