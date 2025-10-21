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
        Schema::table('provinces', function (Blueprint $table) {
            // Eliminar índice único compuesto
            $table->dropUnique('provinces_tenant_id_codigo_unique');
            
            // Eliminar foreign key si existe
            $table->dropForeign(['tenant_id']);
            
            // Eliminar columna tenant_id
            $table->dropColumn('tenant_id');
            
            // Crear nuevo índice único solo en codigo
            $table->unique('codigo');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('provinces', function (Blueprint $table) {
            // Restaurar tenant_id
            $table->foreignId('tenant_id')->after('id')->constrained()->onDelete('cascade');
            
            // Eliminar índice único de codigo
            $table->dropUnique(['codigo']);
            
            // Restaurar índice único compuesto
            $table->unique(['tenant_id', 'codigo'], 'provinces_tenant_id_codigo_unique');
        });
    }
};
