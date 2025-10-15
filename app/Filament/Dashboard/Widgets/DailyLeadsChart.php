<?php

namespace App\Filament\Dashboard\Widgets;

use App\Models\Lead;
use Filament\Widgets\ChartWidget;
use Filament\Widgets\Concerns\InteractsWithPageFilters;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class DailyLeadsChart extends ChartWidget
{
    use InteractsWithPageFilters;
    
    protected static ?int $sort = 9;
    
    protected ?string $heading = null;
    
    public function getHeading(): ?string
    {
        return __('Daily New Leads');
    }
    
    protected ?string $pollingInterval = '30s';
    
    protected int | string | array $columnSpan = 'full';
    
    protected function getData(): array
    {
        // Obtener filtros de la página
        $startDate = $this->pageFilters['start_date'] ?? now()->subDays(29)->toDateString();
        $endDate = $this->pageFilters['end_date'] ?? now()->toDateString();
        
        $startDate = Carbon::parse($startDate)->startOfDay();
        $endDate = Carbon::parse($endDate)->endOfDay();
        
        // Los global scopes ya manejan el filtrado automáticamente
        $query = Lead::query()
            ->select(DB::raw('DATE(created_at) as date'), DB::raw('COUNT(*) as count'))
            ->whereBetween('created_at', [$startDate, $endDate])
            ->groupBy('date')
            ->orderBy('date');
        
        $data = $query->get()->pluck('count', 'date')->toArray();
        
        // Crear array con todos los días (incluso los que no tienen datos)
        $labels = [];
        $values = [];
        
        for ($date = $startDate->copy(); $date <= $endDate; $date->addDay()) {
            $dateString = $date->format('Y-m-d');
            $labels[] = $date->format('d/m');
            $values[] = $data[$dateString] ?? 0;
        }
        
        return [
            'datasets' => [
                [
                    'label' => __('New Leads'),
                    'data' => $values,
                    'borderColor' => '#3B82F6',
                    'backgroundColor' => 'rgba(59, 130, 246, 0.1)',
                    'fill' => true,
                    'tension' => 0.4,
                ],
            ],
            'labels' => $labels,
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }
    
    protected function getOptions(): array
    {
        return [
            'plugins' => [
                'legend' => [
                    'display' => false,
                ],
            ],
            'scales' => [
                'y' => [
                    'beginAtZero' => true,
                    'ticks' => [
                        'stepSize' => 1,
                    ],
                ],
            ],
            'maintainAspectRatio' => false,
            'responsive' => true,
        ];
    }
    
    public function getHeight(): ?string
    {
        return '300px';
    }
}
