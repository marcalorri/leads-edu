<?php

namespace App\Filament\Dashboard\Resources\SalesPhases\Schemas;

use Filament\Forms\Components\ColorPicker;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class SalesPhaseForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make(__('Phase Information'))
                    ->schema([
                        TextInput::make('nombre')
                            ->required()
                            ->maxLength(100)
                            ->label(__('Name')),
                        Textarea::make('descripcion')
                            ->maxLength(500)
                            ->label(__('Description')),
                        TextInput::make('orden')
                            ->numeric()
                            ->default(0)
                            ->required()
                            ->label(__('Order'))
                            ->helperText(__('Display order')),
                        ColorPicker::make('color')
                            ->label(__('Color'))
                            ->default('#3b82f6')
                            ->helperText(__('Color to identify the phase')),
                        Toggle::make('activo')
                            ->default(true)
                            ->label(__('Active')),
                    ])->columns(2),
            ]);
    }
}
