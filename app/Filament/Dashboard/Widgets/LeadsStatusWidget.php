<?php

namespace App\Filament\Dashboard\Widgets;

use App\Models\Lead;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Carbon\Carbon;

class LeadsStatusWidget extends BaseWidget
{
    protected static ?int $sort = 2;
    
    protected ?string $pollingInterval = '30s';
    
    protected function getStats(): array
    {

        
        // Los global scopes ya manejan el filtrado automáticamente
        $query = Lead::query();
        
        // Métricas por estado en el mes actual
        $thisMonth = [
            Carbon::now()->startOfMonth(),
            Carbon::now()->endOfMonth()
        ];
        
        $abiertos = $query->clone()->where('estado', 'abierto')->count();
        $ganados = $query->clone()->where('estado', 'ganado')
            ->whereBetween('created_at', $thisMonth)->count();
        $perdidos = $query->clone()->where('estado', 'perdido')
            ->whereBetween('created_at', $thisMonth)->count();
        
        // Comparación con mes anterior
        $lastMonth = [
            Carbon::now()->subMonth()->startOfMonth(),
            Carbon::now()->subMonth()->endOfMonth()
        ];
        
        $ganadosLastMonth = $query->clone()->where('estado', 'ganado')
            ->whereBetween('created_at', $lastMonth)->count();
        $perdidosLastMonth = $query->clone()->where('estado', 'perdido')
            ->whereBetween('created_at', $lastMonth)->count();
        
        // Calcular cambios porcentuales
        $ganadosChange = $ganadosLastMonth > 0 ? (($ganados - $ganadosLastMonth) / $ganadosLastMonth) * 100 : 0;
        $perdidosChange = $perdidosLastMonth > 0 ? (($perdidos - $perdidosLastMonth) / $perdidosLastMonth) * 100 : 0;
        
        return [
            Stat::make('Leads Abiertos', $abiertos)
                ->description('Leads en proceso')
                ->descriptionIcon('heroicon-m-clock')
                ->color('warning'),
                
            Stat::make('Leads Ganados', $ganados)
                ->description(($ganadosChange >= 0 ? '+' : '') . number_format($ganadosChange, 1) . '% vs mes anterior')
                ->descriptionIcon($ganadosChange >= 0 ? 'heroicon-m-arrow-trending-up' : 'heroicon-m-arrow-trending-down')
                ->color('success'),
                
            Stat::make('Leads Perdidos', $perdidos)
                ->description(($perdidosChange >= 0 ? '+' : '') . number_format($perdidosChange, 1) . '% vs mes anterior')
                ->descriptionIcon($perdidosChange >= 0 ? 'heroicon-m-arrow-trending-up' : 'heroicon-m-arrow-trending-down')
                ->color('danger'),
        ];
    }
}
