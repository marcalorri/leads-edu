<?php

namespace App\Filament\Dashboard\Resources\Contacts\Schemas;

use App\Models\Contact;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class ContactInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('leads_count')
                    ->counts('leads')
                    ->label('Total de Leads'),
                TextEntry::make('nombre_completo'),
                TextEntry::make('telefono_principal'),
                TextEntry::make('telefono_secundario')
                    ->placeholder('-'),
                TextEntry::make('email_principal'),
                TextEntry::make('email_secundario')
                    ->placeholder('-'),
                TextEntry::make('direccion')
                    ->placeholder('-')
                    ->columnSpanFull(),
                TextEntry::make('ciudad')
                    ->placeholder('-'),
                TextEntry::make('codigo_postal')
                    ->placeholder('-'),
                TextEntry::make('province.nombre')
                    ->label('Provincia')
                    ->placeholder('-'),
                TextEntry::make('fecha_nacimiento')
                    ->date()
                    ->placeholder('-'),
                TextEntry::make('dni_nie')
                    ->placeholder('-'),
                TextEntry::make('profesion')
                    ->placeholder('-'),
                TextEntry::make('empresa')
                    ->placeholder('-'),
                TextEntry::make('notas_contacto')
                    ->placeholder('-')
                    ->columnSpanFull(),
                TextEntry::make('preferencia_comunicacion')
                    ->badge(),
                TextEntry::make('created_at')
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('updated_at')
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('deleted_at')
                    ->dateTime()
                    ->visible(fn (Contact $record): bool => $record->trashed()),
            ]);
    }
}
