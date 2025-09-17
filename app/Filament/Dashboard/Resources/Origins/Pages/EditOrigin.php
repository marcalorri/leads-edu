<?php

namespace App\Filament\Dashboard\Resources\Origins\Pages;

use App\Filament\Dashboard\Resources\Origins\OriginResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ViewAction;
use Filament\Resources\Pages\EditRecord;

class EditOrigin extends EditRecord
{
    protected static string $resource = OriginResource::class;

    protected function getHeaderActions(): array
    {
        return [
            ViewAction::make(),
            DeleteAction::make(),
        ];
    }
}
