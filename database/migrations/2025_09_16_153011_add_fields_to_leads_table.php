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
        Schema::table('leads', function (Blueprint $table) {
            // Modificar enum de estado
            $table->enum('estado', ['abierto', 'ganado', 'perdido'])->change();
            
            // Añadir nuevos campos
            $table->string('pais', 100)->nullable()->after('email');
            $table->timestamp('fecha_ganado')->nullable()->after('utm_campaign');
            $table->timestamp('fecha_perdido')->nullable()->after('fecha_ganado');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('leads', function (Blueprint $table) {
            // Revertir enum de estado
            $table->enum('estado', ['nuevo', 'contactado', 'interesado', 'matriculado', 'perdido'])->change();
            
            // Eliminar campos añadidos
            $table->dropColumn(['pais', 'fecha_ganado', 'fecha_perdido']);
        });
    }
};
