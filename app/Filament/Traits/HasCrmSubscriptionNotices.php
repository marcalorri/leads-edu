<?php

namespace App\Filament\Traits;

use App\Support\CrmSubscription;
use Filament\Actions\Action;
use Filament\Actions\CreateAction;
use Filament\Actions\EditAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\BulkAction;
use Filament\Actions\ActionGroup;
use Filament\Notifications\Notification;

trait HasCrmSubscriptionNotices
{
    /**
     * Get CRM upgrade action for modals
     */
    protected static function getCrmUpgradeAction(): Action
    {
        return Action::make('crm-upgrade')
            ->label(__('Upgrade Plan'))
            ->icon('heroicon-o-rocket-launch')
            ->color('warning')
            ->modalHeading(__('Limited CRM Features'))
            ->modalDescription(__('To access all CRM features, you need an active subscription.'))
            ->modalSubmitActionLabel(__('View Plans'))
            ->modalCancelActionLabel(__('Cancel'))
            ->action(function () {
                return redirect()->to(CrmSubscription::getUpgradeUrl());
            });
    }

    /**
     * Add CRM subscription notice to header actions
     */
    protected function addCrmNoticeToHeaderActions(array $actions): array
    {
        if (CrmSubscription::isInactive()) {
            // Deshabilitar acciones de creación y edición
            foreach ($actions as $key => $action) {
                if ($action instanceof CreateAction || $action instanceof EditAction) {
                    $actions[$key] = $action
                        ->disabled()
                        ->tooltip(__('Requires active CRM subscription'));
                }
            }

            // Añadir acción de upgrade al principio
            array_unshift($actions, static::getCrmUpgradeAction());
        }

        return $actions;
    }

    /**
     * Add CRM subscription notice to table actions
     */
    protected function addCrmNoticeToTableActions(array $actions): array
    {
        if (CrmSubscription::isInactive()) {
            foreach ($actions as $key => $action) {
                if ($action instanceof EditAction || $action instanceof DeleteAction) {
                    $actions[$key] = $action
                        ->disabled()
                        ->tooltip(__('Requires active CRM subscription'));
                } elseif ($action instanceof ActionGroup) {
                    // Para ActionGroups, deshabilitar acciones internas
                    $groupActions = $action->getActions();
                    foreach ($groupActions as $groupKey => $groupAction) {
                        if ($groupAction instanceof EditAction || $groupAction instanceof DeleteAction) {
                            $groupActions[$groupKey] = $groupAction
                                ->disabled()
                                ->tooltip(__('Requires active CRM subscription'));
                        }
                    }
                    $actions[$key] = $action->actions($groupActions);
                }
            }
        }

        return $actions;
    }

    /**
     * Add CRM subscription notice to bulk actions
     */
    protected function addCrmNoticeToBulkActions(array $actions): array
    {
        if (CrmSubscription::isInactive()) {
            foreach ($actions as $key => $action) {
                if ($action instanceof BulkAction) {
                    $actions[$key] = $action
                        ->disabled()
                        ->tooltip(__('Requires active CRM subscription'));
                }
            }
        }

        return $actions;
    }

    /**
     * Show CRM upgrade notification
     */
    protected function showCrmUpgradeNotification(): void
    {
        if (CrmSubscription::isInactive()) {
            Notification::make()
                ->warning()
                ->title(__('Limited CRM Features'))
                ->body(CrmSubscription::getStatusMessage())
                ->actions([
                    \Filament\Notifications\Actions\Action::make('upgrade')
                        ->label(__('View Plans'))
                        ->url(CrmSubscription::getUpgradeUrl())
                        ->button(),
                ])
                ->persistent()
                ->send();
        }
    }

    /**
     * Get empty state for CRM resources when subscription is inactive
     */
    protected function getCrmEmptyState(): array
    {
        if (CrmSubscription::isInactive()) {
            return [
                'heading' => __('Limited CRM Features'),
                'description' => CrmSubscription::getStatusMessage(),
                'icon' => 'heroicon-o-lock-closed',
                'actions' => [
                    Action::make('upgrade')
                        ->label(__('View Subscription Plans'))
                        ->icon('heroicon-o-rocket-launch')
                        ->color('primary')
                        ->url(CrmSubscription::getUpgradeUrl()),
                ],
            ];
        }

        return [];
    }
}
