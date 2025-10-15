<?php

namespace App\Filament\Dashboard\Resources\Campuses\Schemas;

use Filament\Infolists\Components\IconEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class CampusInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('tenant.name')
                    ->label(__('Tenant')),
                TextEntry::make('nombre')
                    ->label(__('Name')),
                TextEntry::make('codigo')
                    ->label(__('Code')),
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
                TextEntry::make('telefono')
                    ->label(__('Phone'))
                    ->placeholder('-'),
                TextEntry::make('email')
                    ->label(__('Email'))
                    ->placeholder('-'),
                TextEntry::make('responsable')
                    ->label(__('Manager'))
                    ->placeholder('-'),
                IconEntry::make('activo')
                    ->label(__('Active'))
                    ->boolean(),
                TextEntry::make('created_at')
                    ->label(__('Created'))
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('updated_at')
                    ->label(__('Updated'))
                    ->dateTime()
                    ->placeholder('-'),
            ]);
    }
}
