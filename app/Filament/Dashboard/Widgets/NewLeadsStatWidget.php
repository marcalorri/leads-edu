<?php

namespace App\Filament\Dashboard\Widgets;

use App\Models\Lead;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Filament\Widgets\Concerns\InteractsWithPageFilters;
use Carbon\Carbon;

class NewLeadsStatWidget extends BaseWidget
{
    use InteractsWithPageFilters;
    
    protected static ?int $sort = 1;
    
    protected ?string $pollingInterval = '30s';
    
    protected int | string | array $columnSpan = 1;
    
    protected function getStats(): array
    {
        $user = auth()->user();
        
        // Obtener filtros de la página
        $startDate = $this->pageFilters['start_date'] ?? now()->subDays(29)->toDateString();
        $endDate = $this->pageFilters['end_date'] ?? now()->toDateString();
        
        $startDate = Carbon::parse($startDate)->startOfDay();
        $endDate = Carbon::parse($endDate)->endOfDay();
        
        // Los global scopes ya manejan el filtrado automáticamente
        // Si el usuario puede ver todos los leads, verá todos
        // Si no, solo verá los suyos (filtrado automático por global scope)
        $query = Lead::query();
        
        // Contar leads nuevos en el rango de fechas
        $count = $query->clone()
            ->whereBetween('created_at', [$startDate, $endDate])
            ->count();
        
        // Comparación con período anterior
        $diffDays = $startDate->diffInDays($endDate) + 1;
        $previousStart = $startDate->copy()->subDays($diffDays);
        $previousEnd = $endDate->copy()->subDays($diffDays);
        
        $previousCount = $query->clone()
            ->whereBetween('created_at', [$previousStart, $previousEnd])
            ->count();
        
        $change = $previousCount > 0 ? (($count - $previousCount) / $previousCount) * 100 : 0;
        $changeIcon = $change >= 0 ? 'heroicon-m-arrow-trending-up' : 'heroicon-m-arrow-trending-down';
        $changeColor = $change >= 0 ? 'success' : 'danger';
        
        return [
            Stat::make(__('New Leads'), $count)
                ->description(($change >= 0 ? '+' : '') . number_format($change, 1) . '% ' . __('vs previous period'))
                ->descriptionIcon($changeIcon)
                ->color('info')
                ->chart([7, 2, 10, 3, 15, 4, 17])
        ];
    }
}
