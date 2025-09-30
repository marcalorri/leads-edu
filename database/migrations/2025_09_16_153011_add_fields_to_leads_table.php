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
            
            // Nota: Los campos pais, fecha_ganado y fecha_perdido ya existen en create_leads_table
            // Esta migración solo modifica el enum de estado
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
            
            // Nota: No se eliminan campos porque ya existían en create_leads_table
        });
    }
};
