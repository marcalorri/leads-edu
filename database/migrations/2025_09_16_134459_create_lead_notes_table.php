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
        Schema::create('lead_notes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tenant_id')->constrained()->onDelete('cascade');
            $table->foreignId('lead_id')->constrained()->onDelete('cascade');
            $table->foreignId('usuario_id')->constrained('users')->onDelete('restrict');
            $table->string('titulo', 255)->nullable();
            $table->text('contenido');
            $table->enum('tipo', ['llamada', 'email', 'reunion', 'seguimiento', 'observacion', 'otro']);
            $table->boolean('es_importante')->default(false);
            $table->datetime('fecha_seguimiento')->nullable();
            $table->timestamps();
            $table->softDeletes();
            
            // Índices
            $table->index('tenant_id');
            $table->index(['tenant_id', 'lead_id']);
            $table->index(['tenant_id', 'usuario_id']);
            $table->index('fecha_seguimiento');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('lead_notes');
    }
};
