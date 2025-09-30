<div class="mt-8">
    <div class="bg-white rounded-lg shadow border border-gray-200 p-6">
        <div class="flex items-center space-x-2 mb-6">
            <svg class="w-5 h-5 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
            </svg>
            <h2 class="text-lg font-semibold text-gray-900">Documentación de la API</h2>
        </div>

        <div class="space-y-6">
            <!-- URL Base -->
            <div>
                <h3 class="text-sm font-medium text-gray-900 mb-2">URL Base</h3>
                <div class="bg-gray-50 rounded-lg p-3">
                    <code class="text-sm text-gray-800">{{ $baseUrl }}</code>
                </div>
            </div>

            <!-- Autenticación -->
            <div>
                <h3 class="text-sm font-medium text-gray-900 mb-2">Autenticación</h3>
                <p class="text-sm text-gray-600 mb-2">
                    Incluye tu token API en el header Authorization:
                </p>
                <div class="bg-gray-900 rounded-lg p-3">
                    <code class="text-sm text-green-400">Authorization: Bearer tu_token_aqui</code>
                </div>
            </div>

            <!-- Endpoints -->
            <div>
                <h3 class="text-sm font-medium text-gray-900 mb-3">Endpoints Disponibles</h3>
                <div class="space-y-2">
                    @foreach($endpoints as $endpoint)
                        <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                            <div class="flex items-center space-x-3">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                    {{ $endpoint['method'] === 'GET' ? 'bg-blue-100 text-blue-800' : '' }}
                                    {{ $endpoint['method'] === 'POST' ? 'bg-green-100 text-green-800' : '' }}
                                    {{ $endpoint['method'] === 'PUT' ? 'bg-yellow-100 text-yellow-800' : '' }}
                                    {{ $endpoint['method'] === 'DELETE' ? 'bg-red-100 text-red-800' : '' }}">
                                    {{ $endpoint['method'] }}
                                </span>
                                <code class="text-sm font-mono text-gray-800">{{ $endpoint['path'] }}</code>
                            </div>
                            <div class="flex items-center space-x-2">
                                <span class="text-sm text-gray-600">{{ $endpoint['description'] }}</span>
                                <span class="inline-flex items-center px-2 py-1 rounded text-xs font-medium bg-gray-100 text-gray-800">
                                    {{ $endpoint['scope'] }}
                                </span>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            <!-- Ejemplo de uso -->
            <div>
                <h3 class="text-sm font-medium text-gray-900 mb-3">Ejemplo de Uso</h3>
                <div class="bg-gray-900 rounded-lg p-3 overflow-x-auto">
                    <pre class="text-xs text-green-400"><code>curl -X GET "{{ $baseUrl }}/leads" \
  -H "Authorization: Bearer tu_token_aqui" \
  -H "Accept: application/json"</code></pre>
                </div>
            </div>

            <!-- Nota importante -->
            <div class="bg-amber-50 border border-amber-200 rounded-lg p-4">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <svg class="w-5 h-5 text-amber-400" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                        </svg>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm text-amber-800">
                            <strong>Importante:</strong> Copia tus tokens desde la tabla de arriba. Solo se muestran completos una vez al crearlos.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
