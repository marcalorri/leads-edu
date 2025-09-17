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
        Schema::create('leads', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tenant_id')->constrained()->onDelete('cascade');
            $table->foreignId('asesor_id')->nullable()->constrained('users')->onDelete('set null');
            $table->enum('estado', ['abierto', 'ganado', 'perdido']);
            $table->foreignId('fase_venta_id')->constrained('sales_phases')->onDelete('restrict');
            $table->foreignId('curso_id')->constrained('courses')->onDelete('restrict');
            $table->foreignId('sede_id')->constrained('campuses')->onDelete('restrict');
            $table->foreignId('modalidad_id')->constrained('modalities')->onDelete('restrict');
            $table->foreignId('provincia_id')->constrained('provinces')->onDelete('restrict');
            $table->string('nombre', 100);
            $table->string('apellidos', 150);
            $table->string('telefono', 20);
            $table->string('email', 255);
            $table->string('pais', 100)->nullable();
            $table->foreignId('motivo_nulo_id')->nullable()->constrained('null_reasons')->onDelete('set null');
            $table->foreignId('origen_id')->constrained('origins')->onDelete('restrict');
            $table->string('convocatoria', 100);
            $table->string('horario', 100);
            $table->string('utm_source', 255)->nullable();
            $table->string('utm_medium', 255)->nullable();
            $table->string('utm_campaign', 255)->nullable();
            $table->timestamp('fecha_ganado')->nullable();
            $table->timestamp('fecha_perdido')->nullable();
            $table->timestamps();
            $table->softDeletes();
            
            // Índices
            $table->index('tenant_id');
            $table->index(['tenant_id', 'estado']);
            $table->index(['tenant_id', 'asesor_id']);
            $table->unique(['tenant_id', 'email']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('leads');
    }
};
