<?php

namespace App\Filament\Dashboard\Resources\BusinessUnits;

use App\Filament\Dashboard\Clusters\Configuration;
use App\Filament\Dashboard\Resources\BusinessUnits\Pages\CreateBusinessUnit;
use App\Filament\Dashboard\Resources\BusinessUnits\Pages\EditBusinessUnit;
use App\Filament\Dashboard\Resources\BusinessUnits\Pages\ListBusinessUnits;
use App\Filament\Dashboard\Resources\BusinessUnits\Pages\ViewBusinessUnit;
use App\Filament\Dashboard\Resources\BusinessUnits\Schemas\BusinessUnitForm;
use App\Filament\Dashboard\Resources\BusinessUnits\Schemas\BusinessUnitInfolist;
use App\Filament\Dashboard\Resources\BusinessUnits\Tables\BusinessUnitsTable;
use App\Models\BusinessUnit;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class BusinessUnitResource extends Resource
{
    protected static ?string $model = BusinessUnit::class;

    protected static ?string $modelLabel = 'Unidad de Negocio';
    
    protected static ?string $pluralModelLabel = 'Unidades de Negocio';

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedBuildingOffice2;

    protected static bool $isScopedToTenant = false;

    protected static ?string $cluster = Configuration::class;

    public static function getNavigationGroup(): ?string
    {
        return 'Catálogos Académicos';
    }

    public static function getNavigationSort(): ?int
    {
        return 2;
    }

    public static function form(Schema $schema): Schema
    {
        return BusinessUnitForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return BusinessUnitInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return BusinessUnitsTable::configure($table);
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
            'index' => ListBusinessUnits::route('/'),
            'create' => CreateBusinessUnit::route('/create'),
            'view' => ViewBusinessUnit::route('/{record}'),
            'edit' => EditBusinessUnit::route('/{record}/edit'),
        ];
    }
}
