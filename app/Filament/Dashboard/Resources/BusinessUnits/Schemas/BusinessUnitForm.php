<?php

namespace App\Filament\Dashboard\Resources\BusinessUnits\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Facades\Filament;

class BusinessUnitForm
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
                            table: 'business_units',
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
                    Textarea::make('descripcion')
                        ->columnSpanFull()
                        ->label(__('Description')),
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
