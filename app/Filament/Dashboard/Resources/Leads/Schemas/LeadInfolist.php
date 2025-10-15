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
                Section::make(__('Lead Information'))
                    ->schema([
                        TextEntry::make('nombre')
                            ->label(__('First Name')),
                        TextEntry::make('apellidos')
                            ->label(__('Last Name')),
                        TextEntry::make('telefono')
                            ->label(__('Phone')),
                        TextEntry::make('email')
                            ->label(__('Email')),
                        TextEntry::make('estado')
                            ->badge()
                            ->label(__('Status')),
                        TextEntry::make('asesor.name')
                            ->label(__('Advisor'))
                            ->placeholder(__('Unassigned')),
                    ])->columns(2),

                Section::make(__('Contact Information'))
                    ->schema([
                        TextEntry::make('contact.nombre_completo')
                            ->label(__('Full Name'))
                            ->placeholder(__('No associated contact')),
                        TextEntry::make('contact.telefono_principal')
                            ->label(__('Main Phone'))
                            ->placeholder('-'),
                        TextEntry::make('contact.email_principal')
                            ->label(__('Main Email'))
                            ->placeholder('-'),
                        TextEntry::make('contact.telefono_secundario')
                            ->label(__('Secondary Phone'))
                            ->placeholder('-'),
                        TextEntry::make('contact.email_secundario')
                            ->label(__('Secondary Email'))
                            ->placeholder('-'),
                        TextEntry::make('contact.direccion')
                            ->label(__('Address'))
                            ->placeholder('-')
                            ->columnSpanFull(),
                    ])->columns(2)
                    ->visible(fn (Lead $record): bool => $record->contact !== null),

                Section::make(__('Academic Information'))
                    ->schema([
                        TextEntry::make('course.codigo_curso')
                            ->label(__('Course Code'))
                            ->badge(),
                        TextEntry::make('course.titulacion')
                            ->label(__('Degree')),
                        TextEntry::make('campus.nombre')
                            ->label(__('Campus')),
                        TextEntry::make('modality.nombre')
                            ->label(__('Modality')),
                        TextEntry::make('convocatoria')
                            ->label(__('Call')),
                        TextEntry::make('horario')
                            ->label(__('Schedule')),
                    ])->columns(2),

                Section::make(__('Follow-up'))
                    ->schema([
                        TextEntry::make('salesPhase.nombre')
                            ->label(__('Sales Phase'))
                            ->placeholder('-'),
                        TextEntry::make('origin.nombre')
                            ->label(__('Origin')),
                        TextEntry::make('nullReason.nombre')
                            ->label(__('Null Reason'))
                            ->placeholder('-')
                            ->visible(fn (Lead $record): bool => $record->estado === 'perdido'),
                        TextEntry::make('created_at')
                            ->label(__('Creation Date'))
                            ->dateTime('d/m/Y H:i'),
                    ])->columns(2),
            ]);
    }
}
