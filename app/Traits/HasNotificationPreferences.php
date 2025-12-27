<?php

namespace App\Traits;

trait HasNotificationPreferences
{
    /**
     * Get the default notification preferences structure
     */
    public function getDefaultNotificationPreferences(): array
    {
        return [
            'new_lead' => [
                'email' => true,
                'database' => true,
            ],
            'follow_up_reminder' => [
                'email' => true,
                'database' => true,
            ],
            'lead_won' => [
                'email' => true,
                'database' => true,
            ],
        ];
    }

    /**
     * Get notification preferences with defaults
     */
    public function getNotificationPreferences(): array
    {
        if (empty($this->notification_preferences)) {
            return $this->getDefaultNotificationPreferences();
        }

        // Merge with defaults to ensure all keys exist
        return array_merge(
            $this->getDefaultNotificationPreferences(),
            is_array($this->notification_preferences) ? $this->notification_preferences : []
        );
    }

    /**
     * Check if user should receive a specific notification on a specific channel
     */
    public function shouldReceiveNotification(string $notificationType, string $channel = 'email'): bool
    {
        $preferences = $this->getNotificationPreferences();
        
        return $preferences[$notificationType][$channel] ?? true;
    }

    /**
     * Update notification preference for a specific type and channel
     */
    public function updateNotificationPreference(string $notificationType, string $channel, bool $enabled): void
    {
        $preferences = $this->getNotificationPreferences();
        
        if (!isset($preferences[$notificationType])) {
            $preferences[$notificationType] = [
                'email' => true,
                'database' => true,
            ];
        }
        
        $preferences[$notificationType][$channel] = $enabled;
        
        $this->update(['notification_preferences' => $preferences]);
    }

    /**
     * Get available notification channels for a notification type
     */
    public function getAvailableChannels(string $notificationType): array
    {
        $preferences = $this->getNotificationPreferences();
        
        return array_keys($preferences[$notificationType] ?? []);
    }
}
