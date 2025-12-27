<?php

namespace App\Livewire;

use Filament\Forms\Components\Checkbox;
use Filament\Notifications\Notification;
use Filament\Schemas\Schema;
use Jeffgreco13\FilamentBreezy\Livewire\MyProfileComponent;

class NotificationPreferencesForm extends MyProfileComponent
{
    protected string $view = 'livewire.notification-preferences-form';

    public array $data = [];

    public function mount(): void
    {
        $user = auth()->user();
        
        $this->form->fill([
            'new_lead_email' => $user->shouldReceiveNotification('new_lead', 'email'),
            'new_lead_database' => $user->shouldReceiveNotification('new_lead', 'database'),
            'follow_up_reminder_email' => $user->shouldReceiveNotification('follow_up_reminder', 'email'),
            'follow_up_reminder_database' => $user->shouldReceiveNotification('follow_up_reminder', 'database'),
            'lead_won_email' => $user->shouldReceiveNotification('lead_won', 'email'),
            'lead_won_database' => $user->shouldReceiveNotification('lead_won', 'database'),
        ]);
    }

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Checkbox::make('new_lead_email')
                    ->label(__('New Lead - Email Notification'))
                    ->helperText(__('Receive an email when a new lead is assigned to you'))
                    ->inline(true)
                    ->columnSpanFull(),
                Checkbox::make('new_lead_database')
                    ->label(__('New Lead - In-App Notification'))
                    ->helperText(__('Receive an in-app notification when a new lead is assigned to you'))
                    ->inline(true)
                    ->columnSpanFull(),
                
                Checkbox::make('follow_up_reminder_email')
                    ->label(__('Follow-up Reminder - Email Notification'))
                    ->helperText(__('Receive an email for follow-up reminders'))
                    ->inline(true)
                    ->columnSpanFull(),
                Checkbox::make('follow_up_reminder_database')
                    ->label(__('Follow-up Reminder - In-App Notification'))
                    ->helperText(__('Receive an in-app notification for follow-up reminders'))
                    ->inline(true)
                    ->columnSpanFull(),
                
                Checkbox::make('lead_won_email')
                    ->label(__('Lead Converted - Email Notification'))
                    ->helperText(__('Receive an email when you convert a lead'))
                    ->inline(true)
                    ->columnSpanFull(),
                Checkbox::make('lead_won_database')
                    ->label(__('Lead Converted - In-App Notification'))
                    ->helperText(__('Receive an in-app notification when you convert a lead'))
                    ->inline(true)
                    ->columnSpanFull(),
            ])
            ->columns(1)
            ->statePath('data');
    }

    public function submit()
    {
        $data = $this->form->getState();
        $user = auth()->user();

        $preferences = [
            'new_lead' => [
                'email' => $data['new_lead_email'] ?? false,
                'database' => $data['new_lead_database'] ?? false,
            ],
            'follow_up_reminder' => [
                'email' => $data['follow_up_reminder_email'] ?? false,
                'database' => $data['follow_up_reminder_database'] ?? false,
            ],
            'lead_won' => [
                'email' => $data['lead_won_email'] ?? false,
                'database' => $data['lead_won_database'] ?? false,
            ],
        ];

        $user->update([
            'notification_preferences' => $preferences,
        ]);

        Notification::make()
            ->success()
            ->title(__('Notification preferences updated'))
            ->body(__('Your notification preferences have been saved successfully.'))
            ->send();
    }
}
