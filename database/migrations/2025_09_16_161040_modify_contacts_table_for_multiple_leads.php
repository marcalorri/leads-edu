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
        Schema::table('contacts', function (Blueprint $table) {
            // Primero eliminar la foreign key constraint
            $table->dropForeign(['lead_id']);
            
            // Ahora podemos eliminar el índice único
            $table->dropUnique(['lead_id']);
            
            // Recrear la foreign key sin unique constraint
            $table->foreign('lead_id')->references('id')->on('leads')->onDelete('cascade');
            
            // Añadir índices para búsqueda eficiente
            $table->index(['tenant_id', 'email_principal']);
            $table->index(['tenant_id', 'telefono_principal']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('contacts', function (Blueprint $table) {
            // Revertir cambios
            $table->dropIndex(['tenant_id', 'email_principal']);
            $table->dropIndex(['tenant_id', 'telefono_principal']);
            $table->unique('lead_id');
        });
    }
};
