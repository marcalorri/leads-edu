<?php

namespace App\Filament\Dashboard\Resources\Leads\Schemas;

use App\Models\Lead;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class LeadInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Información del Lead')
                    ->schema([
                        TextEntry::make('nombre')
                            ->label('Nombre'),
                        TextEntry::make('apellidos')
                            ->label('Apellidos'),
                        TextEntry::make('telefono')
                            ->label('Teléfono'),
                        TextEntry::make('email')
                            ->label('Email'),
                        TextEntry::make('estado')
                            ->badge()
                            ->label('Estado'),
                        TextEntry::make('asesor.name')
                            ->label('Asesor')
                            ->placeholder('Sin asignar'),
                    ])->columns(2),

                Section::make('Información del Contacto')
                    ->schema([
                        TextEntry::make('contact.nombre_completo')
                            ->label('Nombre Completo')
                            ->placeholder('Sin contacto asociado'),
                        TextEntry::make('contact.telefono_principal')
                            ->label('Teléfono Principal')
                            ->placeholder('-'),
                        TextEntry::make('contact.email_principal')
                            ->label('Email Principal')
                            ->placeholder('-'),
                        TextEntry::make('contact.telefono_secundario')
                            ->label('Teléfono Secundario')
                            ->placeholder('-'),
                        TextEntry::make('contact.email_secundario')
                            ->label('Email Secundario')
                            ->placeholder('-'),
                        TextEntry::make('contact.direccion')
                            ->label('Dirección')
                            ->placeholder('-')
                            ->columnSpanFull(),
                    ])->columns(2)
                    ->visible(fn (Lead $record): bool => $record->contact !== null),

                Section::make('Información Académica')
                    ->schema([
                        TextEntry::make('course.codigo_curso')
                            ->label('Código Curso')
                            ->badge(),
                        TextEntry::make('course.titulacion')
                            ->label('Titulación'),
                        TextEntry::make('campus.nombre')
                            ->label('Sede'),
                        TextEntry::make('modality.nombre')
                            ->label('Modalidad'),
                        TextEntry::make('convocatoria')
                            ->label('Convocatoria'),
                        TextEntry::make('horario')
                            ->label('Horario'),
                    ])->columns(2),

                Section::make('Seguimiento')
                    ->schema([
                        TextEntry::make('salesPhase.nombre')
                            ->label('Fase de Venta')
                            ->placeholder('-'),
                        TextEntry::make('origin.nombre')
                            ->label('Origen'),
                        TextEntry::make('nullReason.nombre')
                            ->label('Motivo Nulo')
                            ->placeholder('-')
                            ->visible(fn (Lead $record): bool => $record->estado === 'perdido'),
                        TextEntry::make('created_at')
                            ->label('Fecha Creación')
                            ->dateTime('d/m/Y H:i'),
                    ])->columns(2),
            ]);
    }
}
