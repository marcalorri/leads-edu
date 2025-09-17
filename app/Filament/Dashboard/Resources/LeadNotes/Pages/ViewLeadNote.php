<?php

namespace App\Filament\Dashboard\Resources\LeadNotes\Pages;

use App\Filament\Dashboard\Resources\LeadNotes\LeadNoteResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewLeadNote extends ViewRecord
{
    protected static string $resource = LeadNoteResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}
