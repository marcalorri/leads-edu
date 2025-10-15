<?php

namespace App\Filament\Dashboard\Resources\Campuses;

use App\Filament\Dashboard\Clusters\Configuration;
use App\Filament\Dashboard\Resources\Campuses\Pages\CreateCampus;
use App\Filament\Dashboard\Resources\Campuses\Pages\EditCampus;
use App\Filament\Dashboard\Resources\Campuses\Pages\ListCampuses;
use App\Filament\Dashboard\Resources\Campuses\Pages\ViewCampus;
use App\Filament\Dashboard\Resources\Campuses\Schemas\CampusForm;
use App\Filament\Dashboard\Resources\Campuses\Schemas\CampusInfolist;
use App\Filament\Dashboard\Resources\Campuses\Tables\CampusesTable;
use App\Models\Campus;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class CampusResource extends Resource
{
    protected static ?string $model = Campus::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedBuildingOffice2;

    protected static bool $isScopedToTenant = true;

    protected static ?string $cluster = Configuration::class;

    public static function getModelLabel(): string
    {
        return __('Campus');
    }

    public static function getPluralModelLabel(): string
    {
        return __('Campuses');
    }

    public static function getNavigationGroup(): ?string
    {
        return __('Operational Catalogs');
    }

    public static function getNavigationSort(): ?int
    {
        return 10;
    }

    public static function form(Schema $schema): Schema
    {
        return CampusForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return CampusInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return CampusesTable::configure($table);
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
            'index' => ListCampuses::route('/'),
            'create' => CreateCampus::route('/create'),
            'view' => ViewCampus::route('/{record}'),
            'edit' => EditCampus::route('/{record}/edit'),
        ];
    }
}
