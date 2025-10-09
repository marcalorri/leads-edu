<?php

namespace App\Filament\Dashboard\Resources\Leads\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Tabs;
use Filament\Schemas\Schema;

class LeadForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->columns(4)
            ->components([
                // Área principal con tabs (3/4 del ancho)
                Tabs::make('Lead Information')
                    ->columnSpan(3)
                    ->tabs([
                        Tabs\Tab::make('Información Personal')
                            ->icon('heroicon-o-user')
                            ->schema([
                                Section::make('Datos Personales')
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
                                            ->label('Provincia'),
                                    ])->columns(3),
                                
                                Section::make('Información Académica')
                                    ->schema([
                                        Select::make('curso_id')
                                            ->relationship('course', 'titulacion')
                                            ->searchable()
                                            ->preload()
                                            ->label('Curso')
                                            ->columnSpanFull(),
                                        Select::make('sede_id')
                                            ->relationship('campus', 'nombre')
                                            ->searchable()
                                            ->preload()
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
                            ]),
                        
                        Tabs\Tab::make('Contacto')
                            ->icon('heroicon-o-phone')
                            ->schema([
                                Section::make('Contacto Asociado')
                                    ->schema([
                                        Select::make('contact_id')
                                            ->relationship('contact', 'nombre_completo')
                                            ->searchable()
                                            ->preload()
                                            ->label('Contacto')
                                            ->placeholder('Seleccionar contacto')
                                            ->createOptionForm([
                                                TextInput::make('nombre_completo')
                                                    ->required()
                                                    ->label('Nombre Completo'),
                                                TextInput::make('telefono_principal')
                                                    ->required()
                                                    ->label('Teléfono Principal'),
                                                TextInput::make('email_principal')
                                                    ->email()
                                                    ->required()
                                                    ->label('Email Principal'),
                                            ])
                                            ->createOptionUsing(function (array $data) {
                                                $data['tenant_id'] = filament()->getTenant()->id;
                                                return \App\Models\Contact::create($data);
                                            }),
                                    ])->columns(1),
                            ]),
                        
                        Tabs\Tab::make('Origen y Tracking')
                            ->icon('heroicon-o-chart-pie')
                            ->schema([
                                Section::make('Origen del Lead')
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
                            ]),
                    ]),
                
                // Columna lateral fija (1/4 del ancho)
                Section::make('Estado y Seguimiento')
                    ->columnSpan(1)
                    ->schema([
                        Select::make('estado')
                            ->options([
                                'abierto' => 'Abierto',
                                'ganado' => 'Ganado',
                                'perdido' => 'Perdido',
                            ])
                            ->required()
                            ->default('abierto')
                            ->live()
                            ->afterStateUpdated(function ($state, $set) {
                                if ($state === 'perdido') {
                                    $set('fecha_perdido', now()->format('Y-m-d\TH:i'));
                                } elseif ($state === 'ganado') {
                                    $set('fecha_ganado', now()->format('Y-m-d\TH:i'));
                                }
                            })
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
                            ->required(fn ($get) => $get('estado') === 'perdido')
                            ->label('Motivo Nulo')
                            ->helperText('Obligatorio cuando el estado es "Perdido"')
                            ->visible(fn ($get) => $get('estado') === 'perdido'),
                        TextInput::make('fecha_ganado')
                            ->type('datetime-local')
                            ->label('Fecha Ganado')
                            ->helperText('Se establece automáticamente al marcar como ganado')
                            ->visible(fn ($get) => $get('estado') === 'ganado'),
                        TextInput::make('fecha_perdido')
                            ->type('datetime-local')
                            ->label('Fecha Perdido')
                            ->helperText('Se establece automáticamente al marcar como perdido')
                            ->visible(fn ($get) => $get('estado') === 'perdido'),
                    ])->columns(1),
            ]);
    }
}
