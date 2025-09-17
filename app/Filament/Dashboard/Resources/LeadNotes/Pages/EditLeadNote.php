<?php

namespace App\Filament\Dashboard\Resources\LeadNotes\Pages;

use App\Filament\Dashboard\Resources\LeadNotes\LeadNoteResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ForceDeleteAction;
use Filament\Actions\RestoreAction;
use Filament\Actions\ViewAction;
use Filament\Resources\Pages\EditRecord;

class EditLeadNote extends EditRecord
{
    protected static string $resource = LeadNoteResource::class;

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
