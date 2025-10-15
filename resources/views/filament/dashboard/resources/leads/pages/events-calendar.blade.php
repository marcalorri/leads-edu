<x-filament-panels::page>
    <div class="space-y-6">
        <div class="text-sm text-gray-600 dark:text-gray-400">
            {{ __('Calendar view of all lead events. Use the dashboard date filters to adjust the displayed period.') }}
        </div>
        
        {{ $this->getHeaderWidgetsForm() }}
        
        <x-filament-widgets::widgets
            :widgets="$this->getHeaderWidgets()"
            :columns="$this->getHeaderWidgetsColumns()"
        />
    </div>
</x-filament-panels::page>
