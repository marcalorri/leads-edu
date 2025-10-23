<?php

namespace App\Filament\Dashboard\Resources\ApiTokenResource\Pages;

use App\Filament\Dashboard\Resources\ApiTokenResource;
use Filament\Resources\Pages\Page;

class TokenCreated extends Page
{
    protected static string $resource = ApiTokenResource::class;
    
    protected static ?string $title = 'API Token Created';

    public ?string $token = null;
    
    protected string $view = 'filament.dashboard.resources.api-token-resource.pages.token-created';

    public function mount(): void
    {
        // Obtener el token de la sesión
        $this->token = session()->get('generated_token');
        
        // Limpiar la sesión después de obtener el token
        session()->forget('generated_token');
        
        // Si no hay token, redirigir a la lista
        if (!$this->token) {
            $this->redirect(static::getResource()::getUrl('index', ['tenant' => filament()->getTenant()]));
        }
    }

    public function getTitle(): string
    {
        return __('API Token Created');
    }
}
