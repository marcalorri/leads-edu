<?php

namespace App\Filament\Dashboard\Resources\NullReasons\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class NullReasonForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Información del Motivo Nulo')
                    ->schema([
                        TextInput::make('nombre')
                            ->required()
                            ->maxLength(100)
                            ->label('Nombre'),
                        Textarea::make('descripcion')
                            ->maxLength(500)
                            ->label('Descripción'),
                        Toggle::make('activo')
                            ->default(true)
                            ->label('Activo'),
                    ])->columns(2),
            ]);
    }
}
