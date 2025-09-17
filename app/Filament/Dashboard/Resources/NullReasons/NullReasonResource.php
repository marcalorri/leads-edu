<?php

namespace App\Filament\Dashboard\Resources\NullReasons;

use App\Filament\Dashboard\Resources\NullReasons\Pages\CreateNullReason;
use App\Filament\Dashboard\Resources\NullReasons\Pages\EditNullReason;
use App\Filament\Dashboard\Resources\NullReasons\Pages\ListNullReasons;
use App\Filament\Dashboard\Resources\NullReasons\Pages\ViewNullReason;
use App\Filament\Dashboard\Resources\NullReasons\Schemas\NullReasonForm;
use App\Filament\Dashboard\Resources\NullReasons\Schemas\NullReasonInfolist;
use App\Filament\Dashboard\Resources\NullReasons\Tables\NullReasonsTable;
use App\Models\NullReason;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class NullReasonResource extends Resource
{
    protected static ?string $model = NullReason::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedXCircle;

    protected static bool $isScopedToTenant = true;

    public static function getModelLabel(): string
    {
        return 'Motivo Nulo';
    }

    public static function getPluralModelLabel(): string
    {
        return 'Motivos Nulos';
    }

    public static function getNavigationGroup(): ?string
    {
        return 'Catálogos de Ventas';
    }

    public static function getNavigationSort(): ?int
    {
        return 21;
    }

    public static function form(Schema $schema): Schema
    {
        return NullReasonForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return NullReasonInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return NullReasonsTable::configure($table);
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
            'index' => ListNullReasons::route('/'),
            'create' => CreateNullReason::route('/create'),
            'view' => ViewNullReason::route('/{record}'),
            'edit' => EditNullReason::route('/{record}/edit'),
        ];
    }
}
