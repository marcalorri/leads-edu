<?php

namespace App\Filament\Dashboard\Resources\LeadEvents\Pages;

use App\Filament\Dashboard\Resources\LeadEvents\LeadEventResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewLeadEvent extends ViewRecord
{
    protected static string $resource = LeadEventResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}
