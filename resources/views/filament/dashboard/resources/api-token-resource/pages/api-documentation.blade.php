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
                    Todas las peticiones requieren un Bearer Token en el header Authorization:
                </p>
                <div class="bg-gray-900 rounded-lg p-3">
                    <code class="text-sm text-green-400">{{ $examples['authentication']['code'] }}</code>
                </div>
            </div>

            <!-- Scopes -->
            <div>
                <h3 class="text-sm font-medium text-gray-900 mb-3">Scopes Disponibles</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                    @foreach($scopes as $scope => $description)
                        <div class="flex items-start space-x-2 p-3 bg-gray-50 rounded-lg">
                            <svg class="w-5 h-5 text-primary-600 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                            </svg>
                            <div>
                                <code class="text-sm font-medium text-gray-900">{{ $scope }}</code>
                                <p class="text-xs text-gray-600 mt-1">{{ $description }}</p>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            <!-- Endpoints por Grupo -->
            @foreach($endpointGroups as $groupName => $endpoints)
                <div>
                    <h3 class="text-sm font-medium text-gray-900 mb-3">{{ $groupName }}</h3>
                    <div class="space-y-2">
                        @foreach($endpoints as $endpoint)
                            <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg hover:bg-gray-100 transition">
                                <div class="flex items-center space-x-3 flex-1">
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
                                    <span class="inline-flex items-center px-2 py-1 rounded text-xs font-medium bg-primary-100 text-primary-800">
                                        {{ $endpoint['scope'] }}
                                    </span>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endforeach

            <!-- Ejemplos de Uso -->
            <div>
                <h3 class="text-sm font-medium text-gray-900 mb-3">Ejemplos de Uso</h3>
                <div class="space-y-4">
                    <!-- Ejemplo 1: Listar Leads -->
                    <div>
                        <h4 class="text-xs font-medium text-gray-700 mb-2">{{ $examples['list_leads']['title'] }}</h4>
                        <div class="bg-gray-900 rounded-lg p-3 overflow-x-auto">
                            <pre class="text-xs text-green-400"><code>curl -X {{ $examples['list_leads']['method'] }} "{{ $examples['list_leads']['url'] }}" \
  -H "Authorization: Bearer tu_token_aqui" \
  -H "Accept: application/json"</code></pre>
                        </div>
                    </div>

                    <!-- Ejemplo 2: Crear Lead -->
                    <div>
                        <h4 class="text-xs font-medium text-gray-700 mb-2">{{ $examples['create_lead']['title'] }}</h4>
                        <div class="bg-gray-900 rounded-lg p-3 overflow-x-auto">
                            <pre class="text-xs text-green-400"><code>curl -X {{ $examples['create_lead']['method'] }} "{{ $examples['create_lead']['url'] }}" \
  -H "Authorization: Bearer tu_token_aqui" \
  -H "Content-Type: application/json" \
  -d '{!! json_encode($examples['create_lead']['body'], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) !!}'</code></pre>
                        </div>
                    </div>

                    <!-- Ejemplo 3: Crear Sede -->
                    <div>
                        <h4 class="text-xs font-medium text-gray-700 mb-2">{{ $examples['create_campus']['title'] }}</h4>
                        <div class="bg-gray-900 rounded-lg p-3 overflow-x-auto">
                            <pre class="text-xs text-green-400"><code>curl -X {{ $examples['create_campus']['method'] }} "{{ $examples['create_campus']['url'] }}" \
  -H "Authorization: Bearer tu_token_aqui" \
  -H "Content-Type: application/json" \
  -d '{!! json_encode($examples['create_campus']['body'], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) !!}'</code></pre>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Rate Limiting -->
            <div>
                <h3 class="text-sm font-medium text-gray-900 mb-3">Rate Limiting</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                    <div class="p-3 bg-blue-50 border border-blue-200 rounded-lg">
                        <div class="flex items-center space-x-2">
                            <svg class="w-5 h-5 text-blue-600" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"></path>
                            </svg>
                            <div>
                                <p class="text-xs font-medium text-blue-900">Por Token</p>
                                <p class="text-sm font-semibold text-blue-700">{{ $rateLimit['perToken'] }}</p>
                            </div>
                        </div>
                    </div>
                    <div class="p-3 bg-green-50 border border-green-200 rounded-lg">
                        <div class="flex items-center space-x-2">
                            <svg class="w-5 h-5 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M13 6a3 3 0 11-6 0 3 3 0 016 0zM18 8a2 2 0 11-4 0 2 2 0 014 0zM14 15a4 4 0 00-8 0v3h8v-3zM6 8a2 2 0 11-4 0 2 2 0 014 0zM16 18v-3a5.972 5.972 0 00-.75-2.906A3.005 3.005 0 0119 15v3h-3zM4.75 12.094A5.973 5.973 0 004 15v3H1v-3a3 3 0 013.75-2.906z"></path>
                            </svg>
                            <div>
                                <p class="text-xs font-medium text-green-900">Por Tenant</p>
                                <p class="text-sm font-semibold text-green-700">{{ $rateLimit['perTenant'] }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Notas Importantes -->
            <div class="space-y-3">
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

                <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <svg class="w-5 h-5 text-blue-400" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path>
                            </svg>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm text-blue-800">
                                <strong>Documentación completa:</strong> Consulta el archivo <code class="bg-blue-100 px-1 rounded">docs/API_REFERENCE.md</code> para más detalles sobre validaciones, códigos de error y ejemplos avanzados.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
