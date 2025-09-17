<?php

namespace App\Filament\Dashboard\Resources\LeadEvents\Schemas;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class LeadEventForm
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
                TextInput::make('titulo')
                    ->required(),
                Textarea::make('descripcion')
                    ->columnSpanFull(),
                Select::make('tipo')
                    ->options([
            'llamada' => 'Llamada',
            'email' => 'Email',
            'reunion' => 'Reunion',
            'whatsapp' => 'Whatsapp',
            'visita' => 'Visita',
            'seguimiento' => 'Seguimiento',
            'otro' => 'Otro',
        ])
                    ->required(),
                Select::make('estado')
                    ->options([
            'pendiente' => 'Pendiente',
            'en_progreso' => 'En progreso',
            'completada' => 'Completada',
            'cancelada' => 'Cancelada',
        ])
                    ->required(),
                Select::make('prioridad')
                    ->options(['baja' => 'Baja', 'media' => 'Media', 'alta' => 'Alta', 'urgente' => 'Urgente'])
                    ->required(),
                DateTimePicker::make('fecha_programada')
                    ->required(),
                DateTimePicker::make('fecha_completada'),
                TextInput::make('duracion_estimada')
                    ->numeric(),
                Textarea::make('resultado')
                    ->columnSpanFull(),
                Toggle::make('requiere_recordatorio')
                    ->required(),
                TextInput::make('minutos_recordatorio')
                    ->required()
                    ->numeric()
                    ->default(15),
            ]);
    }
}
