<?php

namespace App\Filament\Dashboard\Resources\Origins\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class OriginForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make(__('Origin Information'))
                    ->schema([
                        TextInput::make('nombre')
                            ->required()
                            ->maxLength(100)
                            ->label(__('Name')),
                        Textarea::make('descripcion')
                            ->maxLength(500)
                            ->label(__('Description')),
                        Toggle::make('activo')
                            ->default(true)
                            ->label(__('Active')),
                    ])->columns(2),
            ]);
    }
}
