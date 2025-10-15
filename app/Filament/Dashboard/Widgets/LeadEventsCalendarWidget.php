<?php

namespace App\Filament\Dashboard\Widgets;

use App\Models\LeadEvent;
use Guava\Calendar\Filament\CalendarWidget;
use Guava\Calendar\ValueObjects\FetchInfo;
use Guava\Calendar\ValueObjects\EventClickInfo;
use Guava\Calendar\Enums\CalendarViewType;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

class LeadEventsCalendarWidget extends CalendarWidget
{
    protected CalendarViewType $calendarView = CalendarViewType::ListWeek;
    
    protected bool $eventClickEnabled = true;
    
    public static function getWidgetLabel(): string
    {
        return __('Next 5 Events');
    }
    
    public function getColumnSpan(): int | string | array
    {
        return 2; // Mitad del ancho (de 4 columnas)
    }
    
    public function getHeight(): ?string
    {
        return '400px';
    }
    
    protected function getEvents(FetchInfo $info): Collection
    {
        $user = auth()->user();
        
        // Obtener los próximos 5 eventos (desde ahora en adelante)
        $query = LeadEvent::query()
            ->with(['lead', 'usuario'])
            ->where('fecha_programada', '>=', now())
            ->orderBy('fecha_programada', 'asc')
            ->limit(5);
            
        // Aplicar filtros de permisos - solo eventos del usuario si no puede ver todos
        if (!$user->canViewAllLeads()) {
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
