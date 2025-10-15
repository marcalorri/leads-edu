<?php

namespace App\Filament\Dashboard\Widgets;

use App\Models\Lead;
use Filament\Widgets\ChartWidget;
use Filament\Widgets\Concerns\InteractsWithPageFilters;
use Filament\Support\RawJs;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class CoursesBreakdownWidget extends ChartWidget
{
    use InteractsWithPageFilters;
    
    protected static ?int $sort = 10;
    
    protected ?string $heading = null;
    
    public function getHeading(): ?string
    {
        return __('Lead Distribution by Course');
    }
    
    protected ?string $pollingInterval = '30s';
    
    protected int | string | array $columnSpan = 2; // Mitad del ancho
    
    protected function getData(): array
    {
        $user = auth()->user();
        $tenantId = filament()->getTenant()->id;
        
        // Obtener filtros de la página
        $startDate = $this->pageFilters['start_date'] ?? now()->subDays(29)->toDateString();
        $endDate = $this->pageFilters['end_date'] ?? now()->toDateString();
        
        $startDate = Carbon::parse($startDate)->startOfDay();
        $endDate = Carbon::parse($endDate)->endOfDay();
        
        // Query base para obtener datos por curso y estado
        $baseQuery = DB::table('leads')
            ->select(
                'courses.codigo_curso',
                'courses.titulacion',
                'leads.estado',
                DB::raw('COUNT(leads.id) as total')
            )
            ->join('courses', function($join) use ($tenantId) {
                $join->on('leads.curso_id', '=', 'courses.id')
                     ->where('courses.tenant_id', '=', $tenantId);
            })
            ->where('leads.tenant_id', '=', $tenantId)
            ->whereNull('leads.deleted_at')
            ->whereBetween('leads.created_at', [$startDate, $endDate])
            ->groupBy('courses.id', 'courses.codigo_curso', 'courses.titulacion', 'leads.estado');
        
        // Filtrar por usuario si no puede ver todos los leads
        if (!$user->canViewAllLeads()) {
            $baseQuery->where('leads.asesor_id', $user->id);
        }
        
        $data = $baseQuery->get();
        
        // Organizar datos por curso
        $courseData = [];
        foreach ($data as $row) {
            $courseCode = $row->codigo_curso;
            if (!isset($courseData[$courseCode])) {
                $courseData[$courseCode] = [
                    'codigo' => $courseCode,
                    'titulacion' => $row->titulacion,
                    'abierto' => 0,
                    'ganado' => 0,
                    'perdido' => 0,
                    'total' => 0
                ];
            }
            
            $courseData[$courseCode][$row->estado] = (int) $row->total;
            $courseData[$courseCode]['total'] += (int) $row->total;
        }
        
        // Ordenar por total descendente y tomar top 10
        uasort($courseData, function($a, $b) {
            return $b['total'] <=> $a['total'];
        });
        
        $topCourses = array_slice($courseData, 0, 10, true);
        
        // Preparar datos para Chart.js
        $labels = array_column($topCourses, 'codigo');
        $abiertosData = array_column($topCourses, 'abierto');
        $ganadosData = array_column($topCourses, 'ganado');
        $perdidosData = array_column($topCourses, 'perdido');
        
        return [
            'datasets' => [
                [
                    'label' => __('Open'),
                    'data' => $abiertosData,
                    'backgroundColor' => '#F59E0B', // Amber/Warning
                    'borderColor' => '#D97706',
                    'borderWidth' => 1,
                ],
                [
                    'label' => __('Won'),
                    'data' => $ganadosData,
                    'backgroundColor' => '#10B981', // Green/Success
                    'borderColor' => '#059669',
                    'borderWidth' => 1,
                ],
                [
                    'label' => __('Lost'),
                    'data' => $perdidosData,
                    'backgroundColor' => '#EF4444', // Red/Danger
                    'borderColor' => '#DC2626',
                    'borderWidth' => 1,
                ],
            ],
            'labels' => $labels,
        ];
    }

    protected function getType(): string
    {
        return 'bar';
    }
    
    protected function getOptions(): RawJs
    {
        return RawJs::make(<<<JS
        {
            plugins: {
                legend: {
                    display: true,
                    position: 'top',
                    labels: {
                        usePointStyle: true,
                        padding: 20,
                    },
                },
                tooltip: {
                    mode: 'index',
                    intersect: false,
                    callbacks: {
                        title: function(context) {
                            return 'Curso: ' + context[0].label;
                        },
                        label: function(context) {
                            return context.dataset.label + ': ' + context.parsed.y;
                        }
                    }
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    stacked: true,
                    ticks: {
                        stepSize: 1,
                    },
                    title: {
                        display: true,
                        text: 'Número de Leads',
                    },
                },
                x: {
                    stacked: true,
                    ticks: {
                        maxRotation: 45,
                        minRotation: 45,
                    },
                    title: {
                        display: true,
                        text: 'Códigos de Curso',
                    },
                },
            },
            responsive: true,
            maintainAspectRatio: false,
            interaction: {
                mode: 'index',
                intersect: false,
            },
        }
        JS);
    }
    
    public function getHeight(): ?string
    {
        return '400px'; // Misma altura que el calendario
    }
}
