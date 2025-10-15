<?php

namespace App\Filament\Dashboard\Resources\Durations\Schemas;

use Filament\Infolists\Components\IconEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class DurationInfolist
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
                TextEntry::make('horas_totales')
                    ->label(__('Total Hours'))
                    ->numeric()
                    ->placeholder('-'),
                TextEntry::make('tipo')
                    ->label(__('Type'))
                    ->badge()
                    ->placeholder('-'),
                TextEntry::make('valor_numerico')
                    ->label(__('Numeric Value'))
                    ->numeric()
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
