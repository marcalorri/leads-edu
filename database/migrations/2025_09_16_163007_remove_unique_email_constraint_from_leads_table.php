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
            // Eliminar constraint único de email por tenant
            $table->dropUnique(['tenant_id', 'email']);
            
            // Añadir índice simple para búsquedas
            $table->index(['tenant_id', 'email']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('leads', function (Blueprint $table) {
            // Restaurar constraint único
            $table->dropIndex(['tenant_id', 'email']);
            $table->unique(['tenant_id', 'email']);
        });
    }
};
