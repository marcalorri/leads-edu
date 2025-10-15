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

        // Actualizar con tenant_id y descripción
        $tokenRecord = ApiToken::find($token->accessToken->id);
        
        if ($tokenRecord) {
            $tokenRecord->update([
                'tenant_id' => $tenant->id,
                'description' => $data['description'] ?? null,
            ]);
        } else {
            // El token ya fue creado por Sanctum, solo necesitamos actualizarlo
            // Buscar por otros campos si el ID no funciona
            $tokenRecord = ApiToken::where('tokenable_type', get_class($user))
                ->where('tokenable_id', $user->id)
                ->where('name', $data['name'])
                ->latest()
                ->first();
                
            if ($tokenRecord) {
                $tokenRecord->update([
                    'tenant_id' => $tenant->id,
                    'description' => $data['description'] ?? null,
                ]);
            } else {
                // Si aún no se encuentra, usar el token que ya existe
                $sanctumToken = $token->accessToken;
                $sanctumToken->tenant_id = $tenant->id;
                $sanctumToken->description = $data['description'] ?? null;
                $sanctumToken->save();
                
                // Convertir a ApiToken para el return
                $tokenRecord = ApiToken::find($sanctumToken->id);
            }
        }

        // Mostrar token al usuario
        Notification::make()
            ->title(__('Token created successfully'))
            ->body(__('SAVE THIS TOKEN: ') . $token->plainTextToken)
            ->success()
            ->persistent()
            ->send();

        // Asegurar que devolvemos un ApiToken
        return $tokenRecord ?: ApiToken::find($token->accessToken->id);
    }

    protected function getCreatedNotification(): ?Notification
    {
        return null;
    }
}
