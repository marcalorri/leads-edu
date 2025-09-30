<x-filament-panels::page>
    <div class="space-y-6">
        <!-- Header -->
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center space-x-3">
                <div class="flex-shrink-0">
                    <svg class="h-8 w-8 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"></path>
                    </svg>
                </div>
                <div>
                    <h2 class="text-xl font-semibold text-gray-900">API Tokens</h2>
                    <p class="text-sm text-gray-600">Gestiona los tokens de acceso a la API REST</p>
                </div>
            </div>
        </div>

        <!-- API Status -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div class="bg-green-50 border border-green-200 rounded-lg p-4">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <svg class="w-5 h-5 text-green-400" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                        </svg>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm font-medium text-green-800">API Activa</p>
                        <p class="text-xs text-green-600">{{ $baseUrl }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <svg class="w-5 h-5 text-blue-400" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M18 8a6 6 0 01-7.743 5.743L10 14l-1 1-1 1H6v2H2v-4l4.257-4.257A6 6 0 1118 8zm-6-4a1 1 0 100 2 2 2 0 012 2 1 1 0 102 0 4 4 0 00-4-4z" clip-rule="evenodd"></path>
                        </svg>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm font-medium text-blue-800">{{ $tokens->count() }} Tokens</p>
                        <p class="text-xs text-blue-600">Creados en total</p>
                    </div>
                </div>
            </div>

            <div class="bg-purple-50 border border-purple-200 rounded-lg p-4">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <svg class="w-5 h-5 text-purple-400" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M3 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm0 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm0 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm0 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1z" clip-rule="evenodd"></path>
                        </svg>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm font-medium text-purple-800">6 Endpoints</p>
                        <p class="text-xs text-purple-600">CRUD completo</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Existing Tokens -->
        @if($tokens->count() > 0)
        <div class="bg-white rounded-lg shadow">
            <div class="px-6 py-4 border-b border-gray-200">
                <h3 class="text-lg font-medium text-gray-900">Tokens Existentes</h3>
            </div>
            <div class="divide-y divide-gray-200">
                @foreach($tokens as $token)
                <div class="px-6 py-4">
                    <div class="flex items-center justify-between">
                        <div class="flex-1">
                            <div class="flex items-center space-x-3">
                                <h4 class="text-sm font-medium text-gray-900">{{ $token->name }}</h4>
                                @if($token->isActive())
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                        Activo
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                        Expirado
                                    </span>
                                @endif
                            </div>
                            <div class="mt-1 flex items-center space-x-4 text-sm text-gray-500">
                                <span>Usuario: {{ $token->tokenable->name }}</span>
                                <span>Creado: {{ $token->created_at->format('d/m/Y H:i') }}</span>
                                @if($token->last_used_at)
                                    <span>Último uso: {{ $token->last_used_at->format('d/m/Y H:i') }}</span>
                                @else
                                    <span>Nunca usado</span>
                                @endif
                            </div>
                            @if($token->abilities)
                                <div class="mt-2 flex flex-wrap gap-1">
                                    @foreach($token->abilities as $ability)
                                        <span class="inline-flex items-center px-2 py-1 rounded text-xs font-medium bg-gray-100 text-gray-800">
                                            {{ $ability }}
                                        </span>
                                    @endforeach
                                </div>
                            @endif
                        </div>
                        <div class="flex-shrink-0">
                            <form method="POST" action="#" onsubmit="return confirm('¿Estás seguro de que quieres eliminar este token?')">
                                @csrf
                                @method('DELETE')
                                <button type="button" onclick="if(confirm('¿Eliminar token?')) { fetch('/api/tokens/{{ $token->id }}', {method: 'DELETE', headers: {'X-CSRF-TOKEN': '{{ csrf_token() }}'}}).then(() => location.reload()); }" class="text-red-600 hover:text-red-900 text-sm font-medium">
                                    Eliminar
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
        @endif

        <!-- Create New Token -->
        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-lg font-medium text-gray-900 mb-4">Crear Nuevo Token</h3>
            <div class="bg-amber-50 border border-amber-200 rounded-lg p-4 mb-4">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <svg class="w-5 h-5 text-amber-400" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                        </svg>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm text-amber-800">
                            <strong>Crear token manualmente:</strong> Por el momento, los tokens deben crearse usando Laravel Tinker.
                        </p>
                    </div>
                </div>
            </div>

            <div class="space-y-4">
                <h4 class="text-sm font-medium text-gray-900">Pasos para crear un token:</h4>
                @foreach($createTokenInstructions as $step => $instruction)
                <div class="flex items-start space-x-3">
                    <div class="flex-shrink-0">
                        <span class="inline-flex items-center justify-center w-6 h-6 rounded-full bg-primary-100 text-primary-600 text-xs font-medium">
                            {{ substr($step, -1) }}
                        </span>
                    </div>
                    <div class="flex-1">
                        <code class="text-sm bg-gray-100 px-2 py-1 rounded">{{ $instruction }}</code>
                    </div>
                </div>
                @endforeach
            </div>
        </div>

        <!-- API Endpoints -->
        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-lg font-medium text-gray-900 mb-4">Endpoints Disponibles</h3>
            <div class="space-y-3">
                <div class="flex items-center space-x-3 p-3 border border-gray-200 rounded-lg">
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">GET</span>
                    <code class="text-sm font-mono">{{ $baseUrl }}/leads</code>
                    <span class="text-sm text-gray-600">Listar leads</span>
                </div>
                <div class="flex items-center space-x-3 p-3 border border-gray-200 rounded-lg">
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">POST</span>
                    <code class="text-sm font-mono">{{ $baseUrl }}/leads</code>
                    <span class="text-sm text-gray-600">Crear lead</span>
                </div>
                <div class="flex items-center space-x-3 p-3 border border-gray-200 rounded-lg">
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">GET</span>
                    <code class="text-sm font-mono">{{ $baseUrl }}/leads/{id}</code>
                    <span class="text-sm text-gray-600">Ver lead</span>
                </div>
                <div class="flex items-center space-x-3 p-3 border border-gray-200 rounded-lg">
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">PUT</span>
                    <code class="text-sm font-mono">{{ $baseUrl }}/leads/{id}</code>
                    <span class="text-sm text-gray-600">Actualizar lead</span>
                </div>
                <div class="flex items-center space-x-3 p-3 border border-gray-200 rounded-lg">
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">DELETE</span>
                    <code class="text-sm font-mono">{{ $baseUrl }}/leads/{id}</code>
                    <span class="text-sm text-gray-600">Eliminar lead</span>
                </div>
                <div class="flex items-center space-x-3 p-3 border border-gray-200 rounded-lg">
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">GET</span>
                    <code class="text-sm font-mono">{{ $baseUrl }}/leads/filters</code>
                    <span class="text-sm text-gray-600">Filtros disponibles</span>
                </div>
            </div>
        </div>

        <!-- Usage Example -->
        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-lg font-medium text-gray-900 mb-4">Ejemplo de Uso</h3>
            <div class="bg-gray-900 rounded-lg p-4 overflow-x-auto">
                <pre class="text-sm text-green-400"><code>curl -X GET "{{ $baseUrl }}/leads" \
  -H "Authorization: Bearer tu_token_aqui" \
  -H "Accept: application/json"</code></pre>
            </div>
        </div>
    </div>
</x-filament-panels::page>
