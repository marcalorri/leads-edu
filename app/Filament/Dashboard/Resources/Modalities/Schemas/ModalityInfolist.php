<?php

namespace App\Filament\Dashboard\Resources\Modalities\Schemas;

use Filament\Infolists\Components\IconEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class ModalityInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('tenant.name')
                    ->label(__('Tenant')),
                TextEntry::make('nombre')
                    ->label(__('Name')),
                TextEntry::make('descripcion')
                    ->label(__('Description'))
                    ->placeholder('-')
                    ->columnSpanFull(),
                TextEntry::make('codigo')
                    ->label(__('Code')),
                IconEntry::make('requiere_sede')
                    ->label(__('Requires Campus'))
                    ->boolean(),
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
