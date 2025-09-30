<?php

namespace App\Filament\Dashboard\Resources\LeadEvents\Schemas;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Get;
use Filament\Schemas\Schema;

class LeadEventForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Información del Evento')
                    ->schema([
                        Hidden::make('tenant_id')
                            ->default(fn () => filament()->getTenant()?->id),
                        
                        Select::make('lead_id')
                            ->label('Lead')
                            ->relationship('lead', 'nombre')
                            ->searchable()
                            ->preload()
                            ->required(),
                        
                        Hidden::make('usuario_id')
                            ->default(fn () => auth()->id()),
                        
                        TextInput::make('titulo')
                            ->label('Título')
                            ->required()
                            ->maxLength(255)
                            ->columnSpanFull(),
                        
                        Select::make('tipo')
                            ->label('Tipo')
                            ->options([
                                'llamada' => 'Llamada',
                                'email' => 'Email',
                                'reunion' => 'Reunión',
                                'whatsapp' => 'WhatsApp',
                                'visita' => 'Visita',
                                'seguimiento' => 'Seguimiento',
                                'otro' => 'Otro',
                            ])
                            ->required()
                            ->default('llamada'),
                        
                        Select::make('estado')
                            ->label('Estado')
                            ->options([
                                'pendiente' => 'Pendiente',
                                'en_progreso' => 'En Progreso',
                                'completada' => 'Completada',
                                'cancelada' => 'Cancelada',
                            ])
                            ->required()
                            ->default('pendiente')
                            ->live(),
                        
                        Select::make('prioridad')
                            ->label('Prioridad')
                            ->options([
                                'baja' => 'Baja',
                                'media' => 'Media',
                                'alta' => 'Alta',
                                'urgente' => 'Urgente'
                            ])
                            ->required()
                            ->default('media'),
                        
                        DateTimePicker::make('fecha_programada')
                            ->label('Fecha programada')
                            ->required()
                            ->default(now()->addHour()),
                        
                        TextInput::make('duracion_estimada')
                            ->label('Duración estimada (minutos)')
                            ->numeric()
                            ->suffix('min')
                            ->default(30),
                        
                        Textarea::make('descripcion')
                            ->label('Descripción')
                            ->rows(3)
                            ->columnSpanFull(),
                    ])
                    ->columns(2),

                Section::make('Recordatorios')
                    ->schema([
                        Toggle::make('requiere_recordatorio')
                            ->label('Activar recordatorio')
                            ->default(true)
                            ->live(),
                        
                        TextInput::make('minutos_recordatorio')
                            ->label('Minutos antes del evento')
                            ->numeric()
                            ->default(15)
                            ->suffix('min')
                            ->visible(fn (Get $get): bool => $get('requiere_recordatorio')),
                    ])
                    ->columns(2),

                Section::make('Resultado')
                    ->schema([
                        DateTimePicker::make('fecha_completada')
                            ->label('Fecha de finalización')
                            ->visible(fn (Get $get): bool => in_array($get('estado'), ['completada', 'cancelada'])),
                        
                        Textarea::make('resultado')
                            ->label('Resultado/Notas')
                            ->rows(4)
                            ->columnSpanFull()
                            ->visible(fn (Get $get): bool => in_array($get('estado'), ['completada', 'cancelada'])),
                    ])
                    ->visible(fn (Get $get): bool => in_array($get('estado'), ['completada', 'cancelada'])),
            ]);
    }
}
