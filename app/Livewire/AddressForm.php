<?php

namespace App\Livewire;

use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Schemas\Schema;
use Jeffgreco13\FilamentBreezy\Livewire\MyProfileComponent;
use Parfaitementweb\FilamentCountryField\Forms\Components\Country;

class AddressForm extends MyProfileComponent
{
    protected string $view = 'livewire.address-form';

    public array $data;

    public function mount(): void
    {
        $user = auth()->user();
        $address = $user->address()->first();

        if ($address) {
            $this->form->fill([
                'address_line_1' => $address->address_line_1,
                'address_line_2' => $address->address_line_2,
                'city' => $address->city,
                'state' => $address->state,
                'zip' => $address->zip,
                'country_code' => $address->country_code,
                'phone' => $address->phone,
                'tax_number' => $address->tax_number,
            ]);
        }
    }

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('address_line_1')
                    ->label(__('Dirección Línea 1'))
                    ->helperText(__('Dirección, nombre de empresa, c/o'))
                    ->columnSpanFull(),
                TextInput::make('address_line_2')
                    ->label(__('Dirección Línea 2'))
                    ->helperText(__('Apartamento, suite, unidad, edificio, piso, etc.'))
                    ->columnSpanFull(),
                TextInput::make('city')
                    ->label(__('Ciudad')),
                TextInput::make('state')
                    ->label(__('Provincia/Estado')),
                TextInput::make('zip')
                    ->label(__('Código Postal')),
                Country::make('country_code')
                    ->label(__('País')),
                TextInput::make('phone')
                    ->label(__('Teléfono'))
                    ->tel(),
                TextInput::make('tax_number')
                    ->label(__('Número de Identificación Fiscal')),
            ])
            ->columns(2)
            ->statePath('data');
    }

    public function submit()
    {
        $data = $this->form->getState();

        $user = auth()->user();

        $address = $user->address()->first();

        if ($address) {
            $address->update($data);
        } else {
            $user->address()->create($data);
        }

        Notification::make()
            ->title(__('Address Saved'))
            ->success()
            ->send();
    }
}
