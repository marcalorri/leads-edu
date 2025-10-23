<?php

namespace App\Filament\Dashboard\Resources\ApiTokenResource\Pages;

use App\Filament\Dashboard\Resources\ApiTokenResource;
use App\Models\ApiToken;
use Filament\Resources\Pages\CreateRecord;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\Auth;

class CreateApiToken extends CreateRecord
{
    protected static string $resource = ApiTokenResource::class;
    
    public ?string $generatedToken = null;


    protected function handleRecordCreation(array $data): ApiToken
    {
        $user = Auth::user();
        $tenant = filament()->getTenant();

        // Crear el token usando Sanctum
        $token = $user->createToken(
            $data['name'],
            $data['abilities'] ?? [],
            $data['expires_at'] ?? null
        );

        // Obtener el registro del token y actualizar con tenant_id y descripción
        $tokenRecord = $token->accessToken;
        $tokenRecord->tenant_id = $tenant->id;
        $tokenRecord->description = $data['description'] ?? null;
        $tokenRecord->save();

        // Guardar el token generado para mostrarlo en la vista
        $this->generatedToken = $token->plainTextToken;

        // Devolver el ApiToken
        return ApiToken::find($tokenRecord->id);
    }

    protected function getCreatedNotification(): ?Notification
    {
        if ($this->generatedToken) {
            return Notification::make()
                ->success()
                ->title(__('Token created successfully'))
                ->body(__('Copy the token below. It will only be shown once.'))
                ->persistent()
                ->send();
        }
        
        return null;
    }
    
    protected function afterCreate(): void
    {
        // Redirigir a una página personalizada que muestre el token
        if ($this->generatedToken) {
            session()->flash('generated_token', $this->generatedToken);
        }
    }
    
    protected function getRedirectUrl(): string
    {
        // Redirigir a la página de visualización del token
        return static::getResource()::getUrl('token-created', [
            'tenant' => filament()->getTenant(),
            'record' => $this->record,
        ]);
    }
}
