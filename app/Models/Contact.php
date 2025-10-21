<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\Tenant;
use App\Models\Province;
use App\Models\Lead;
use Illuminate\Support\Facades\Auth;

class Contact extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'tenant_id',
        'asesor_id',
        'nombre_completo',
        'telefono_principal',
        'telefono_secundario',
        'email_principal',
        'email_secundario',
        'direccion',
        'ciudad',
        'codigo_postal',
        'provincia_id',
        'country_id',
        'fecha_nacimiento',
        'dni_nie',
        'profesion',
        'empresa',
        'notas_contacto',
        'preferencia_comunicacion',
    ];

    protected $casts = [
        'fecha_nacimiento' => 'date',
    ];

    protected static function booted(): void
    {
        static::addGlobalScope('tenant', function ($query) {
            if (filament()->getTenant()) {
                $query->where('tenant_id', filament()->getTenant()->id);
            }
        });

        // Scope para filtrar contactos por usuario (solo si no puede ver todos los contactos)
        static::addGlobalScope('user_access', function ($query) {
            if (Auth::check()) {
                $user = Auth::user();
                // Si no puede ver todos los contactos, solo ve los de sus leads
                if (!$user->canViewAllContacts()) {
                    // Solo mostrar contactos que tienen leads asignados al usuario
                    $query->whereHas('leads', function ($leadQuery) use ($user) {
                        $leadQuery->where('asesor_id', $user->id);
                    });
                }
            }
        });

        static::creating(function ($model) {
            if (!$model->tenant_id && filament()->getTenant()) {
                $model->tenant_id = filament()->getTenant()->id;
            }
        });
    }

    // Relaciones
    public function tenant(): BelongsTo
    {
        return $this->belongsTo(Tenant::class);
    }

    public function leads(): HasMany
    {
        return $this->hasMany(Lead::class, 'contact_id');
    }

    public function province(): BelongsTo
    {
        return $this->belongsTo(Province::class, 'provincia_id');
    }

    public function country(): BelongsTo
    {
        return $this->belongsTo(Country::class);
    }

    public function asesor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'asesor_id');
    }
}
