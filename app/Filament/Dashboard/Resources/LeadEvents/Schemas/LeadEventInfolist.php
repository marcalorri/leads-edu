<?php

namespace App\Filament\Dashboard\Resources\LeadEvents\Schemas;

use App\Models\LeadEvent;
use Filament\Infolists\Components\IconEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class LeadEventInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('tenant.name')
                    ->label('Tenant'),
                TextEntry::make('lead.id')
                    ->label('Lead'),
                TextEntry::make('usuario.name')
                    ->label('Usuario'),
                TextEntry::make('titulo'),
                TextEntry::make('descripcion')
                    ->placeholder('-')
                    ->columnSpanFull(),
                TextEntry::make('tipo')
                    ->badge(),
                TextEntry::make('estado')
                    ->badge(),
                TextEntry::make('prioridad')
                    ->badge(),
                TextEntry::make('fecha_programada')
                    ->dateTime(),
                TextEntry::make('fecha_completada')
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('duracion_estimada')
                    ->numeric()
                    ->placeholder('-'),
                TextEntry::make('resultado')
                    ->placeholder('-')
                    ->columnSpanFull(),
                IconEntry::make('requiere_recordatorio')
                    ->boolean(),
                TextEntry::make('minutos_recordatorio')
                    ->numeric(),
                TextEntry::make('created_at')
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('updated_at')
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('deleted_at')
                    ->dateTime()
                    ->visible(fn (LeadEvent $record): bool => $record->trashed()),
            ]);
    }
}
