<div class="w-full max-w-none">
    <div class="bg-white rounded-xl shadow-sm ring-1 ring-gray-950/5 dark:bg-gray-900 dark:ring-white/10">
        <div class="p-6 border-b border-gray-200 dark:border-gray-700">
            <div class="flex items-center space-x-3">
                <div class="flex-shrink-0">
                    <div class="w-10 h-10 bg-primary-500 rounded-lg flex items-center justify-center">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                        </svg>
                    </div>
                </div>
                <div>
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white">
                        {{ __('Personal Information') }}
                    </h3>
                    <p class="text-sm text-gray-500 dark:text-gray-400">
                        {{ __('Update your avatar and personal information.') }}
                    </p>
                </div>
            </div>
        </div>
        
        <div class="p-6">
            <form wire:submit="submit" class="space-y-6">
                {{ $this->form }}
                
                <div class="flex justify-end pt-4 border-t border-gray-200 dark:border-gray-700">
                    <x-filament::button type="submit" wire:loading.attr="disabled" size="lg">
                        <x-slot name="icon">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                        </x-slot>
                        {{ __('Save Changes') }}
                    </x-filament::button>
                </div>
            </form>
        </div>
    </div>
</div>
