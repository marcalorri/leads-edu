<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\V1\LeadApiController;
use App\Http\Controllers\Api\V1\CatalogApiController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

// Payment provider webhooks (sin autenticación)
Route::post('/payments-providers/stripe/webhook', [
    App\Http\Controllers\PaymentProviders\StripeController::class,
    'handleWebhook',
])->name('payments-providers.stripe.webhook');

Route::post('/payments-providers/paddle/webhook', [
    App\Http\Controllers\PaymentProviders\PaddleController::class,
    'handleWebhook',
])->name('payments-providers.paddle.webhook');

Route::post('/payments-providers/lemon-squeezy/webhook', [
    App\Http\Controllers\PaymentProviders\LemonSqueezyController::class,
    'handleWebhook',
])->name('payments-providers.lemon-squeezy.webhook');

/*
|--------------------------------------------------------------------------
| API V1 Routes - Leads Management
|--------------------------------------------------------------------------
|
| Rutas protegidas con autenticación Sanctum y middleware de tenant.
| Requieren tokens API válidos con los scopes apropiados.
|
*/

Route::prefix('v1')->middleware(['auth:sanctum', 'tenant.api', 'api.rate_limit:1000,60'])->group(function () {
    
    // Leads API Routes
    Route::prefix('leads')->group(function () {
        
        // GET /api/v1/leads - Listar leads (requiere scope leads:read)
        Route::get('/', [LeadApiController::class, 'index'])
            ->middleware('api.permission:leads:read')
            ->name('api.v1.leads.index');
        
        // POST /api/v1/leads - Crear lead (requiere scope leads:write)
        Route::post('/', [LeadApiController::class, 'store'])
            ->middleware('api.permission:leads:write')
            ->name('api.v1.leads.store');
        
        // GET /api/v1/leads/filters - Obtener filtros disponibles (requiere scope leads:read)
        Route::get('/filters', [LeadApiController::class, 'filters'])
            ->middleware('api.permission:leads:read')
            ->name('api.v1.leads.filters');
        
        // GET /api/v1/leads/{id} - Ver lead específico (requiere scope leads:read)
        Route::get('/{lead}', [LeadApiController::class, 'show'])
            ->middleware('api.permission:leads:read')
            ->name('api.v1.leads.show');
        
        // PUT /api/v1/leads/{id} - Actualizar lead (requiere scope leads:write)
        Route::put('/{lead}', [LeadApiController::class, 'update'])
            ->middleware('api.permission:leads:write')
            ->name('api.v1.leads.update');
        
        // DELETE /api/v1/leads/{id} - Eliminar lead (requiere scope leads:delete)
        Route::delete('/{lead}', [LeadApiController::class, 'destroy'])
            ->middleware('api.permission:leads:delete')
            ->name('api.v1.leads.destroy');
    });
    
    // Catálogos API Routes
    Route::prefix('catalogs')->group(function () {
        
        // Cursos
        Route::get('/courses', [CatalogApiController::class, 'courses'])
            ->middleware('api.permission:leads:read')
            ->name('api.v1.catalogs.courses');
        Route::post('/courses', [CatalogApiController::class, 'storeCourse'])
            ->middleware('api.permission:leads:admin')
            ->name('api.v1.catalogs.courses.store');
        Route::put('/courses/{course}', [CatalogApiController::class, 'updateCourse'])
            ->middleware('api.permission:leads:admin')
            ->name('api.v1.catalogs.courses.update');
        
        // Asesores (solo lectura)
        Route::get('/asesores', [CatalogApiController::class, 'asesores'])
            ->middleware('api.permission:leads:read')
            ->name('api.v1.catalogs.asesores');
        
        // Sedes
        Route::get('/campuses', [CatalogApiController::class, 'campuses'])
            ->middleware('api.permission:leads:read')
            ->name('api.v1.catalogs.campuses');
        Route::post('/campuses', [CatalogApiController::class, 'storeCampus'])
            ->middleware('api.permission:leads:admin')
            ->name('api.v1.catalogs.campuses.store');
        Route::put('/campuses/{campus}', [CatalogApiController::class, 'updateCampus'])
            ->middleware('api.permission:leads:admin')
            ->name('api.v1.catalogs.campuses.update');
        
        // Modalidades
        Route::get('/modalities', [CatalogApiController::class, 'modalities'])
            ->middleware('api.permission:leads:read')
            ->name('api.v1.catalogs.modalities');
        Route::post('/modalities', [CatalogApiController::class, 'storeModality'])
            ->middleware('api.permission:leads:admin')
            ->name('api.v1.catalogs.modalities.store');
        Route::put('/modalities/{modality}', [CatalogApiController::class, 'updateModality'])
            ->middleware('api.permission:leads:admin')
            ->name('api.v1.catalogs.modalities.update');
        
        // Provincias
        Route::get('/provinces', [CatalogApiController::class, 'provinces'])
            ->middleware('api.permission:leads:read')
            ->name('api.v1.catalogs.provinces');
        Route::post('/provinces', [CatalogApiController::class, 'storeProvince'])
            ->middleware('api.permission:leads:admin')
            ->name('api.v1.catalogs.provinces.store');
        Route::put('/provinces/{province}', [CatalogApiController::class, 'updateProvince'])
            ->middleware('api.permission:leads:admin')
            ->name('api.v1.catalogs.provinces.update');
        
        // Fases de Venta
        Route::get('/sales-phases', [CatalogApiController::class, 'salesPhases'])
            ->middleware('api.permission:leads:read')
            ->name('api.v1.catalogs.sales-phases');
        Route::post('/sales-phases', [CatalogApiController::class, 'storeSalesPhase'])
            ->middleware('api.permission:leads:admin')
            ->name('api.v1.catalogs.sales-phases.store');
        Route::put('/sales-phases/{salesPhase}', [CatalogApiController::class, 'updateSalesPhase'])
            ->middleware('api.permission:leads:admin')
            ->name('api.v1.catalogs.sales-phases.update');
        
        // Orígenes
        Route::get('/origins', [CatalogApiController::class, 'origins'])
            ->middleware('api.permission:leads:read')
            ->name('api.v1.catalogs.origins');
        Route::post('/origins', [CatalogApiController::class, 'storeOrigin'])
            ->middleware('api.permission:leads:admin')
            ->name('api.v1.catalogs.origins.store');
        Route::put('/origins/{origin}', [CatalogApiController::class, 'updateOrigin'])
            ->middleware('api.permission:leads:admin')
            ->name('api.v1.catalogs.origins.update');
        
        // Estados (solo lectura - es un enum)
        Route::get('/estados', [CatalogApiController::class, 'estados'])
            ->middleware('api.permission:leads:read')
            ->name('api.v1.catalogs.estados');
    });
    
});
