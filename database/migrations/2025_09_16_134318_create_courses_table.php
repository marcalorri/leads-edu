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
        Schema::create('courses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tenant_id')->constrained()->onDelete('cascade');
            $table->string('codigo_curso', 50);
            $table->string('titulacion', 255);
            $table->foreignId('area_id')->constrained()->onDelete('restrict');
            $table->foreignId('unidad_negocio_id')->references('id')->on('business_units')->onDelete('restrict');
            $table->foreignId('duracion_id')->references('id')->on('durations')->onDelete('restrict');
            $table->timestamps();
            $table->softDeletes();
            
            // Índices
            $table->index('tenant_id');
            $table->unique(['tenant_id', 'codigo_curso']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('courses');
    }
};
