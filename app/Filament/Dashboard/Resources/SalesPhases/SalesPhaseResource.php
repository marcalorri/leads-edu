<?php

namespace App\Filament\Dashboard\Resources\SalesPhases;

use App\Filament\Dashboard\Clusters\Configuration;

use App\Filament\Dashboard\Resources\SalesPhases\Pages\CreateSalesPhase;
use App\Filament\Dashboard\Resources\SalesPhases\Pages\EditSalesPhase;
use App\Filament\Dashboard\Resources\SalesPhases\Pages\ListSalesPhases;
use App\Filament\Dashboard\Resources\SalesPhases\Pages\ViewSalesPhase;
use App\Filament\Dashboard\Resources\SalesPhases\Schemas\SalesPhaseForm;
use App\Filament\Dashboard\Resources\SalesPhases\Schemas\SalesPhaseInfolist;
use App\Filament\Dashboard\Resources\SalesPhases\Tables\SalesPhasesTable;
use App\Models\SalesPhase;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class SalesPhaseResource extends Resource
{
    protected static ?string $model = SalesPhase::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedChartBar;

    protected static bool $isScopedToTenant = false;

    protected static ?string $cluster = Configuration::class;

    public static function getModelLabel(): string
    {
        return 'Fase de Venta';
    }

    public static function getPluralModelLabel(): string
    {
        return 'Fases de Venta';
    }

    public static function getNavigationGroup(): ?string
    {
        return 'Catálogos de Ventas';
    }

    public static function getNavigationSort(): ?int
    {
        return 20;
    }

    public static function form(Schema $schema): Schema
    {
        return SalesPhaseForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return SalesPhaseInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return SalesPhasesTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListSalesPhases::route('/'),
            'create' => CreateSalesPhase::route('/create'),
            'view' => ViewSalesPhase::route('/{record}'),
            'edit' => EditSalesPhase::route('/{record}/edit'),
        ];
    }
}
