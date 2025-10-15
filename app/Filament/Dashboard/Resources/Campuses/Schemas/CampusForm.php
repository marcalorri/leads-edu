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
                        ->label(__('Code')),
                    TextInput::make('nombre')
                        ->required()
                        ->maxLength(100)
                        ->label(__('Name')),
                    Textarea::make('direccion')
                        ->columnSpanFull()
                        ->label(__('Address')),
                    TextInput::make('ciudad')
                        ->maxLength(100)
                        ->label(__('City')),
                    TextInput::make('codigo_postal')
                        ->maxLength(10)
                        ->label(__('Postal Code')),
                    TextInput::make('telefono')
                        ->tel()
                        ->maxLength(20)
                        ->label(__('Phone')),
                    TextInput::make('email')
                        ->email()
                        ->maxLength(255)
                        ->label(__('Email')),
                    TextInput::make('responsable')
                        ->maxLength(255)
                        ->label(__('Manager')),
                    Toggle::make('activo')
                        ->default(true)
                        ->label(__('Active')),
                ])
            ]);
    }
}
