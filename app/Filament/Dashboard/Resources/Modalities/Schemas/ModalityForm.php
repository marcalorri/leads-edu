<?php

namespace App\Filament\Dashboard\Resources\Modalities\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class ModalityForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make()->schema([
                    TextInput::make('codigo')
                        ->required()
                        ->maxLength(10)
                        ->unique(ignoreRecord: true)
                        ->label('Código'),
                    TextInput::make('nombre')
                        ->required()
                        ->maxLength(50)
                        ->label('Nombre'),
                    Textarea::make('descripcion')
                        ->columnSpanFull()
                        ->label('Descripción'),
                    Toggle::make('requiere_sede')
                        ->default(true)
                        ->label('Requiere Sede'),
                    Toggle::make('activo')
                        ->default(true)
                        ->label('Activo'),
                ])
            ]);
    }
}
