<?php

namespace App\Filament\Dashboard\Resources\LeadNotes\Schemas;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class LeadNoteForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('tenant_id')
                    ->relationship('tenant', 'name')
                    ->required(),
                Select::make('lead_id')
                    ->relationship('lead', 'id')
                    ->required(),
                Select::make('usuario_id')
                    ->relationship('usuario', 'name')
                    ->required(),
                TextInput::make('titulo'),
                Textarea::make('contenido')
                    ->required()
                    ->columnSpanFull(),
                Select::make('tipo')
                    ->options([
            'llamada' => 'Llamada',
            'email' => 'Email',
            'reunion' => 'Reunion',
            'seguimiento' => 'Seguimiento',
            'observacion' => 'Observacion',
            'otro' => 'Otro',
        ])
                    ->required(),
                Toggle::make('es_importante')
                    ->required(),
                DateTimePicker::make('fecha_seguimiento'),
            ]);
    }
}
