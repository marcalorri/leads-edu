<?php

namespace App\Filament\Dashboard\Resources\NullReasons\Pages;

use App\Filament\Dashboard\Resources\NullReasons\NullReasonResource;
use Filament\Resources\Pages\CreateRecord;

class CreateNullReason extends CreateRecord
{
    protected static string $resource = NullReasonResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
