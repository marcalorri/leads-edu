<?php

namespace App\Filament\Dashboard\Resources\Areas;

use App\Filament\Dashboard\Clusters\Configuration;
use App\Filament\Dashboard\Resources\Areas\Pages\CreateArea;
use App\Filament\Dashboard\Resources\Areas\Pages\EditArea;
use App\Filament\Dashboard\Resources\Areas\Pages\ListAreas;
use App\Filament\Dashboard\Resources\Areas\Schemas\AreaForm;
use App\Filament\Dashboard\Resources\Areas\Tables\AreasTable;
use App\Models\Area;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;

class AreaResource extends Resource
{
    protected static ?string $model = Area::class;

    protected static ?string $modelLabel = 'Área';
    
    protected static ?string $pluralModelLabel = 'Áreas';

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;
    
    protected static bool $isScopedToTenant = false;

    protected static ?string $cluster = Configuration::class;

    public static function getNavigationGroup(): ?string
    {
        return 'Catálogos Académicos';
    }

    public static function getNavigationSort(): ?int
    {
        return 1;
    }

    public static function form(Schema $schema): Schema
    {
        return AreaForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return AreasTable::configure($table);
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
            'index' => ListAreas::route('/'),
            'create' => CreateArea::route('/create'),
            'edit' => EditArea::route('/{record}/edit'),
        ];
    }
}
