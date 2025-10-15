<?php

namespace App\Filament\Dashboard\Resources\Courses\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Facades\Filament;

class CourseForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make()->schema([
                    TextInput::make('codigo_curso')
                        ->required()
                        ->maxLength(50)
                        ->unique(
                            table: 'courses',
                            column: 'codigo_curso',
                            ignoreRecord: true,
                            modifyRuleUsing: function ($rule) {
                                return $rule->where('tenant_id', Filament::getTenant()->id);
                            }
                        )
                        ->label(__('Course Code')),
                    TextInput::make('titulacion')
                        ->required()
                        ->maxLength(255)
                        ->columnSpanFull()
                        ->label(__('Degree')),
                    Select::make('area_id')
                        ->relationship('area', 'nombre')
                        ->searchable()
                        ->preload()
                        ->required()
                        ->label(__('Area')),
                    Select::make('unidad_negocio_id')
                        ->relationship('businessUnit', 'nombre')
                        ->searchable()
                        ->preload()
                        ->required()
                        ->label(__('Business Unit')),
                    Select::make('duracion_id')
                        ->relationship('duration', 'nombre')
                        ->searchable()
                        ->preload()
                        ->required()
                        ->label(__('Duration')),
                ])
            ]);
    }
}
