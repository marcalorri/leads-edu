<?php

namespace App\Filament\Dashboard\Widgets;

use App\Models\Lead;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Carbon\Carbon;

class NewLeadsWidget extends BaseWidget
{
    protected static ?int $sort = 1;
    
    protected ?string $pollingInterval = '30s';
    
    protected function getStats(): array
    {

        
        // Los global scopes ya manejan el filtrado automáticamente
        $query = Lead::query();
        
        // Métricas para diferentes períodos
        $today = $query->clone()->whereDate('created_at', Carbon::today())->count();
        $thisWeek = $query->clone()->whereBetween('created_at', [
            Carbon::now()->startOfWeek(),
            Carbon::now()->endOfWeek()
        ])->count();
        $thisMonth = $query->clone()->whereMonth('created_at', Carbon::now()->month)
            ->whereYear('created_at', Carbon::now()->year)->count();
        
        // Comparación con período anterior
        $lastWeek = $query->clone()->whereBetween('created_at', [
            Carbon::now()->subWeek()->startOfWeek(),
            Carbon::now()->subWeek()->endOfWeek()
        ])->count();
        
        $weeklyChange = $lastWeek > 0 ? (($thisWeek - $lastWeek) / $lastWeek) * 100 : 0;
        $changeIcon = $weeklyChange >= 0 ? 'heroicon-m-arrow-trending-up' : 'heroicon-m-arrow-trending-down';
        $changeColor = $weeklyChange >= 0 ? 'success' : 'danger';
        
        return [
            Stat::make('Nuevos Leads Hoy', $today)
                ->description('Leads creados hoy')
                ->descriptionIcon('heroicon-m-calendar-days')
                ->color('info'),
                
            Stat::make('Nuevos Leads Esta Semana', $thisWeek)
                ->description(($weeklyChange >= 0 ? '+' : '') . number_format($weeklyChange, 1) . '% vs semana anterior')
                ->descriptionIcon($changeIcon)
                ->color($changeColor),
                
            Stat::make('Nuevos Leads Este Mes', $thisMonth)
                ->description('Total del mes actual')
                ->descriptionIcon('heroicon-m-chart-bar')
                ->color('primary'),
        ];
    }
}
