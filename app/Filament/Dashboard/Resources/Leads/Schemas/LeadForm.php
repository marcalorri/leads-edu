<?php

namespace App\Filament\Dashboard\Resources\Leads\Schemas;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Get;
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
                Tabs::make(__('Lead Information'))
                    ->columnSpan(3)
                    ->tabs([
                        Tabs\Tab::make(__('Personal Information'))
                            ->icon('heroicon-o-user')
                            ->schema([
                                Section::make(__('Personal Data'))
                                    ->schema([
                                        TextInput::make('nombre')
                                            ->required()
                                            ->maxLength(100)
                                            ->label(__('First Name')),
                                        TextInput::make('apellidos')
                                            ->required()
                                            ->maxLength(150)
                                            ->label(__('Last Name')),
                                        TextInput::make('telefono')
                                            ->tel()
                                            ->required()
                                            ->maxLength(20)
                                            ->label(__('Phone')),
                                        TextInput::make('email')
                                            ->email()
                                            ->required()
                                            ->maxLength(255)
                                            ->label(__('Email')),
                                        Select::make('country_id')
                                            ->relationship('country', 'nombre', fn ($query) => $query->where('activo', true))
                                            ->searchable()
                                            ->preload()
                                            ->live()
                                            ->afterStateUpdated(function ($state, $set) {
                                                // Limpiar provincia cuando cambia el país
                                                $set('provincia_id', null);
                                            })
                                            ->label(__('Country'))
                                            ->helperText(__('Select country first to filter provinces')),
                                        Select::make('provincia_id')
                                            ->relationship(
                                                'province',
                                                'nombre',
                                                fn ($query, $get) => $query
                                                    ->where('activo', true)
                                                    ->when(
                                                        $get('country_id'),
                                                        fn ($q, $countryId) => $q->where('country_id', $countryId)
                                                    )
                                            )
                                            ->searchable()
                                            ->preload()
                                            ->label(__('Province / State'))
                                            ->helperText(fn ($get) => !$get('country_id') 
                                                ? __('Select a country first') 
                                                : __('Provinces filtered by selected country')
                                            ),
                                    ])->columns(3),
                                
                                Section::make(__('Academic Information'))
                                    ->schema([
                                        Select::make('curso_id')
                                            ->relationship(
                                                'course',
                                                'titulacion',
                                                fn ($query) => $query->where('tenant_id', filament()->getTenant()->id)
                                            )
                                            ->searchable()
                                            ->preload()
                                            ->createOptionForm([
                                                TextInput::make('codigo_curso')
                                                    ->required()
                                                    ->maxLength(50)
                                                    ->label(__('Course Code')),
                                                TextInput::make('titulacion')
                                                    ->required()
                                                    ->maxLength(255)
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
                                            ->createOptionUsing(function ($data) {
                                                $data['tenant_id'] = filament()->getTenant()->id;
                                                return \App\Models\Course::create($data)->id;
                                            })
                                            ->label(__('Course'))
                                            ->columnSpanFull(),
                                        Select::make('sede_id')
                                            ->relationship(
                                                'campus',
                                                'nombre',
                                                fn ($query) => $query->where('tenant_id', filament()->getTenant()->id)
                                            )
                                            ->searchable()
                                            ->preload()
                                            ->createOptionForm([
                                                TextInput::make('codigo')
                                                    ->required()
                                                    ->maxLength(20)
                                                    ->label(__('Code')),
                                                TextInput::make('nombre')
                                                    ->required()
                                                    ->maxLength(100)
                                                    ->label(__('Name')),
                                                TextInput::make('ciudad')
                                                    ->maxLength(100)
                                                    ->label(__('City')),
                                                TextInput::make('telefono')
                                                    ->tel()
                                                    ->maxLength(20)
                                                    ->label(__('Phone')),
                                            ])
                                            ->createOptionUsing(function ($data) {
                                                $data['tenant_id'] = filament()->getTenant()->id;
                                                $data['activo'] = true;
                                                return \App\Models\Campus::create($data)->id;
                                            })
                                            ->label(__('Campus')),
                                        Select::make('modalidad_id')
                                            ->relationship(
                                                'modality',
                                                'nombre',
                                                fn ($query) => $query->where('tenant_id', filament()->getTenant()->id)
                                            )
                                            ->searchable()
                                            ->preload()
                                            ->createOptionForm([
                                                TextInput::make('codigo')
                                                    ->required()
                                                    ->maxLength(10)
                                                    ->label(__('Code')),
                                                TextInput::make('nombre')
                                                    ->required()
                                                    ->maxLength(50)
                                                    ->label(__('Name')),
                                                Toggle::make('requiere_sede')
                                                    ->default(true)
                                                    ->label(__('Requires Campus')),
                                            ])
                                            ->createOptionUsing(function ($data) {
                                                $data['tenant_id'] = filament()->getTenant()->id;
                                                $data['activo'] = true;
                                                return \App\Models\Modality::create($data)->id;
                                            })
                                            ->label(__('Modality')),
                                        TextInput::make('convocatoria')
                                            ->maxLength(100)
                                            ->label(__('Call')),
                                        TextInput::make('horario')
                                            ->maxLength(100)
                                            ->label(__('Schedule')),
                                    ])->columns(2),
                            ]),
                        
                        Tabs\Tab::make(__('Contact'))
                            ->icon('heroicon-o-phone')
                            ->schema([
                                Section::make(__('Associated Contact'))
                                    ->schema([
                                        Select::make('contact_id')
                                            ->relationship(
                                                'contact',
                                                'nombre_completo',
                                                fn ($query) => $query->where('tenant_id', filament()->getTenant()->id)
                                            )
                                            ->searchable()
                                            ->preload()
                                            ->label(__('Contact'))
                                            ->placeholder(__('Select contact'))
                                            ->createOptionForm([
                                                TextInput::make('nombre_completo')
                                                    ->required()
                                                    ->label(__('Full Name')),
                                                TextInput::make('telefono_principal')
                                                    ->required()
                                                    ->label(__('Main Phone')),
                                                TextInput::make('email_principal')
                                                    ->email()
                                                    ->required()
                                                    ->label(__('Main Email')),
                                            ])
                                            ->createOptionUsing(function (array $data) {
                                                $data['tenant_id'] = filament()->getTenant()->id;
                                                return \App\Models\Contact::create($data);
                                            }),
                                    ])->columns(1),
                            ]),
                        
                        Tabs\Tab::make(__('Origin and Tracking'))
                            ->icon('heroicon-o-chart-pie')
                            ->schema([
                                Section::make(__('Lead Origin'))
                                    ->schema([
                                        Select::make('origen_id')
                                            ->relationship(
                                                'origin',
                                                'nombre',
                                                fn ($query) => $query->where('tenant_id', filament()->getTenant()->id)
                                            )
                                            ->searchable()
                                            ->preload()
                                            ->label(__('Origin')),
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
                Section::make(__('Status and Follow-up'))
                    ->columnSpan(1)
                    ->schema([
                        Select::make('estado')
                            ->options([
                                'abierto' => __('Open'),
                                'ganado' => __('Won'),
                                'perdido' => __('Lost'),
                            ])
                            ->required()
                            ->default('abierto')
                            ->live()
                            ->afterStateUpdated(function ($state, $set) {
                                if ($state === 'perdido') {
                                    $set('fecha_perdido', now());
                                } elseif ($state === 'ganado') {
                                    $set('fecha_ganado', now());
                                }
                            })
                            ->label(__('Status')),
                        Select::make('fase_venta_id')
                            ->relationship(
                                'salesPhase',
                                'nombre',
                                fn ($query) => $query->where('tenant_id', filament()->getTenant()->id)
                            )
                            ->searchable()
                            ->preload()
                            ->label(__('Sales Phase')),
                        Select::make('asesor_id')
                            ->relationship(
                                'asesor',
                                'name',
                                fn ($query) => $query->whereHas('tenants', function ($q) {
                                    $q->where('tenant_id', filament()->getTenant()->id);
                                })
                            )
                            ->searchable()
                            ->preload()
                            ->label(__('Assigned Advisor')),
                        Select::make('motivo_nulo_id')
                            ->relationship(
                                'nullReason',
                                'nombre',
                                fn ($query) => $query->where('tenant_id', filament()->getTenant()->id)
                            )
                            ->searchable()
                            ->preload()
                            ->required(fn ($get) => $get('estado') === 'perdido')
                            ->label(__('Null Reason'))
                            ->helperText(__('Required when status is "Lost"'))
                            ->visible(fn ($get) => $get('estado') === 'perdido'),
                        DateTimePicker::make('fecha_ganado')
                            ->label(__('Won Date'))
                            ->helperText(__('Set automatically when marked as won'))
                            ->seconds(false)
                            ->visible(fn ($get) => $get('estado') === 'ganado'),
                        DateTimePicker::make('fecha_perdido')
                            ->label(__('Lost Date'))
                            ->helperText(__('Set automatically when marked as lost'))
                            ->seconds(false)
                            ->visible(fn ($get) => $get('estado') === 'perdido'),
                    ])->columns(1),
            ]);
    }
}
