<?php

namespace App\Filament\Dashboard\Resources\LeadNotes\Schemas;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Section;
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
                Section::make('Información de la Nota')
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
                            ->maxLength(255),
                        
                        Select::make('tipo')
                            ->label('Tipo')
                            ->options([
                                'llamada' => 'Llamada',
                                'email' => 'Email',
                                'reunion' => 'Reunión',
                                'seguimiento' => 'Seguimiento',
                                'observacion' => 'Observación',
                                'otro' => 'Otro',
                            ])
                            ->required()
                            ->default('observacion'),
                        
                        Textarea::make('contenido')
                            ->label('Contenido')
                            ->required()
                            ->rows(4)
                            ->columnSpanFull(),
                        
                        Toggle::make('es_importante')
                            ->label('Marcar como importante')
                            ->default(false),
                        
                        DateTimePicker::make('fecha_seguimiento')
                            ->label('Fecha de seguimiento')
                            ->helperText('Opcional: programa un recordatorio para esta fecha'),
                    ])
                    ->columns(2),
            ]);
    }
}
