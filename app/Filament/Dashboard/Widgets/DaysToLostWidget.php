<?php

namespace App\Filament\Dashboard\Widgets;

use App\Models\Lead;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Filament\Widgets\Concerns\InteractsWithPageFilters;
use Carbon\Carbon;

class DaysToLostWidget extends BaseWidget
{
    use InteractsWithPageFilters;
    
    protected static ?int $sort = 8;
    
    protected ?string $pollingInterval = '30s';
    
    protected int | string | array $columnSpan = 1;
    
    protected function getStats(): array
    {

        
        // Obtener filtros de la página
        $startDate = $this->pageFilters['start_date'] ?? now()->subDays(29)->toDateString();
        $endDate = $this->pageFilters['end_date'] ?? now()->toDateString();
        
        $startDate = Carbon::parse($startDate)->startOfDay();
        $endDate = Carbon::parse($endDate)->endOfDay();
        
        // Los global scopes ya manejan el filtrado automáticamente
        $query = Lead::query();
        
        // Obtener leads perdidos en el rango de fechas
        $lostLeads = $query->clone()
            ->where('estado', 'perdido')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->get(['created_at', 'fecha_perdido', 'updated_at']);
        
        if ($lostLeads->isEmpty()) {
            return [
                Stat::make(__('Days to Lost'), 'N/A')
                    ->description(__('No lost leads in this period'))
                    ->descriptionIcon('heroicon-m-clock')
                    ->color('gray')
            ];
        }
        
        // Filtrar solo los que tienen fecha_perdido o usar updated_at como fallback
        $validLeads = $lostLeads->filter(function ($lead) {
            return $lead->fecha_perdido !== null;
        });
        
        // Si no hay leads con fecha_perdido, usar updated_at como aproximación
        if ($validLeads->isEmpty()) {
            $validLeads = $lostLeads;
            $useUpdatedAt = true;
        } else {
            $useUpdatedAt = false;
        }
        
        // Calcular días promedio hasta perdido
        $totalDays = 0;
        $count = 0;
        
        foreach ($validLeads as $lead) {
            $createdAt = Carbon::parse($lead->created_at);
            $lostAt = $useUpdatedAt ? Carbon::parse($lead->updated_at) : Carbon::parse($lead->fecha_perdido);
            $days = $createdAt->diffInDays($lostAt);
            $totalDays += $days;
            $count++;
        }
        
        $averageDays = $count > 0 ? round($totalDays / $count, 1) : 0;
        
        // Comparación con período anterior
        $diffDays = $startDate->diffInDays($endDate) + 1;
        $previousStart = $startDate->copy()->subDays($diffDays);
        $previousEnd = $endDate->copy()->subDays($diffDays);
        
        $previousLostLeads = $query->clone()
            ->where('estado', 'perdido')
            ->whereNotNull('fecha_perdido')
            ->whereBetween('created_at', [$previousStart, $previousEnd])
            ->get(['created_at', 'fecha_perdido']);
        
        $previousAverageDays = 0;
        if ($previousLostLeads->isNotEmpty()) {
            $previousTotalDays = 0;
            $previousCount = 0;
            
            foreach ($previousLostLeads as $lead) {
                $createdAt = Carbon::parse($lead->created_at);
                $lostAt = Carbon::parse($lead->fecha_perdido);
                $days = $createdAt->diffInDays($lostAt);
                $previousTotalDays += $days;
                $previousCount++;
            }
            
            $previousAverageDays = $previousCount > 0 ? round($previousTotalDays / $previousCount, 1) : 0;
        }
        
        $change = $previousAverageDays > 0 ? $averageDays - $previousAverageDays : 0;
        $changeIcon = $change <= 0 ? 'heroicon-m-arrow-trending-down' : 'heroicon-m-arrow-trending-up';
        // Para días hasta perdido, más días es mejor (más tiempo de oportunidad)
        $changeColor = $change >= 0 ? 'success' : 'danger';
        
        $description = $change == 0 ? __('No changes vs previous period') : 
                      ($change > 0 ? '+' : '') . number_format($change, 1) . ' ' . __('days vs previous period');
        
        // Agregar indicador si estamos usando updated_at como fallback
        if ($useUpdatedAt) {
            $description = __('Approx.') . ' ' . $description . ' (' . __('using update date') . ')';
        }
        
        return [
            Stat::make(__('Days to Lost'), number_format($averageDays, 1) . ' ' . __('days'))
                ->description($description)
                ->descriptionIcon($changeIcon)
                ->color('warning')
                ->chart([3, 5, 7, 9, 12, 15, 18]) // Tendencia ascendente ideal (más tiempo antes de perder)
        ];
    }
}
