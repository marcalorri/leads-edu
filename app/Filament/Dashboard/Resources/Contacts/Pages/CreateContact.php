<?php

namespace App\Filament\Dashboard\Resources\Contacts\Pages;

use App\Filament\Dashboard\Resources\Contacts\ContactResource;
use Filament\Resources\Pages\CreateRecord;

class CreateContact extends CreateRecord
{
    protected static string $resource = ContactResource::class;
}
