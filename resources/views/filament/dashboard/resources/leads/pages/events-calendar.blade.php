<x-filament-panels::page>
    <div class="space-y-6">
        <div class="text-sm text-gray-600 dark:text-gray-400">
            Vista de calendario de todos los eventos de leads. Utiliza los filtros de fecha del dashboard para ajustar el período mostrado.
        </div>
        
        {{ $this->getHeaderWidgetsForm() }}
        
        <x-filament-widgets::widgets
            :widgets="$this->getHeaderWidgets()"
            :columns="$this->getHeaderWidgetsColumns()"
        />
    </div>
</x-filament-panels::page>
