<?php

namespace App\Filament\Dashboard\Resources\LeadNotes\Pages;

use App\Filament\Dashboard\Resources\LeadNotes\LeadNoteResource;
use Filament\Resources\Pages\CreateRecord;

class CreateLeadNote extends CreateRecord
{
    protected static string $resource = LeadNoteResource::class;
}
