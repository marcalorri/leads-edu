<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('lead_events', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tenant_id')->constrained()->onDelete('cascade');
            $table->foreignId('lead_id')->constrained()->onDelete('cascade');
            $table->foreignId('usuario_id')->constrained('users')->onDelete('restrict');
            $table->string('titulo', 255);
            $table->text('descripcion')->nullable();
            $table->enum('tipo', ['llamada', 'email', 'reunion', 'whatsapp', 'visita', 'seguimiento', 'otro']);
            $table->enum('estado', ['pendiente', 'en_progreso', 'completada', 'cancelada']);
            $table->enum('prioridad', ['baja', 'media', 'alta', 'urgente']);
            $table->datetime('fecha_programada');
            $table->datetime('fecha_completada')->nullable();
            $table->integer('duracion_estimada')->nullable(); // En minutos
            $table->text('resultado')->nullable();
            $table->boolean('requiere_recordatorio')->default(true);
            $table->integer('minutos_recordatorio')->default(15);
            $table->timestamps();
            $table->softDeletes();
            
            // Índices
            $table->index('tenant_id');
            $table->index(['tenant_id', 'lead_id']);
            $table->index(['tenant_id', 'usuario_id']);
            $table->index(['tenant_id', 'estado']);
            $table->index('fecha_programada');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('lead_events');
    }
};
