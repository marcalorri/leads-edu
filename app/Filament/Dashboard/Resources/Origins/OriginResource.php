<?php

namespace App\Filament\Dashboard\Resources\Origins;

use App\Filament\Dashboard\Clusters\Configuration;

use App\Filament\Dashboard\Resources\Origins\Pages\CreateOrigin;
use App\Filament\Dashboard\Resources\Origins\Pages\EditOrigin;
use App\Filament\Dashboard\Resources\Origins\Pages\ListOrigins;
use App\Filament\Dashboard\Resources\Origins\Pages\ViewOrigin;
use App\Filament\Dashboard\Resources\Origins\Schemas\OriginForm;
use App\Filament\Dashboard\Resources\Origins\Schemas\OriginInfolist;
use App\Filament\Dashboard\Resources\Origins\Tables\OriginsTable;
use App\Models\Origin;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class OriginResource extends Resource
{
    protected static ?string $model = Origin::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedGlobeAlt;

    protected static bool $isScopedToTenant = false;

    protected static ?string $cluster = Configuration::class;

    public static function getModelLabel(): string
    {
        return __('Origin');
    }

    public static function getPluralModelLabel(): string
    {
        return __('Origins');
    }

    public static function getNavigationGroup(): ?string
    {
        return __('Sales Catalogs');
    }

    public static function getNavigationSort(): ?int
    {
        return 22;
    }

    public static function form(Schema $schema): Schema
    {
        return OriginForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return OriginInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return OriginsTable::configure($table);
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
            'index' => ListOrigins::route('/'),
            'create' => CreateOrigin::route('/create'),
            'view' => ViewOrigin::route('/{record}'),
            'edit' => EditOrigin::route('/{record}/edit'),
        ];
    }
}
