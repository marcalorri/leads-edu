<?php

namespace App\Filament\Dashboard\Pages;

use App\Filament\Dashboard\Widgets\NewLeadsStatWidget;
use App\Filament\Dashboard\Widgets\OpenLeadsStatWidget;
use App\Filament\Dashboard\Widgets\WonLeadsStatWidget;
use App\Filament\Dashboard\Widgets\LostLeadsStatWidget;
use App\Filament\Dashboard\Widgets\ConversionRateStatWidget;
use App\Filament\Dashboard\Widgets\LossRateStatWidget;
use App\Filament\Dashboard\Widgets\DaysToWonWidget;
use App\Filament\Dashboard\Widgets\DaysToLostWidget;
use App\Filament\Dashboard\Widgets\DailyLeadsChart;
use App\Filament\Dashboard\Widgets\CoursesBreakdownWidget;
use App\Filament\Dashboard\Widgets\LeadEventsCalendarWidget;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Pages\Dashboard\Concerns\HasFiltersForm;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Pages\Dashboard as BaseDashboard;

class Dashboard extends BaseDashboard
{
    use HasFiltersForm;
    
    protected static ?string $title = 'Dashboard';
    
    public function filtersForm(Schema $schema): Schema
    {
        return $schema->components([
            Section::make([
                DatePicker::make('start_date')
                    ->default(now()->subDays(29)->toDateString())
                    ->afterStateHydrated(function (DatePicker $component, ?string $state) {
                        if (! $state) {
                            $component->state(now()->subDays(29)->toDateString());
                        }
                    })
                    ->label(__('Start date')),
                DatePicker::make('end_date')
                    ->default(now()->toDateString())
                    ->afterStateHydrated(function (DatePicker $component, ?string $state) {
                        if (! $state) {
                            $component->state(now()->toDateString());
                        }
                    })
                    ->label(__('End date')),
                Select::make('period')
                    ->label(__('Period'))
                    ->options([
                        'today' => __('Today'),
                        'yesterday' => __('Yesterday'),
                        'last_7_days' => __('Last 7 days'),
                        'last_30_days' => __('Last 30 days'),
                        'this_month' => __('This month'),
                        'last_month' => __('Last month'),
                        'custom' => __('Custom'),
                    ])
                    ->default('last_30_days')
                    ->live()
                    ->afterStateUpdated(function ($state, $set) {
                        if ($state !== 'custom') {
                            [$start, $end] = match($state) {
                                'today' => [now(), now()],
                                'yesterday' => [now()->subDay(), now()->subDay()],
                                'last_7_days' => [now()->subDays(6), now()],
                                'last_30_days' => [now()->subDays(29), now()],
                                'this_month' => [now()->startOfMonth(), now()->endOfMonth()],
                                'last_month' => [now()->subMonth()->startOfMonth(), now()->subMonth()->endOfMonth()],
                                default => [now()->subDays(29), now()],
                            };
                            
                            $set('start_date', $start->toDateString());
                            $set('end_date', $end->toDateString());
                        }
                    }),
            ])->columnSpanFull()
                ->columns(3),
        ]);
    }
    
    public function getWidgets(): array
    {
        return [
            NewLeadsStatWidget::class,
            OpenLeadsStatWidget::class,
            WonLeadsStatWidget::class,
            LostLeadsStatWidget::class,
            ConversionRateStatWidget::class,
            LossRateStatWidget::class,
            DaysToWonWidget::class,
            DaysToLostWidget::class,
            DailyLeadsChart::class,
            LeadEventsCalendarWidget::class,
            CoursesBreakdownWidget::class,
        ];
    }
    
    public function getColumns(): int | array
    {
        return [
            'sm' => 1,
            'md' => 2,
            'lg' => 4,
            'xl' => 4,
        ];
    }
}
