<?php

namespace App\Filament\Dashboard\Resources\Durations\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\Select;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class DurationForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make()->schema([
                    TextInput::make('nombre')
                        ->required()
                        ->maxLength(50)
                        ->label('Nombre'),
                    Textarea::make('descripcion')
                        ->columnSpanFull()
                        ->label('Descripción'),
                    Select::make('tipo')
                        ->options([
                            'horas' => 'Horas',
                            'dias' => 'Días',
                            'semanas' => 'Semanas',
                            'meses' => 'Meses',
                            'años' => 'Años',
                        ])
                        ->required()
                        ->label('Tipo'),
                    TextInput::make('valor_numerico')
                        ->numeric()
                        ->label('Valor Numérico'),
                    TextInput::make('horas_totales')
                        ->numeric()
                        ->label('Horas Totales'),
                    Toggle::make('activo')
                        ->default(true)
                        ->label('Activo'),
                ])
            ]);
    }
}
