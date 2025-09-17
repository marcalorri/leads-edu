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
                            ->label('Orden')
                            ->helperText('Orden de visualización (opcional)'),
                        ColorPicker::make('color')
                            ->required()
                            ->label('Color')
                            ->helperText('Color para identificar la fase'),
                        Toggle::make('activo')
                            ->default(true)
                            ->label('Activo'),
                    ])->columns(2),
            ]);
    }
}
