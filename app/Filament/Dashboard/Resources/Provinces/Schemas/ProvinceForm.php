<?php

namespace App\Filament\Dashboard\Resources\Provinces\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Facades\Filament;

class ProvinceForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make()->schema([
                    TextInput::make('codigo')
                        ->required()
                        ->maxLength(10)
                        ->unique(
                            table: 'provinces',
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
                    TextInput::make('codigo_ine')
                        ->maxLength(5)
                        ->label('Código INE'),
                    TextInput::make('comunidad_autonoma')
                        ->maxLength(100)
                        ->label('Comunidad Autónoma'),
                    Toggle::make('activo')
                        ->default(true)
                        ->label('Activo'),
                ])
            ]);
    }
}
