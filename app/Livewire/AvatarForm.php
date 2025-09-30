<?php

namespace App\Livewire;

use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Schemas\Schema;
use Jeffgreco13\FilamentBreezy\Livewire\MyProfileComponent;

class AvatarForm extends MyProfileComponent
{
    protected string $view = 'livewire.avatar-form';

    public array $data;

    public function mount(): void
    {
        $user = auth()->user();
        
        $this->form->fill([
            'avatar' => $user->avatar,
            'name' => $user->name,
            'public_name' => $user->public_name,
            'phone_number' => $user->phone_number,
        ]);
    }

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                FileUpload::make('avatar')
                    ->label('Avatar')
                    ->image()
                    ->avatar()
                    ->disk('public')
                    ->directory('avatars')
                    ->visibility('public')
                    ->columnSpanFull()
                    ->helperText('Sube una imagen para tu avatar. Si no subes ninguna, se mostrarán tus iniciales.'),
                
                TextInput::make('name')
                    ->label('Nombre Completo')
                    ->required()
                    ->maxLength(255),
                
                TextInput::make('public_name')
                    ->label('Nombre Público')
                    ->maxLength(255)
                    ->helperText('Nombre que se mostrará públicamente (opcional)'),
                
                TextInput::make('phone_number')
                    ->label('Teléfono')
                    ->tel()
                    ->maxLength(20),
            ])
            ->columns(2)
            ->statePath('data');
    }

    public function submit()
    {
        $data = $this->form->getState();
        $user = auth()->user();
        
        $user->update($data);

        Notification::make()
            ->title('Perfil Actualizado')
            ->body('Tu información personal ha sido actualizada correctamente.')
            ->success()
            ->send();
    }
}
