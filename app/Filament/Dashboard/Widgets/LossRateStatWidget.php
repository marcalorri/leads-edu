<?php

namespace App\Filament\Dashboard\Widgets;

use App\Models\Lead;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Carbon\Carbon;

class LossRateStatWidget extends BaseWidget
{
    protected static ?int $sort = 6;
    
    protected ?string $pollingInterval = '30s';
    
    protected int | string | array $columnSpan = 1;
    
    public ?string $filter = 'last_30_days';
    
    protected function getStats(): array
    {

        $dateRange = $this->getDateRange();
        
        // Los global scopes ya manejan el filtrado automáticamente
        $query = Lead::query();
        
        // Calcular tasa de pérdida en el rango de fechas
        $totalLeads = $query->clone()->whereBetween('created_at', $dateRange)->count();
        $lostLeads = $query->clone()
            ->where('estado', 'perdido')
            ->whereBetween('created_at', $dateRange)
            ->count();
        
        $lossRate = $totalLeads > 0 ? ($lostLeads / $totalLeads) * 100 : 0;
        
        // Comparación con período anterior
        $previousRange = $this->getPreviousDateRange();
        $previousTotalLeads = $query->clone()->whereBetween('created_at', $previousRange)->count();
        $previousLostLeads = $query->clone()
            ->where('estado', 'perdido')
            ->whereBetween('created_at', $previousRange)
            ->count();
        
        $previousLossRate = $previousTotalLeads > 0 ? ($previousLostLeads / $previousTotalLeads) * 100 : 0;
        $change = $previousLossRate > 0 ? $lossRate - $previousLossRate : 0;
        
        $changeIcon = $change >= 0 ? 'heroicon-m-arrow-trending-up' : 'heroicon-m-arrow-trending-down';
        // Para tasa de pérdida, el aumento es malo (danger) y la disminución es buena (success)
        $changeColor = $change >= 0 ? 'danger' : 'success';
        
        return [
            Stat::make(__('Loss Rate'), number_format($lossRate, 1) . '%')
                ->description(($change >= 0 ? '+' : '') . number_format($change, 1) . '% ' . __('vs previous period'))
                ->descriptionIcon($changeIcon)
                ->color('warning')
                ->chart([25, 20, 18, 15, 12, 10, 8])
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
