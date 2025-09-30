<?php

namespace App\Filament\Dashboard\Resources\Durations;

use App\Filament\Dashboard\Clusters\Configuration;
use App\Filament\Dashboard\Resources\Durations\Pages\CreateDuration;
use App\Filament\Dashboard\Resources\Durations\Pages\EditDuration;
use App\Filament\Dashboard\Resources\Durations\Pages\ListDurations;
use App\Filament\Dashboard\Resources\Durations\Pages\ViewDuration;
use App\Filament\Dashboard\Resources\Durations\Schemas\DurationForm;
use App\Filament\Dashboard\Resources\Durations\Schemas\DurationInfolist;
use App\Filament\Dashboard\Resources\Durations\Tables\DurationsTable;
use App\Models\Duration;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class DurationResource extends Resource
{
    protected static ?string $model = Duration::class;

    protected static ?string $modelLabel = 'Duración';
    
    protected static ?string $pluralModelLabel = 'Duraciones';

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedClock;

    protected static bool $isScopedToTenant = false;

    protected static ?string $cluster = Configuration::class;

    public static function getNavigationGroup(): ?string
    {
        return 'Catálogos Académicos';
    }

    public static function getNavigationSort(): ?int
    {
        return 3;
    }

    public static function form(Schema $schema): Schema
    {
        return DurationForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return DurationInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return DurationsTable::configure($table);
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
            'index' => ListDurations::route('/'),
            'create' => CreateDuration::route('/create'),
            'view' => ViewDuration::route('/{record}'),
            'edit' => EditDuration::route('/{record}/edit'),
        ];
    }
}
