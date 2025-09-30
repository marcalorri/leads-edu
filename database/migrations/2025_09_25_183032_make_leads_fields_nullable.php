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
            // Hacer nullable los campos que no son esenciales
            $table->string('apellidos', 150)->nullable()->change();
            $table->string('telefono', 20)->nullable()->change();
            $table->string('email', 255)->nullable()->change();
            $table->string('convocatoria', 100)->nullable()->change();
            $table->string('horario', 100)->nullable()->change();
            
            // Hacer nullable las foreign keys no esenciales
            $table->foreignId('fase_venta_id')->nullable()->change();
            $table->foreignId('curso_id')->nullable()->change();
            $table->foreignId('sede_id')->nullable()->change();
            $table->foreignId('modalidad_id')->nullable()->change();
            $table->foreignId('provincia_id')->nullable()->change();
            $table->foreignId('origen_id')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
