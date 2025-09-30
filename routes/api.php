<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\V1\LeadApiController;

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
    
});
