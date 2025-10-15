<?php

namespace App\Filament\Dashboard\Resources\LeadNotes\Schemas;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class LeadNoteForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make(__('Note Information'))
                    ->schema([
                        Hidden::make('tenant_id')
                            ->default(fn () => filament()->getTenant()?->id),
                        
                        Hidden::make('usuario_id')
                            ->default(fn () => auth()->id()),
                        
                        TextInput::make('titulo')
                            ->label(__('Title'))
                            ->required()
                            ->maxLength(255)
                            ->columnSpanFull(),
                                                    
                        Select::make('lead_id')
                            ->label(__('Lead'))
                            ->relationship('lead', 'nombre')
                            ->searchable()
                            ->preload()
                            ->required(),
                        
                        Select::make('tipo')
                            ->label(__('Type'))
                            ->options([
                                'llamada' => __('Call'),
                                'email' => __('Email'),
                                'reunion' => __('Meeting'),
                                'seguimiento' => __('Follow-up'),
                                'observacion' => __('Observation'),
                                'otro' => __('Other'),
                            ])
                            ->required()
                            ->default('observacion'),
                        
                        Textarea::make('contenido')
                            ->label(__('Content'))
                            ->rows(4)
                            ->columnSpanFull(),
                        
                        Toggle::make('es_importante')
                            ->label(__('Mark as important'))
                            ->default(false),
                        
                        DateTimePicker::make('fecha_seguimiento')
                            ->label(__('Follow-up Date'))
                            ->helperText(__('Optional: schedule a reminder for this date')),
                    ])
                    ->columns(2),
            ]);
    }
}
