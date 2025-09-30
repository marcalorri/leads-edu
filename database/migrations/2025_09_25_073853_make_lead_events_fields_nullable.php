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
        Schema::table('lead_events', function (Blueprint $table) {
            // Hacer nullable los campos que ya no usaremos en el formulario simplificado
            $table->enum('tipo', ['llamada', 'email', 'reunion', 'whatsapp', 'visita', 'seguimiento', 'otro'])->nullable()->change();
            $table->enum('prioridad', ['baja', 'media', 'alta', 'urgente'])->nullable()->change();
            $table->boolean('requiere_recordatorio')->nullable()->change();
            $table->integer('minutos_recordatorio')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('lead_events', function (Blueprint $table) {
            // Revertir los cambios
            $table->enum('tipo', ['llamada', 'email', 'reunion', 'whatsapp', 'visita', 'seguimiento', 'otro'])->nullable(false)->change();
            $table->enum('prioridad', ['baja', 'media', 'alta', 'urgente'])->nullable(false)->change();
            $table->boolean('requiere_recordatorio')->default(true)->nullable(false)->change();
            $table->integer('minutos_recordatorio')->default(15)->nullable(false)->change();
        });
    }
};
