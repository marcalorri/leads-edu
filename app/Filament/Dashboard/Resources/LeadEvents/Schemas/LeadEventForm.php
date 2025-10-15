<?php

namespace App\Filament\Dashboard\Resources\LeadEvents\Schemas;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Get;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class LeadEventForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make(__('Event Information'))
                    ->schema([
                        Hidden::make('tenant_id')
                            ->default(fn () => filament()->getTenant()?->id),
                        
                        Select::make('lead_id')
                            ->label(__('Lead'))
                            ->relationship('lead', 'nombre')
                            ->searchable()
                            ->preload()
                            ->required(),
                        
                        Hidden::make('usuario_id')
                            ->default(fn () => auth()->id()),
                        
                        TextInput::make('titulo')
                            ->label(__('Title'))
                            ->required()
                            ->maxLength(255)
                            ->columnSpanFull(),
                        
                        Select::make('tipo')
                            ->label(__('Type'))
                            ->options([
                                'llamada' => __('Call'),
                                'email' => __('Email'),
                                'reunion' => __('Meeting'),
                                'whatsapp' => __('WhatsApp'),
                                'visita' => __('Visit'),
                                'seguimiento' => __('Follow-up'),
                                'otro' => __('Other'),
                            ])
                            ->required()
                            ->default('llamada'),
                        
                        Select::make('estado')
                            ->label(__('Status'))
                            ->options([
                                'pendiente' => __('Pending'),
                                'en_progreso' => __('In Progress'),
                                'completada' => __('Completed'),
                                'cancelada' => __('Cancelled'),
                            ])
                            ->required()
                            ->default('pendiente')
                            ->live(),
                        
                        Select::make('prioridad')
                            ->label(__('Priority'))
                            ->options([
                                'baja' => __('Low'),
                                'media' => __('Medium'),
                                'alta' => __('High'),
                                'urgente' => __('Urgent')
                            ])
                            ->required()
                            ->default('media'),
                        
                        DateTimePicker::make('fecha_programada')
                            ->label(__('Scheduled Date'))
                            ->required()
                            ->default(now()->addHour()),
                        
                        TextInput::make('duracion_estimada')
                            ->label(__('Estimated Duration (minutes)'))
                            ->numeric()
                            ->suffix('min')
                            ->default(30),
                        
                        Textarea::make('descripcion')
                            ->label(__('Description'))
                            ->rows(3)
                            ->columnSpanFull(),
                    ])
                    ->columns(2),

                Section::make(__('Reminders'))
                    ->schema([
                        Toggle::make('requiere_recordatorio')
                            ->label(__('Enable reminder'))
                            ->default(true)
                            ->live(),
                        
                        TextInput::make('minutos_recordatorio')
                            ->label(__('Minutes before event'))
                            ->numeric()
                            ->default(15)
                            ->suffix('min')
                            ->visible(fn (Get $get): bool => $get('requiere_recordatorio')),
                    ])
                    ->columns(2),

                Section::make(__('Result'))
                    ->schema([
                        DateTimePicker::make('fecha_completada')
                            ->label(__('Completion Date'))
                            ->visible(fn (Get $get): bool => in_array($get('estado'), ['completada', 'cancelada'])),
                        
                        Textarea::make('resultado')
                            ->label(__('Result/Notes'))
                            ->rows(4)
                            ->columnSpanFull()
                            ->visible(fn (Get $get): bool => in_array($get('estado'), ['completada', 'cancelada'])),
                    ])
                    ->visible(fn (Get $get): bool => in_array($get('estado'), ['completada', 'cancelada'])),
            ]);
    }
}
