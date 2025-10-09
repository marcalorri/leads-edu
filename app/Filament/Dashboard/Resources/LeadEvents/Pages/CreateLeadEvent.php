<?php

namespace App\Filament\Dashboard\Resources\LeadEvents\Pages;

use App\Filament\Dashboard\Resources\LeadEvents\LeadEventResource;
use Filament\Resources\Pages\CreateRecord;

class CreateLeadEvent extends CreateRecord
{
    protected static string $resource = LeadEventResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
