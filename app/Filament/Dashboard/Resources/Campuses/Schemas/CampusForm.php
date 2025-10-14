<?php

namespace App\Filament\Dashboard\Resources\Campuses\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Facades\Filament;

class CampusForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make()->schema([
                    TextInput::make('codigo')
                        ->required()
                        ->maxLength(20)
                        ->unique(
                            table: 'campuses',
                            column: 'codigo',
                            ignoreRecord: true,
                            modifyRuleUsing: function ($rule) {
                                return $rule->where('tenant_id', Filament::getTenant()->id);
                            }
                        )
                        ->label('Código'),
                    TextInput::make('nombre')
                        ->required()
                        ->maxLength(100)
                        ->label('Nombre'),
                    Textarea::make('direccion')
                        ->columnSpanFull()
                        ->label('Dirección'),
                    TextInput::make('ciudad')
                        ->maxLength(100)
                        ->label('Ciudad'),
                    TextInput::make('codigo_postal')
                        ->maxLength(10)
                        ->label('Código Postal'),
                    TextInput::make('telefono')
                        ->tel()
                        ->maxLength(20)
                        ->label('Teléfono'),
                    TextInput::make('email')
                        ->email()
                        ->maxLength(255)
                        ->label('Email'),
                    TextInput::make('responsable')
                        ->maxLength(255)
                        ->label('Responsable'),
                    Toggle::make('activo')
                        ->default(true)
                        ->label('Activo'),
                ])
            ]);
    }
}
