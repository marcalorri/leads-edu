<?php

namespace App\Filament\Dashboard\Resources\Modalities;

use App\Filament\Dashboard\Clusters\Configuration;

use App\Filament\Dashboard\Resources\Modalities\Pages\CreateModality;
use App\Filament\Dashboard\Resources\Modalities\Pages\EditModality;
use App\Filament\Dashboard\Resources\Modalities\Pages\ListModalities;
use App\Filament\Dashboard\Resources\Modalities\Pages\ViewModality;
use App\Filament\Dashboard\Resources\Modalities\Schemas\ModalityForm;
use App\Filament\Dashboard\Resources\Modalities\Schemas\ModalityInfolist;
use App\Filament\Dashboard\Resources\Modalities\Tables\ModalitiesTable;
use App\Models\Modality;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class ModalityResource extends Resource
{
    protected static ?string $model = Modality::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedComputerDesktop;

    protected static bool $isScopedToTenant = false;

    protected static ?string $cluster = Configuration::class;

    public static function getModelLabel(): string
    {
        return 'Modalidad';
    }

    public static function getPluralModelLabel(): string
    {
        return 'Modalidades';
    }

    public static function getNavigationGroup(): ?string
    {
        return 'Catálogos Operativos';
    }

    public static function getNavigationSort(): ?int
    {
        return 11;
    }

    public static function form(Schema $schema): Schema
    {
        return ModalityForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return ModalityInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return ModalitiesTable::configure($table);
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
            'index' => ListModalities::route('/'),
            'create' => CreateModality::route('/create'),
            'view' => ViewModality::route('/{record}'),
            'edit' => EditModality::route('/{record}/edit'),
        ];
    }
}
