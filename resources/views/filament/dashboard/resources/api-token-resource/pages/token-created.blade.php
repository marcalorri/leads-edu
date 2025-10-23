<x-filament-panels::page>
<div class="bg-success-50 dark:bg-success-900/20 border border-success-200 dark:border-success-700 rounded-lg p-6">
    <div class="flex items-start gap-4">
        <div class="flex-shrink-0">
            <svg class="w-8 h-8 text-success-600 dark:text-success-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
        </div>
        
        <div class="flex-1">
            <h3 class="text-lg font-semibold text-success-900 dark:text-success-100 mb-2">
                {{ __('Token created successfully') }}
            </h3>
            
            <div class="bg-white dark:bg-gray-800 rounded-lg p-4 mb-4 border border-gray-200 dark:border-gray-700">
                <p class="text-sm text-gray-600 dark:text-gray-400 mb-2 font-medium">
                    ⚠️ {{ __('IMPORTANT: Copy this token now. For security reasons, it will not be shown again.') }}
                </p>
                
                <div class="flex items-center gap-2 bg-gray-50 dark:bg-gray-900 p-3 rounded border border-gray-300 dark:border-gray-600">
                    <code id="api-token" class="flex-1 text-sm font-mono text-gray-900 dark:text-gray-100 break-all select-all">
                        {{ $this->token }}
                    </code>
                    
                    <button 
                        type="button"
                        onclick="copyToken()"
                        class="flex-shrink-0 inline-flex items-center gap-2 px-4 py-2 bg-primary-600 hover:bg-primary-700 text-white rounded-lg transition-colors duration-200 font-medium text-sm"
                    >
                        <svg id="copy-icon" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"></path>
                        </svg>
                        <span id="copy-text">{{ __('Copy') }}</span>
                    </button>
                </div>
            </div>
            
            <div class="space-y-2 text-sm text-gray-700 dark:text-gray-300">
                <p class="font-medium">{{ __('How to use this token:') }}</p>
                <ul class="list-disc list-inside space-y-1 ml-2">
                    <li>{{ __('Add it to the Authorization header: Bearer {token}') }}</li>
                    <li>{{ __('Store it securely (password manager, environment variables)') }}</li>
                    <li>{{ __('Never share it publicly or commit it to version control') }}</li>
                </ul>
            </div>
            
            <div class="mt-4 pt-4 border-t border-success-200 dark:border-success-700">
                <a 
                    href="{{ route('filament.dashboard.resources.api-tokens.index', ['tenant' => filament()->getTenant()]) }}"
                    class="inline-flex items-center gap-2 text-sm font-medium text-primary-600 hover:text-primary-700 dark:text-primary-400 dark:hover:text-primary-300"
                >
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                    {{ __('Back to tokens list') }}
                </a>
            </div>
        </div>
    </div>
</div>

<script>
function copyToken() {
    const tokenElement = document.getElementById('api-token');
    const copyIcon = document.getElementById('copy-icon');
    const copyText = document.getElementById('copy-text');
    
    // Copiar al portapapeles
    navigator.clipboard.writeText(tokenElement.textContent.trim()).then(() => {
        // Cambiar el icono y texto temporalmente
        copyIcon.innerHTML = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>';
        copyText.textContent = '{{ __("Copied!") }}';
        
        // Volver al estado original después de 2 segundos
        setTimeout(() => {
            copyIcon.innerHTML = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"></path>';
            copyText.textContent = '{{ __("Copy") }}';
        }, 2000);
    }).catch(err => {
        console.error('Error copying token:', err);
        alert('{{ __("Error copying token. Please copy it manually.") }}');
    });
}
</script>
</x-filament-panels::page>
