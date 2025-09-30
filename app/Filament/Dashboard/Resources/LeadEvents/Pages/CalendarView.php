<?php

namespace App\Filament\Dashboard\Resources\LeadEvents\Pages;

use App\Filament\Dashboard\Resources\LeadEvents\LeadEventResource;
use App\Filament\Dashboard\Resources\LeadEvents\Widgets\AllEventsCalendarWidget;
use Filament\Resources\Pages\Page;

class CalendarView extends Page
{
    protected static string $resource = LeadEventResource::class;
    
    protected string $view = 'filament.dashboard.resources.lead-events.pages.calendar-view';
    
    protected static ?string $title = 'Vista de Calendario';
    
    protected static ?string $navigationLabel = 'Calendario';
    
    protected static ?string $slug = 'calendar';
    
    public static function getNavigationIcon(): ?string
    {
        return 'heroicon-o-calendar-days';
    }
    
    protected function getHeaderWidgets(): array
    {
        return [
            AllEventsCalendarWidget::class,
        ];
    }
}
