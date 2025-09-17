<?php

namespace App\Filament\Dashboard\Resources\Leads\Pages;

use App\Filament\Dashboard\Resources\Leads\LeadResource;
use Filament\Resources\Pages\CreateRecord;

class CreateLead extends CreateRecord
{
    protected static string $resource = LeadResource::class;
}
