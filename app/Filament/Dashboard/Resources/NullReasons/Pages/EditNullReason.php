<?php

namespace App\Filament\Dashboard\Resources\NullReasons\Pages;

use App\Filament\Dashboard\Resources\NullReasons\NullReasonResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ViewAction;
use Filament\Resources\Pages\EditRecord;

class EditNullReason extends EditRecord
{
    protected static string $resource = NullReasonResource::class;

    protected function getHeaderActions(): array
    {
        return [
            ViewAction::make(),
            DeleteAction::make(),
        ];
    }
}
