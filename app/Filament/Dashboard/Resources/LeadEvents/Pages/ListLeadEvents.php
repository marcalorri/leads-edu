<?php

namespace App\Filament\Dashboard\Resources\LeadEvents\Pages;

use App\Filament\Dashboard\Resources\LeadEvents\LeadEventResource;
use App\Filament\Dashboard\Resources\LeadEvents\Widgets\AllEventsCalendarWidget;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListLeadEvents extends ListRecords
{
    protected static string $resource = LeadEventResource::class;


    
    protected function getHeaderWidgets(): array
    {
        return [
            AllEventsCalendarWidget::class,
        ];
    }
}
