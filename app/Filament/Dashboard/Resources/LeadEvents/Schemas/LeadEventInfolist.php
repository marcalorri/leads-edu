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
                    ->label(__('Tenant')),
                TextEntry::make('lead.nombre')
                    ->label(__('Lead')),
                TextEntry::make('usuario.name')
                    ->label(__('User')),
                TextEntry::make('titulo')
                    ->label(__('Title')),
                TextEntry::make('descripcion')
                    ->label(__('Description'))
                    ->placeholder('-')
                    ->columnSpanFull(),
                TextEntry::make('tipo')
                    ->label(__('Type'))
                    ->badge(),
                TextEntry::make('estado')
                    ->label(__('Status'))
                    ->badge(),
                TextEntry::make('prioridad')
                    ->label(__('Priority'))
                    ->badge(),
                TextEntry::make('fecha_programada')
                    ->label(__('Scheduled Date'))
                    ->dateTime(),
                TextEntry::make('fecha_completada')
                    ->label(__('Completed Date'))
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('duracion_estimada')
                    ->label(__('Estimated Duration'))
                    ->numeric()
                    ->placeholder('-'),
                TextEntry::make('resultado')
                    ->label(__('Result'))
                    ->placeholder('-')
                    ->columnSpanFull(),
                IconEntry::make('requiere_recordatorio')
                    ->label(__('Requires Reminder'))
                    ->boolean(),
                TextEntry::make('minutos_recordatorio')
                    ->label(__('Reminder Minutes'))
                    ->numeric(),
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
                    ->visible(fn (LeadEvent $record): bool => $record->trashed()),
            ]);
    }
}
