<div class="flex items-center gap-2 px-3 py-1.5 rounded-lg text-sm {{ $isCritical ? 'bg-red-50 text-red-700 border border-red-200' : ($showWarning ? 'bg-amber-50 text-amber-700 border border-amber-200' : 'bg-gray-50 text-gray-700 border border-gray-200') }}">
    <x-heroicon-o-users class="h-4 w-4" />
    
    @if($isUnlimited)
        <span class="font-medium">
            {{ $currentCount }} {{ __('Leads') }}
        </span>
        <span class="text-xs opacity-75">
            ({{ __('Unlimited') }})
        </span>
    @else
        <span class="font-medium">
            {{ $currentCount }}/{{ $maxLeads }}
        </span>
        <span class="text-xs opacity-75">
            {{ __('Leads') }}
        </span>
        
        @if($showWarning)
            <x-heroicon-o-exclamation-triangle class="h-4 w-4 {{ $isCritical ? 'text-red-600' : 'text-amber-600' }}" />
        @endif
    @endif
    
    @if(!$isUnlimited)
        <div class="relative w-16 h-1.5 bg-gray-200 rounded-full overflow-hidden ml-1">
            <div 
                class="absolute top-0 left-0 h-full transition-all {{ $isCritical ? 'bg-red-500' : ($showWarning ? 'bg-amber-500' : 'bg-green-500') }}" 
                style="width: {{ min($percentage, 100) }}%">
            </div>
        </div>
    @endif
</div>
