<x-filament-panels::page>
    <div class="space-y-6">
        <div class="text-sm text-gray-600 dark:text-gray-400">
            {{ __('Complete calendar view of all events. Click on any event to go to the related lead.') }}
        </div>
        
        {{ $this->getHeaderWidgetsForm() }}
        
        <x-filament-widgets::widgets
            :widgets="$this->getHeaderWidgets()"
            :columns="$this->getHeaderWidgetsColumns()"
        />
    </div>
</x-filament-panels::page>
