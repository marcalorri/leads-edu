<?php

namespace App\Filament\Dashboard\Resources\Leads\Widgets;

use App\Models\LeadEvent;
use Guava\Calendar\Filament\CalendarWidget;
use Guava\Calendar\ValueObjects\FetchInfo;
use Guava\Calendar\Enums\CalendarViewType;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

class LeadEventsCalendarWidget extends CalendarWidget
{
    protected CalendarViewType $calendarView = CalendarViewType::DayGridMonth;
    
    public ?Model $record = null;
    
    public static function getWidgetLabel(): string
    {
        return 'Calendario de Eventos del Lead';
    }
    
    public function getColumnSpan(): int | string | array
    {
        return 'full';
    }
    
    protected function getEvents(FetchInfo $info): Collection
    {
        if (!$this->record) {
            return collect();
        }
        
        return LeadEvent::query()
            ->with(['lead', 'usuario'])
            ->where('lead_id', $this->record->id)
            ->whereBetween('fecha_programada', [$info->start, $info->end])
            ->get();
    }
    
    public function getHeight(): ?string
    {
        return '500px';
    }
}
