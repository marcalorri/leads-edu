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
                    ->label(__('Total Leads')),
                TextEntry::make('nombre_completo')
                    ->label(__('Full Name')),
                TextEntry::make('telefono_principal')
                    ->label(__('Main Phone')),
                TextEntry::make('telefono_secundario')
                    ->label(__('Secondary Phone'))
                    ->placeholder('-'),
                TextEntry::make('email_principal')
                    ->label(__('Main Email')),
                TextEntry::make('email_secundario')
                    ->label(__('Secondary Email'))
                    ->placeholder('-'),
                TextEntry::make('direccion')
                    ->label(__('Address'))
                    ->placeholder('-')
                    ->columnSpanFull(),
                TextEntry::make('ciudad')
                    ->label(__('City'))
                    ->placeholder('-'),
                TextEntry::make('codigo_postal')
                    ->label(__('Postal Code'))
                    ->placeholder('-'),
                TextEntry::make('province.nombre')
                    ->label(__('Province'))
                    ->placeholder('-'),
                TextEntry::make('fecha_nacimiento')
                    ->label(__('Birth Date'))
                    ->date()
                    ->placeholder('-'),
                TextEntry::make('dni_nie')
                    ->label(__('DNI/NIE'))
                    ->placeholder('-'),
                TextEntry::make('profesion')
                    ->label(__('Profession'))
                    ->placeholder('-'),
                TextEntry::make('empresa')
                    ->label(__('Company'))
                    ->placeholder('-'),
                TextEntry::make('notas_contacto')
                    ->label(__('Contact Notes'))
                    ->placeholder('-')
                    ->columnSpanFull(),
                TextEntry::make('preferencia_comunicacion')
                    ->label(__('Communication Preference'))
                    ->badge(),
                TextEntry::make('created_at')
                    ->label(__('Created'))
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('updated_at')
                    ->label(__('Updated'))
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('deleted_at')
                    ->label(__('Deleted'))
                    ->dateTime()
                    ->visible(fn (Contact $record): bool => $record->trashed()),
            ]);
    }
}
