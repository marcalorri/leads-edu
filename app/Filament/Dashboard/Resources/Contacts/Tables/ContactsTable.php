<?php

namespace App\Filament\Dashboard\Resources\Contacts\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ForceDeleteBulkAction;
use Filament\Actions\RestoreBulkAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Table;

class ContactsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('nombre_completo')
                    ->label('Nombre Completo')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('telefono_principal')
                    ->label('Teléfono')
                    ->searchable(),
                TextColumn::make('email_principal')
                    ->label('Email')
                    ->searchable(),
                TextColumn::make('province.nombre')
                    ->label('Provincia')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('leads_count')
                    ->counts('leads')
                    ->label('Leads')
                    ->sortable(),
                TextColumn::make('preferencia_comunicacion')
                    ->label('Preferencia')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'email' => 'success',
                        'telefono' => 'info',
                        'whatsapp' => 'warning',
                        'sms' => 'gray',
                        default => 'gray',
                    }),
                TextColumn::make('created_at')
                    ->dateTime('d/m/Y')
                    ->label('Creado')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                TrashedFilter::make(),
            ])
            ->recordActions([
                ViewAction::make(),
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                    ForceDeleteBulkAction::make(),
                    RestoreBulkAction::make(),
                ]),
            ]);
    }
}
