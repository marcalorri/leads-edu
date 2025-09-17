<?php

namespace App\Filament\Dashboard\Resources\LeadEvents\Pages;

use App\Filament\Dashboard\Resources\LeadEvents\LeadEventResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ForceDeleteAction;
use Filament\Actions\RestoreAction;
use Filament\Actions\ViewAction;
use Filament\Resources\Pages\EditRecord;

class EditLeadEvent extends EditRecord
{
    protected static string $resource = LeadEventResource::class;

    protected function getHeaderActions(): array
    {
        return [
            ViewAction::make(),
            DeleteAction::make(),
            ForceDeleteAction::make(),
            RestoreAction::make(),
        ];
    }
}
