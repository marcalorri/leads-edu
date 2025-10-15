<?php

namespace App\Filament\Dashboard\Resources\SalesPhases;

use App\Filament\Dashboard\Clusters\Configuration;

use App\Filament\Dashboard\Resources\SalesPhases\Pages\CreateSalesPhase;
use App\Filament\Dashboard\Resources\SalesPhases\Pages\EditSalesPhase;
use App\Filament\Dashboard\Resources\SalesPhases\Pages\ListSalesPhases;
use App\Filament\Dashboard\Resources\SalesPhases\Schemas\SalesPhaseForm;
use App\Filament\Dashboard\Resources\SalesPhases\Tables\SalesPhasesTable;
use App\Models\SalesPhase;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;

class SalesPhaseResource extends Resource
{
    protected static ?string $model = SalesPhase::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedChartBar;

    protected static bool $isScopedToTenant = false;

    protected static ?string $cluster = Configuration::class;

    public static function getModelLabel(): string
    {
        return __('Sales Phase');
    }

    public static function getPluralModelLabel(): string
    {
        return __('Sales Phases');
    }

    public static function getNavigationGroup(): ?string
    {
        return __('Sales Catalogs');
    }

    public static function getNavigationSort(): ?int
    {
        return 20;
    }

    public static function form(Schema $schema): Schema
    {
        return SalesPhaseForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return SalesPhasesTable::configure($table);
    }

    public static function getRecordUrl(Model $record): ?string
    {
        return static::getUrl('edit', ['record' => $record]);
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
            'edit' => EditSalesPhase::route('/{record}/edit'),
        ];
    }
}
