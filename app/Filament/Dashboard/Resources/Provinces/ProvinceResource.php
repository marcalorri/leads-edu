<?php

namespace App\Filament\Dashboard\Resources\Provinces;

use App\Filament\Dashboard\Clusters\Configuration;

use App\Filament\Dashboard\Resources\Provinces\Pages\CreateProvince;
use App\Filament\Dashboard\Resources\Provinces\Pages\EditProvince;
use App\Filament\Dashboard\Resources\Provinces\Pages\ListProvinces;
use App\Filament\Dashboard\Resources\Provinces\Schemas\ProvinceForm;
use App\Filament\Dashboard\Resources\Provinces\Tables\ProvincesTable;
use App\Models\Province;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class ProvinceResource extends Resource
{
    protected static ?string $model = Province::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedMapPin;

    protected static bool $isScopedToTenant = false;

    protected static ?string $cluster = Configuration::class;

    public static function getModelLabel(): string
    {
        return 'Provincia';
    }

    public static function getPluralModelLabel(): string
    {
        return 'Provincias';
    }

    public static function getNavigationGroup(): ?string
    {
        return 'Catálogos Operativos';
    }

    public static function getNavigationSort(): ?int
    {
        return 12;
    }

    public static function form(Schema $schema): Schema
    {
        return ProvinceForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return ProvincesTable::configure($table);
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
            'index' => ListProvinces::route('/'),
            'create' => CreateProvince::route('/create'),
            'edit' => EditProvince::route('/{record}/edit'),
        ];
    }
}
