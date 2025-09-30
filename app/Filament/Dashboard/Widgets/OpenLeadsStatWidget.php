<?php

namespace App\Filament\Dashboard\Widgets;

use App\Models\Lead;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Filament\Widgets\Concerns\InteractsWithPageFilters;
use Carbon\Carbon;

class OpenLeadsStatWidget extends BaseWidget
{
    use InteractsWithPageFilters;
    
    protected static ?int $sort = 2;
    
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
        $query = Lead::query();
        
        // Contar leads abiertos creados en el rango de fechas
        $count = $query->clone()
            ->where('estado', 'abierto')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->count();
        
        // Comparación con período anterior
        $diffDays = $startDate->diffInDays($endDate) + 1;
        $previousStart = $startDate->copy()->subDays($diffDays);
        $previousEnd = $endDate->copy()->subDays($diffDays);
        
        $previousCount = $query->clone()
            ->where('estado', 'abierto')
            ->whereBetween('created_at', [$previousStart, $previousEnd])
            ->count();
        
        $change = $previousCount > 0 ? (($count - $previousCount) / $previousCount) * 100 : 0;
        $changeIcon = $change >= 0 ? 'heroicon-m-arrow-trending-up' : 'heroicon-m-arrow-trending-down';
        $changeColor = $change >= 0 ? 'warning' : 'danger';
        
        return [
            Stat::make('Leads Abiertos', $count)
                ->description(($change >= 0 ? '+' : '') . number_format($change, 1) . '% vs período anterior')
                ->descriptionIcon($changeIcon)
                ->color('warning')
                ->chart([3, 8, 5, 12, 7, 9, 6])
        ];
    }
    
    protected function getDateRange(): array
    {
        return match($this->filter) {
            'today' => [Carbon::today(), Carbon::today()->endOfDay()],
            'yesterday' => [Carbon::yesterday(), Carbon::yesterday()->endOfDay()],
            'last_7_days' => [Carbon::now()->subDays(6), Carbon::now()],
            'last_30_days' => [Carbon::now()->subDays(29), Carbon::now()],
            'this_month' => [Carbon::now()->startOfMonth(), Carbon::now()->endOfMonth()],
            'last_month' => [Carbon::now()->subMonth()->startOfMonth(), Carbon::now()->subMonth()->endOfMonth()],
            default => [Carbon::now()->subDays(29), Carbon::now()],
        };
    }
    
    protected function getPreviousDateRange(): array
    {
        $current = $this->getDateRange();
        $diff = $current[0]->diffInDays($current[1]) + 1;
        
        return [
            $current[0]->copy()->subDays($diff),
            $current[1]->copy()->subDays($diff)
        ];
    }
}
