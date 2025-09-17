<?php

namespace App\Filament\Dashboard\Resources\Leads\Schemas;

use App\Models\Lead;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class LeadInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('asesor.name')
                    ->label('Asesor')
                    ->placeholder('-'),
                TextEntry::make('estado')
                    ->badge(),
                TextEntry::make('fase_venta_id')
                    ->numeric(),
                TextEntry::make('curso_id')
                    ->numeric(),
                TextEntry::make('sede_id')
                    ->numeric(),
                TextEntry::make('modalidad_id')
                    ->numeric(),
                TextEntry::make('provincia_id')
                    ->numeric(),
                TextEntry::make('nombre'),
                TextEntry::make('apellidos'),
                TextEntry::make('telefono'),
                TextEntry::make('email')
                    ->label('Email address'),
                TextEntry::make('motivo_nulo_id')
                    ->numeric()
                    ->placeholder('-'),
                TextEntry::make('origen_id')
                    ->numeric(),
                TextEntry::make('convocatoria'),
                TextEntry::make('horario'),
                TextEntry::make('utm_source')
                    ->placeholder('-'),
                TextEntry::make('utm_medium')
                    ->placeholder('-'),
                TextEntry::make('utm_campaign')
                    ->placeholder('-'),
                TextEntry::make('created_at')
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('updated_at')
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('deleted_at')
                    ->dateTime()
                    ->visible(fn (Lead $record): bool => $record->trashed()),
            ]);
    }
}
