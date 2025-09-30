<?php

namespace App\Filament\Dashboard\Resources\LeadEvents\Widgets;

use App\Models\LeadEvent;
use Guava\Calendar\Filament\CalendarWidget;
use Guava\Calendar\ValueObjects\FetchInfo;
use Guava\Calendar\ValueObjects\EventClickInfo;
use Guava\Calendar\Enums\CalendarViewType;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

class AllEventsCalendarWidget extends CalendarWidget
{
    protected CalendarViewType $calendarView = CalendarViewType::DayGridMonth;
    
    protected bool $eventClickEnabled = true;
    
    public static function getWidgetLabel(): string
    {
        return 'Calendario de Todos los Eventos';
    }
    
    public function getColumnSpan(): int | string | array
    {
        return 'full';
    }
    
    public function getHeight(): ?string
    {
        return '600px';
    }
    
    protected function getEvents(FetchInfo $info): Collection
    {
        $user = auth()->user();
        
        // Obtener todos los eventos en el rango de fechas visible
        $query = LeadEvent::query()
            ->with(['lead', 'usuario'])
            ->whereBetween('fecha_programada', [$info->start, $info->end]);
            
        // Aplicar filtros de permisos
        if (!$user->is_admin) {
            $query->where('usuario_id', $user->id);
        }
        
        return $query->get();
    }
    
    // Manejar clic en eventos - redirigir a editar lead
    protected function onEventClick(EventClickInfo $info, Model $event, ?string $action = null): void
    {
        // El evento ya viene como parámetro
        if ($event instanceof LeadEvent && $event->lead) {
            // Redirigir a la página de edición del lead
            $this->redirect(route('filament.dashboard.resources.leads.edit', [
                'tenant' => filament()->getTenant(),
                'record' => $event->lead
            ]));
        }
    }
}
