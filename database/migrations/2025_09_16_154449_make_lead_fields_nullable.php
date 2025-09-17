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
            // Hacer campos opcionales nullable
            $table->string('convocatoria', 100)->nullable()->change();
            $table->string('horario', 100)->nullable()->change();
            $table->foreignId('modalidad_id')->nullable()->change();
            $table->foreignId('fase_venta_id')->nullable()->change();
            $table->foreignId('origen_id')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('leads', function (Blueprint $table) {
            // Revertir campos a required
            $table->string('convocatoria', 100)->nullable(false)->change();
            $table->string('horario', 100)->nullable(false)->change();
            $table->foreignId('modalidad_id')->nullable(false)->change();
            $table->foreignId('fase_venta_id')->nullable(false)->change();
            $table->foreignId('origen_id')->nullable(false)->change();
        });
    }
};
