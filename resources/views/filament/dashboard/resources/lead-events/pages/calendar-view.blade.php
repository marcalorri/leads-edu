<x-filament-panels::page>
    <div class="space-y-6">
        <div class="text-sm text-gray-600 dark:text-gray-400">
            Vista de calendario completa de todos los eventos. Haz clic en cualquier evento para ir al lead relacionado.
        </div>
        
        {{ $this->getHeaderWidgetsForm() }}
        
        <x-filament-widgets::widgets
            :widgets="$this->getHeaderWidgets()"
            :columns="$this->getHeaderWidgetsColumns()"
        />
    </div>
</x-filament-panels::page>
