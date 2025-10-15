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
                        ->label(__('Name')),
                    Textarea::make('descripcion')
                        ->columnSpanFull()
                        ->label(__('Description')),
                    Select::make('tipo')
                        ->options([
                            'horas' => __('Hours'),
                            'dias' => __('Days'),
                            'semanas' => __('Weeks'),
                            'meses' => __('Months'),
                            'años' => __('Years'),
                        ])
                        ->required()
                        ->label(__('Type')),
                    TextInput::make('valor_numerico')
                        ->numeric()
                        ->label(__('Numeric Value')),
                    TextInput::make('horas_totales')
                        ->numeric()
                        ->label(__('Total Hours')),
                    Toggle::make('activo')
                        ->default(true)
                        ->label(__('Active')),
                ])
            ]);
    }
}
