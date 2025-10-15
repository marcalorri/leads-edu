<?php

namespace App\Filament\Dashboard\Widgets;

use App\Models\Lead;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Carbon\Carbon;

class WonLeadsStatWidget extends BaseWidget
{
    protected static ?int $sort = 3;
    
    protected ?string $pollingInterval = '30s';
    
    protected int | string | array $columnSpan = 1;
    
    public ?string $filter = 'last_30_days';
    
    protected function getStats(): array
    {
        $user = auth()->user();
        $dateRange = $this->getDateRange();
        
        // Los global scopes ya manejan el filtrado automáticamente
        // Si el usuario puede ver todos los leads, verá todos
        // Si no, solo verá los suyos (filtrado automático por global scope)
        $query = Lead::query();
        
        // Contar leads ganados en el rango de fechas
        $count = $query->clone()
            ->where('estado', 'ganado')
            ->whereBetween('created_at', $dateRange)
            ->count();
        
        // Comparación con período anterior
        $previousRange = $this->getPreviousDateRange();
        $previousCount = $query->clone()
            ->where('estado', 'ganado')
            ->whereBetween('created_at', $previousRange)
            ->count();
        
        $change = $previousCount > 0 ? (($count - $previousCount) / $previousCount) * 100 : 0;
        $changeIcon = $change >= 0 ? 'heroicon-m-arrow-trending-up' : 'heroicon-m-arrow-trending-down';
        $changeColor = $change >= 0 ? 'success' : 'danger';
        
        return [
            Stat::make(__('Won Leads'), $count)
                ->description(($change >= 0 ? '+' : '') . number_format($change, 1) . '% ' . __('vs previous period'))
                ->descriptionIcon($changeIcon)
                ->color('success')
                ->chart([2, 4, 6, 8, 10, 12, 15])
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
