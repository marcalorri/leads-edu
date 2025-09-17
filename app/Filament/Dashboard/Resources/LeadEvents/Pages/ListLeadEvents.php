<?php

namespace App\Filament\Dashboard\Resources\LeadEvents\Pages;

use App\Filament\Dashboard\Resources\LeadEvents\LeadEventResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListLeadEvents extends ListRecords
{
    protected static string $resource = LeadEventResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
