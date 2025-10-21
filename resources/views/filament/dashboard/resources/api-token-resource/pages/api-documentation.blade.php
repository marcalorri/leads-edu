<div class="mt-8">
    <div class="bg-white rounded-lg shadow border border-gray-200 p-6">
        <div class="flex items-center space-x-2 mb-6">
            <svg class="w-5 h-5 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
            </svg>
            <h2 class="text-lg font-semibold text-gray-900">{{ __('API Documentation') }}</h2>
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
                <h3 class="text-sm font-medium text-gray-900 mb-2">{{ __('Authentication') }}</h3>
                <p class="text-sm text-gray-600 mb-2">
                    {{ __('All requests require a Bearer Token in the Authorization header:') }}
                </p>
                <div class="bg-gray-900 rounded-lg p-3">
                    <code class="text-sm text-green-400">{{ $examples['authentication']['code'] }}</code>
                </div>
            </div>

            <!-- Scopes -->
            <div>
                <h3 class="text-sm font-medium text-gray-900 mb-3">{{ __('Available Scopes') }}</h3>
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

            <!-- Smart Field Resolution -->
            <div class="bg-green-50 border border-green-200 rounded-lg p-4 mb-6">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <svg class="w-5 h-5 text-green-400" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                        </svg>
                    </div>
                    <div class="ml-3">
                        <h3 class="text-sm font-medium text-green-800">{{ __('Smart Field Resolution') }}</h3>
                        <div class="mt-2 text-sm text-green-700">
                            <p>{{ __('You can use names or codes instead of IDs for related fields:') }}</p>
                            <ul class="list-disc list-inside mt-2 space-y-1">
                                <li><code class="bg-green-100 px-1 rounded">curso_id</code>: {{ __('Course code (PROG001) or title (Web Development)') }}</li>
                                <li><code class="bg-green-100 px-1 rounded">sede_id</code>: {{ __('Campus name (Main Campus)') }}</li>
                                <li><code class="bg-green-100 px-1 rounded">modalidad_id</code>: {{ __('Modality name (Online, Presencial)') }}</li>
                                <li><code class="bg-green-100 px-1 rounded">provincia_id</code>: {{ __('Province name (Madrid, Barcelona)') }}</li>
                                <li><code class="bg-green-100 px-1 rounded">asesor_id</code>: {{ __('Advisor email or name') }}</li>
                                <li><code class="bg-green-100 px-1 rounded">fase_venta_id</code>: {{ __('Sales phase name') }}</li>
                                <li><code class="bg-green-100 px-1 rounded">origen_id</code>: {{ __('Origin name (Web, Phone)') }}</li>
                            </ul>
                            <p class="mt-2">{{ __('The API will automatically resolve these to the correct IDs.') }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Available Filters -->
            <div>
                <h3 class="text-sm font-medium text-gray-900 mb-3">{{ __('Available Filters') }}</h3>
                
                <!-- Lead Filters -->
                <div class="mb-4">
                    <h4 class="text-xs font-semibold text-gray-700 mb-2">{{ __('Lead Filters') }} (GET /api/v1/leads)</h4>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-2">
                        @foreach($filters['leads'] as $param => $description)
                            <div class="flex items-start space-x-2 p-2 bg-gray-50 rounded">
                                <code class="text-xs font-medium text-primary-700">{{ $param }}</code>
                                <span class="text-xs text-gray-600">- {{ $description }}</span>
                            </div>
                        @endforeach
                    </div>
                </div>

                <!-- Catalog Filters -->
                <div>
                    <h4 class="text-xs font-semibold text-gray-700 mb-2">{{ __('Catalog Filters') }} (GET /api/v1/catalogs/*)</h4>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-2">
                        @foreach($filters['catalogs'] as $param => $description)
                            <div class="flex items-start space-x-2 p-2 bg-gray-50 rounded">
                                <code class="text-xs font-medium text-primary-700">{{ $param }}</code>
                                <span class="text-xs text-gray-600">- {{ $description }}</span>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

            <!-- Ejemplos de Uso -->
            <div>
                <h3 class="text-sm font-medium text-gray-900 mb-3">{{ __('Usage Examples') }}</h3>
                
                <!-- LEADS Examples -->
                <div class="mb-6">
                    <h4 class="text-xs font-semibold text-gray-800 mb-3 flex items-center">
                        <svg class="w-4 h-4 mr-2 text-blue-600" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M9 6a3 3 0 11-6 0 3 3 0 016 0zM17 6a3 3 0 11-6 0 3 3 0 016 0zM12.93 17c.046-.327.07-.66.07-1a6.97 6.97 0 00-1.5-4.33A5 5 0 0119 16v1h-6.07zM6 11a5 5 0 015 5v1H1v-1a5 5 0 015-5z"></path>
                        </svg>
                        {{ __('Lead Management Examples') }}
                    </h4>
                    <div class="space-y-3">
                        @foreach(['list_leads', 'list_leads_filtered', 'list_leads_search', 'list_leads_date_range', 'get_lead', 'create_lead', 'create_lead_complete', 'create_lead_with_names', 'update_lead'] as $exampleKey)
                            @if(isset($examples[$exampleKey]))
                                <div>
                                    <h5 class="text-xs font-medium text-gray-700 mb-1">{{ $examples[$exampleKey]['title'] }}</h5>
                                    <div class="bg-gray-900 rounded-lg p-3 overflow-x-auto">
                                        @if($examples[$exampleKey]['method'] === 'GET')
                                            <pre class="text-xs text-green-400"><code>curl -X {{ $examples[$exampleKey]['method'] }} "{{ $examples[$exampleKey]['url'] }}" \
  -H "Authorization: Bearer tu_token_aqui" \
  -H "Accept: application/json"</code></pre>
                                        @else
                                            <pre class="text-xs text-green-400"><code>curl -X {{ $examples[$exampleKey]['method'] }} "{{ $examples[$exampleKey]['url'] }}" \
  -H "Authorization: Bearer tu_token_aqui" \
  -H "Content-Type: application/json" \
  -d '{!! json_encode($examples[$exampleKey]['body'], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) !!}'</code></pre>
                                        @endif
                                    </div>
                                </div>
                            @endif
                        @endforeach
                    </div>
                </div>

                <!-- CATALOGS Examples -->
                <div>
                    <h4 class="text-xs font-semibold text-gray-800 mb-3 flex items-center">
                        <svg class="w-4 h-4 mr-2 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M7 3a1 1 0 000 2h6a1 1 0 100-2H7zM4 7a1 1 0 011-1h10a1 1 0 110 2H5a1 1 0 01-1-1zM2 11a2 2 0 012-2h12a2 2 0 012 2v4a2 2 0 01-2 2H4a2 2 0 01-2-2v-4z"></path>
                        </svg>
                        {{ __('Catalog Management Examples') }}
                    </h4>
                    <div class="space-y-3">
                        @foreach(['list_courses', 'create_course', 'list_campuses', 'create_campus', 'list_advisors', 'get_filters'] as $exampleKey)
                            @if(isset($examples[$exampleKey]))
                                <div>
                                    <h5 class="text-xs font-medium text-gray-700 mb-1">{{ $examples[$exampleKey]['title'] }}</h5>
                                    <div class="bg-gray-900 rounded-lg p-3 overflow-x-auto">
                                        @if($examples[$exampleKey]['method'] === 'GET')
                                            <pre class="text-xs text-green-400"><code>curl -X {{ $examples[$exampleKey]['method'] }} "{{ $examples[$exampleKey]['url'] }}" \
  -H "Authorization: Bearer tu_token_aqui" \
  -H "Accept: application/json"</code></pre>
                                        @else
                                            <pre class="text-xs text-green-400"><code>curl -X {{ $examples[$exampleKey]['method'] }} "{{ $examples[$exampleKey]['url'] }}" \
  -H "Authorization: Bearer tu_token_aqui" \
  -H "Content-Type: application/json" \
  -d '{!! json_encode($examples[$exampleKey]['body'], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) !!}'</code></pre>
                                        @endif
                                    </div>
                                </div>
                            @endif
                        @endforeach
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
                                <strong>{{ __('Important:') }}</strong> {{ __('Copy your tokens from the table above. They are only shown in full once when created.') }}
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
