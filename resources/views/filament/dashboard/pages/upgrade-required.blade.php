<x-filament-panels::page>
    <div class="space-y-6">
        <!-- Header con icono -->
        <div class="text-center">
            <div class="mx-auto flex h-16 w-16 items-center justify-center rounded-full bg-primary-100 dark:bg-primary-900">
                <x-heroicon-o-lock-closed class="h-8 w-8 text-primary-600 dark:text-primary-400" />
            </div>
            <h2 class="mt-4 text-2xl font-bold text-gray-900 dark:text-white">
                {{ __('CRM Subscription Required') }}
            </h2>
            <p class="mt-2 text-gray-600 dark:text-gray-400">
                {{ __('To access CRM features you need an active subscription') }}
            </p>
        </div>

        <!-- Características bloqueadas -->
        <div class="rounded-lg border border-gray-200 bg-white p-6 dark:border-gray-700 dark:bg-gray-800">
            <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">
                {{ __('Included CRM Features:') }}
            </h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div class="flex items-center space-x-3">
                    <x-heroicon-o-users class="h-5 w-5 text-primary-600" />
                    <span class="text-gray-700 dark:text-gray-300">{{ __('Lead Management') }}</span>
                </div>
                <div class="flex items-center space-x-3">
                    <x-heroicon-o-phone class="h-5 w-5 text-primary-600" />
                    <span class="text-gray-700 dark:text-gray-300">{{ __('Contact Management') }}</span>
                </div>
                <div class="flex items-center space-x-3">
                    <x-heroicon-o-academic-cap class="h-5 w-5 text-primary-600" />
                    <span class="text-gray-700 dark:text-gray-300">{{ __('Course Catalog') }}</span>
                </div>
                <div class="flex items-center space-x-3">
                    <x-heroicon-o-chat-bubble-left-right class="h-5 w-5 text-primary-600" />
                    <span class="text-gray-700 dark:text-gray-300">{{ __('Notes and Follow-up') }}</span>
                </div>
                <div class="flex items-center space-x-3">
                    <x-heroicon-o-calendar-days class="h-5 w-5 text-primary-600" />
                    <span class="text-gray-700 dark:text-gray-300">{{ __('Events and Reminders') }}</span>
                </div>
                <div class="flex items-center space-x-3">
                    <x-heroicon-o-chart-bar class="h-5 w-5 text-primary-600" />
                    <span class="text-gray-700 dark:text-gray-300">{{ __('Reports and Analytics') }}</span>
                </div>
                <div class="flex items-center space-x-3">
                    <x-heroicon-o-cog-6-tooth class="h-5 w-5 text-primary-600" />
                    <span class="text-gray-700 dark:text-gray-300">{{ __('Advanced Configuration') }}</span>
                </div>
                <div class="flex items-center space-x-3">
                    <x-heroicon-o-arrow-path class="h-5 w-5 text-primary-600" />
                    <span class="text-gray-700 dark:text-gray-300">{{ __('Automation') }}</span>
                </div>
            </div>
        </div>

        <!-- Información del tenant actual -->
        @if(filament()->getTenant())
        <div class="rounded-lg border border-amber-200 bg-amber-50 p-4 dark:border-amber-800 dark:bg-amber-900/20">
            <div class="flex items-center">
                <x-heroicon-o-information-circle class="h-5 w-5 text-amber-600 dark:text-amber-400" />
                <span class="ml-2 text-sm text-amber-700 dark:text-amber-300">
                    {{ __('Current workspace:') }} <strong>{{ filament()->getTenant()->name }}</strong>
                </span>
            </div>
        </div>
        @endif

        <!-- Call to action -->
        <div class="text-center space-y-4">
            <p class="text-gray-600 dark:text-gray-400">
                {{ __('Ready to boost your educational management?') }}
            </p>
            
            <!-- Botones de acción se muestran en el header -->
        </div>

        <!-- Información adicional -->
        <div class="rounded-lg bg-gray-50 p-4 dark:bg-gray-800/50">
            <h4 class="font-medium text-gray-900 dark:text-white mb-2">
                {{ __('Need help?') }}
            </h4>
            <p class="text-sm text-gray-600 dark:text-gray-400">
                {{ __('If you have questions about our plans or need assistance, do not hesitate to contact our support team.') }}
            </p>
        </div>
    </div>
</x-filament-panels::page>
