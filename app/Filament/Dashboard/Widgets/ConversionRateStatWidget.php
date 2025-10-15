<?php

namespace App\Filament\Dashboard\Widgets;

use App\Models\Lead;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Carbon\Carbon;

class ConversionRateStatWidget extends BaseWidget
{
    protected static ?int $sort = 5;
    
    protected ?string $pollingInterval = '30s';
    
    protected int | string | array $columnSpan = 1;
    
    public ?string $filter = 'last_30_days';
    
    protected function getStats(): array
    {

        $dateRange = $this->getDateRange();
        
        // Los global scopes ya manejan el filtrado automáticamente
        $query = Lead::query();
        
        // Calcular tasa de conversión en el rango de fechas
        $totalLeads = $query->clone()->whereBetween('created_at', $dateRange)->count();
        $wonLeads = $query->clone()
            ->where('estado', 'ganado')
            ->whereBetween('created_at', $dateRange)
            ->count();
        
        $conversionRate = $totalLeads > 0 ? ($wonLeads / $totalLeads) * 100 : 0;
        
        // Comparación con período anterior
        $previousRange = $this->getPreviousDateRange();
        $previousTotalLeads = $query->clone()->whereBetween('created_at', $previousRange)->count();
        $previousWonLeads = $query->clone()
            ->where('estado', 'ganado')
            ->whereBetween('created_at', $previousRange)
            ->count();
        
        $previousConversionRate = $previousTotalLeads > 0 ? ($previousWonLeads / $previousTotalLeads) * 100 : 0;
        $change = $previousConversionRate > 0 ? $conversionRate - $previousConversionRate : 0;
        
        $changeIcon = $change >= 0 ? 'heroicon-m-arrow-trending-up' : 'heroicon-m-arrow-trending-down';
        $changeColor = $change >= 0 ? 'success' : 'danger';
        
        return [
            Stat::make(__('Conversion Rate'), number_format($conversionRate, 1) . '%')
                ->description(($change >= 0 ? '+' : '') . number_format($change, 1) . '% ' . __('vs previous period'))
                ->descriptionIcon($changeIcon)
                ->color('primary')
                ->chart([10, 15, 12, 18, 20, 25, 22])
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
