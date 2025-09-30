<?php

namespace App\Filament\Dashboard\Widgets;

use App\Models\Lead;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Filament\Widgets\Concerns\InteractsWithPageFilters;
use Carbon\Carbon;

class DaysToWonWidget extends BaseWidget
{
    use InteractsWithPageFilters;
    
    protected static ?int $sort = 7;
    
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
        
        // Obtener leads ganados en el rango de fechas
        $wonLeads = $query->clone()
            ->where('estado', 'ganado')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->get(['created_at', 'fecha_ganado', 'updated_at']);
        
        if ($wonLeads->isEmpty()) {
            return [
                Stat::make('Días hasta Ganado', 'N/A')
                    ->description('No hay leads ganados en este período')
                    ->descriptionIcon('heroicon-m-clock')
                    ->color('gray')
            ];
        }
        
        // Filtrar solo los que tienen fecha_ganado o usar updated_at como fallback
        $validLeads = $wonLeads->filter(function ($lead) {
            return $lead->fecha_ganado !== null;
        });
        
        // Si no hay leads con fecha_ganado, usar updated_at como aproximación
        if ($validLeads->isEmpty()) {
            $validLeads = $wonLeads;
            $useUpdatedAt = true;
        } else {
            $useUpdatedAt = false;
        }
        
        // Calcular días promedio hasta ganado
        $totalDays = 0;
        $count = 0;
        
        foreach ($validLeads as $lead) {
            $createdAt = Carbon::parse($lead->created_at);
            $wonAt = $useUpdatedAt ? Carbon::parse($lead->updated_at) : Carbon::parse($lead->fecha_ganado);
            $days = $createdAt->diffInDays($wonAt);
            $totalDays += $days;
            $count++;
        }
        
        $averageDays = $count > 0 ? round($totalDays / $count, 1) : 0;
        
        // Comparación con período anterior
        $diffDays = $startDate->diffInDays($endDate) + 1;
        $previousStart = $startDate->copy()->subDays($diffDays);
        $previousEnd = $endDate->copy()->subDays($diffDays);
        
        $previousWonLeads = $query->clone()
            ->where('estado', 'ganado')
            ->whereNotNull('fecha_ganado')
            ->whereBetween('created_at', [$previousStart, $previousEnd])
            ->get(['created_at', 'fecha_ganado']);
        
        $previousAverageDays = 0;
        if ($previousWonLeads->isNotEmpty()) {
            $previousTotalDays = 0;
            $previousCount = 0;
            
            foreach ($previousWonLeads as $lead) {
                $createdAt = Carbon::parse($lead->created_at);
                $wonAt = Carbon::parse($lead->fecha_ganado);
                $days = $createdAt->diffInDays($wonAt);
                $previousTotalDays += $days;
                $previousCount++;
            }
            
            $previousAverageDays = $previousCount > 0 ? round($previousTotalDays / $previousCount, 1) : 0;
        }
        
        $change = $previousAverageDays > 0 ? $averageDays - $previousAverageDays : 0;
        $changeIcon = $change <= 0 ? 'heroicon-m-arrow-trending-down' : 'heroicon-m-arrow-trending-up';
        // Para días hasta ganado, menos días es mejor (success), más días es peor (danger)
        $changeColor = $change <= 0 ? 'success' : 'danger';
        
        $description = $change == 0 ? 'Sin cambios vs período anterior' : 
                      ($change > 0 ? '+' : '') . number_format($change, 1) . ' días vs período anterior';
        
        // Agregar indicador si estamos usando updated_at como fallback
        if ($useUpdatedAt) {
            $description = 'Aprox. ' . $description . ' (usando fecha de actualización)';
        }
        
        return [
            Stat::make('Días hasta Ganado', number_format($averageDays, 1) . ' días')
                ->description($description)
                ->descriptionIcon($changeIcon)
                ->color('success')
                ->chart([15, 12, 10, 8, 6, 5, 4]) // Tendencia descendente ideal
        ];
    }
}
