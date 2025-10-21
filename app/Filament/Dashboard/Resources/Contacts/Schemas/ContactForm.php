<?php

namespace App\Filament\Dashboard\Resources\Contacts\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Get;
use Filament\Schemas\Schema;

class ContactForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('tenant_id')
                    ->relationship('tenant', 'name')
                    ->hidden(),
                TextInput::make('nombre_completo')
                    ->required()
                    ->label(__('Full Name')),
                TextInput::make('telefono_principal')
                    ->tel()
                    ->required()
                    ->label(__('Main Phone')),
                TextInput::make('telefono_secundario')
                    ->tel()
                    ->label(__('Secondary Phone')),
                TextInput::make('email_principal')
                    ->email()
                    ->required()
                    ->label(__('Main Email')),
                TextInput::make('email_secundario')
                    ->email()
                    ->label(__('Secondary Email')),
                Textarea::make('direccion')
                    ->columnSpanFull()
                    ->label(__('Address')),
                TextInput::make('ciudad')
                    ->label(__('City')),
                TextInput::make('codigo_postal')
                    ->label(__('Postal Code')),
                Select::make('country_id')
                    ->relationship('country', 'nombre', fn ($query) => $query->where('activo', true))
                    ->searchable()
                    ->preload()
                    ->live()
                    ->afterStateUpdated(function ($state, $set) {
                        // Limpiar provincia cuando cambia el país
                        $set('provincia_id', null);
                    })
                    ->label(__('Country')),
                Select::make('provincia_id')
                    ->relationship(
                        'province',
                        'nombre',
                        fn ($query, $get) => $query
                            ->where('activo', true)
                            ->when(
                                $get('country_id'),
                                fn ($q, $countryId) => $q->where('country_id', $countryId)
                            )
                    )
                    ->searchable()
                    ->preload()
                    ->label(__('Province / State')
                    ),
                DatePicker::make('fecha_nacimiento')
                    ->label(__('Birth Date')),
                TextInput::make('dni_nie')
                    ->label(__('DNI/NIE')),
                TextInput::make('profesion')
                    ->label(__('Profession')),
                TextInput::make('empresa')
                    ->label(__('Company')),
                Textarea::make('notas_contacto')
                    ->columnSpanFull()
                    ->label(__('Contact Notes')),
                Select::make('preferencia_comunicacion')
                    ->options([
                        'email' => __('Email'),
                        'telefono' => __('Phone'),
                        'whatsapp' => __('WhatsApp'),
                        'sms' => __('SMS')
                    ])
                    ->required()
                    ->label(__('Communication Preference')),
            ]);
    }
}
