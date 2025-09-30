<?php

namespace App\Filament\Dashboard\Resources\Leads\Pages;

use App\Filament\Dashboard\Resources\Leads\LeadResource;
use App\Filament\Dashboard\Widgets\LeadEventsCalendarWidget;
use Filament\Resources\Pages\Page;

class EventsCalendar extends Page
{
    protected static string $resource = LeadResource::class;
    
    protected string $view = 'filament.dashboard.resources.leads.pages.events-calendar';
    
    protected static ?string $title = 'Calendario de Eventos';
    
    protected static ?string $navigationLabel = 'Eventos para hoy';
    
    protected static ?string $slug = 'calendar';
    
    public static function getNavigationIcon(): ?string
    {
        return 'heroicon-o-calendar-days';
    }
    
    protected function getHeaderWidgets(): array
    {
        return [
            LeadEventsCalendarWidget::class,
        ];
    }
}
