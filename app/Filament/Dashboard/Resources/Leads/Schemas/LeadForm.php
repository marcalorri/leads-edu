<?php

namespace App\Filament\Dashboard\Resources\Leads\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class LeadForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Información Personal')
                    ->schema([
                        TextInput::make('nombre')
                            ->required()
                            ->maxLength(100)
                            ->label('Nombre'),
                        TextInput::make('apellidos')
                            ->required()
                            ->maxLength(150)
                            ->label('Apellidos'),
                        TextInput::make('telefono')
                            ->tel()
                            ->required()
                            ->maxLength(20)
                            ->label('Teléfono'),
                        TextInput::make('email')
                            ->email()
                            ->required()
                            ->maxLength(255)
                            ->label('Email'),
                        TextInput::make('pais')
                            ->maxLength(100)
                            ->label('País'),
                        Select::make('provincia_id')
                            ->relationship('province', 'nombre')
                            ->searchable()
                            ->preload()
                            ->required()
                            ->label('Provincia'),
                    ])->columns(2),
                
                Section::make('Información Académica')
                    ->schema([
                        Select::make('curso_id')
                            ->relationship('course', 'titulacion')
                            ->searchable()
                            ->preload()
                            ->required()
                            ->label('Curso'),
                        Select::make('sede_id')
                            ->relationship('campus', 'nombre')
                            ->searchable()
                            ->preload()
                            ->required()
                            ->label('Sede'),
                        Select::make('modalidad_id')
                            ->relationship('modality', 'nombre')
                            ->searchable()
                            ->preload()
                            ->label('Modalidad'),
                        TextInput::make('convocatoria')
                            ->maxLength(100)
                            ->label('Convocatoria'),
                        TextInput::make('horario')
                            ->maxLength(100)
                            ->label('Horario'),
                    ])->columns(2),
                
                Section::make('Estado y Seguimiento')
                    ->schema([
                        Select::make('estado')
                            ->options([
                                'abierto' => 'Abierto',
                                'ganado' => 'Ganado',
                                'perdido' => 'Perdido',
                            ])
                            ->required()
                            ->default('abierto')
                            ->label('Estado'),
                        Select::make('fase_venta_id')
                            ->relationship('salesPhase', 'nombre')
                            ->searchable()
                            ->preload()
                            ->label('Fase de Venta'),
                        Select::make('asesor_id')
                            ->relationship('asesor', 'name')
                            ->searchable()
                            ->preload()
                            ->label('Asesor Asignado'),
                        Select::make('motivo_nulo_id')
                            ->relationship('nullReason', 'nombre')
                            ->searchable()
                            ->preload()
                            ->label('Motivo Nulo')
                            ->visible(fn ($get) => $get('estado') === 'perdido'),
                        TextInput::make('fecha_ganado')
                            ->type('datetime-local')
                            ->label('Fecha Ganado')
                            ->visible(fn ($get) => $get('estado') === 'ganado'),
                        TextInput::make('fecha_perdido')
                            ->type('datetime-local')
                            ->label('Fecha Perdido')
                            ->visible(fn ($get) => $get('estado') === 'perdido'),
                    ])->columns(2),
                
                Section::make('Origen y Tracking')
                    ->schema([
                        Select::make('origen_id')
                            ->relationship('origin', 'nombre')
                            ->searchable()
                            ->preload()
                            ->label('Origen'),
                        TextInput::make('utm_source')
                            ->maxLength(255)
                            ->label('UTM Source'),
                        TextInput::make('utm_medium')
                            ->maxLength(255)
                            ->label('UTM Medium'),
                        TextInput::make('utm_campaign')
                            ->maxLength(255)
                            ->label('UTM Campaign'),
                    ])->columns(2),
            ]);
    }
}
