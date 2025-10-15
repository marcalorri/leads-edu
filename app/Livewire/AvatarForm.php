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
                    ->label(__('Avatar'))
                    ->image()
                    ->avatar()
                    ->disk('public')
                    ->directory('avatars')
                    ->visibility('public')
                    ->columnSpanFull()
                    ->helperText(__('Upload an image for your avatar. If you don\'t upload one, your initials will be displayed.')),
                
                TextInput::make('name')
                    ->label(__('Full Name'))
                    ->required()
                    ->maxLength(255),
                
                TextInput::make('public_name')
                    ->label(__('Public Name'))
                    ->maxLength(255)
                    ->helperText(__('Name that will be displayed publicly (optional)')),
                
                TextInput::make('phone_number')
                    ->label(__('Phone'))
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
            ->title(__('Profile Updated'))
            ->body(__('Your personal information has been updated successfully.'))
            ->success()
            ->send();
    }
}
