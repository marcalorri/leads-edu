<?php

namespace App\Filament\Dashboard\Widgets;

use App\Models\Lead;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Carbon\Carbon;

class ConversionRateWidget extends BaseWidget
{
    protected static ?int $sort = 3;
    
    protected ?string $pollingInterval = '30s';
    
    protected function getStats(): array
    {

        
        // Los global scopes ya manejan el filtrado automáticamente
        $query = Lead::query();
        
        // Métricas del mes actual
        $thisMonth = [
            Carbon::now()->startOfMonth(),
            Carbon::now()->endOfMonth()
        ];
        
        $totalLeads = $query->clone()->whereBetween('created_at', $thisMonth)->count();
        $ganadosLeads = $query->clone()->where('estado', 'ganado')
            ->whereBetween('created_at', $thisMonth)->count();
        $perdidosLeads = $query->clone()->where('estado', 'perdido')
            ->whereBetween('created_at', $thisMonth)->count();
        
        // Calcular tasas
        $conversionRate = $totalLeads > 0 ? ($ganadosLeads / $totalLeads) * 100 : 0;
        $lossRate = $totalLeads > 0 ? ($perdidosLeads / $totalLeads) * 100 : 0;
        $activeRate = $totalLeads > 0 ? (($totalLeads - $ganadosLeads - $perdidosLeads) / $totalLeads) * 100 : 0;
        
        // Comparación con mes anterior
        $lastMonth = [
            Carbon::now()->subMonth()->startOfMonth(),
            Carbon::now()->subMonth()->endOfMonth()
        ];
        
        $totalLeadsLastMonth = $query->clone()->whereBetween('created_at', $lastMonth)->count();
        $ganadosLeadsLastMonth = $query->clone()->where('estado', 'ganado')
            ->whereBetween('created_at', $lastMonth)->count();
        
        $lastMonthConversionRate = $totalLeadsLastMonth > 0 ? ($ganadosLeadsLastMonth / $totalLeadsLastMonth) * 100 : 0;
        $conversionChange = $lastMonthConversionRate > 0 ? $conversionRate - $lastMonthConversionRate : 0;
        
        return [
            Stat::make('Tasa de Conversión', number_format($conversionRate, 1) . '%')
                ->description(($conversionChange >= 0 ? '+' : '') . number_format($conversionChange, 1) . '% vs mes anterior')
                ->descriptionIcon($conversionChange >= 0 ? 'heroicon-m-arrow-trending-up' : 'heroicon-m-arrow-trending-down')
                ->color($conversionChange >= 0 ? 'success' : 'danger'),
                
            Stat::make('Tasa de Pérdida', number_format($lossRate, 1) . '%')
                ->description('Leads perdidos este mes')
                ->descriptionIcon('heroicon-m-x-circle')
                ->color('danger'),
                
            Stat::make('Leads Activos', number_format($activeRate, 1) . '%')
                ->description('En proceso de conversión')
                ->descriptionIcon('heroicon-m-clock')
                ->color('warning'),
        ];
    }
}
