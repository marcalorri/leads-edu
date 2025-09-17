<?php

namespace App\Filament\Dashboard\Resources\Contacts\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
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
                    ->required(),
                TextInput::make('telefono_principal')
                    ->tel()
                    ->required(),
                TextInput::make('telefono_secundario')
                    ->tel(),
                TextInput::make('email_principal')
                    ->email()
                    ->required(),
                TextInput::make('email_secundario')
                    ->email(),
                Textarea::make('direccion')
                    ->columnSpanFull(),
                TextInput::make('ciudad'),
                TextInput::make('codigo_postal'),
                Select::make('provincia_id')
                    ->relationship('province', 'nombre')
                    ->searchable()
                    ->preload(),
                DatePicker::make('fecha_nacimiento'),
                TextInput::make('dni_nie'),
                TextInput::make('profesion'),
                TextInput::make('empresa'),
                Textarea::make('notas_contacto')
                    ->columnSpanFull(),
                Select::make('preferencia_comunicacion')
                    ->options(['email' => 'Email', 'telefono' => 'Telefono', 'whatsapp' => 'Whatsapp', 'sms' => 'Sms'])
                    ->required(),
            ]);
    }
}
