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
                Section::make('Información de la Fase')
                    ->schema([
                        TextInput::make('nombre')
                            ->required()
                            ->maxLength(100)
                            ->label('Nombre'),
                        Textarea::make('descripcion')
                            ->maxLength(500)
                            ->label('Descripción'),
                        TextInput::make('orden')
                            ->numeric()
                            ->default(0)
                            ->required()
                            ->label('Orden')
                            ->helperText('Orden de visualización'),
                        ColorPicker::make('color')
                            ->label('Color')
                            ->default('#3b82f6')
                            ->helperText('Color para identificar la fase'),
                        Toggle::make('activo')
                            ->default(true)
                            ->label('Activo'),
                    ])->columns(2),
            ]);
    }
}
