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
        Schema::table('personal_access_tokens', function (Blueprint $table) {
            // Agregar tenant_id si no existe
            if (!Schema::hasColumn('personal_access_tokens', 'tenant_id')) {
                $table->foreignId('tenant_id')->nullable()->after('id')->constrained('tenants')->onDelete('cascade');
            } else {
                // Si la columna existe pero no tiene foreign key, agregarla
                try {
                    $table->foreign('tenant_id')->references('id')->on('tenants')->onDelete('cascade');
                } catch (\Exception $e) {
                    // La foreign key ya existe
                }
            }
            
            // Solo agregar description si no existe
            if (!Schema::hasColumn('personal_access_tokens', 'description')) {
                $table->text('description')->nullable()->after('name');
            }
            
            // Agregar índice compuesto si no existe
            try {
                $table->index(['tenant_id', 'tokenable_id']);
            } catch (\Exception $e) {
                // El índice ya existe
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('personal_access_tokens', function (Blueprint $table) {
            // Eliminar índice compuesto
            try {
                $table->dropIndex(['tenant_id', 'tokenable_id']);
            } catch (\Exception $e) {
                // Índice no existe
            }
            
            // Eliminar foreign key y columna tenant_id
            if (Schema::hasColumn('personal_access_tokens', 'tenant_id')) {
                try {
                    $table->dropForeign(['tenant_id']);
                } catch (\Exception $e) {
                    // Foreign key no existe
                }
                $table->dropColumn('tenant_id');
            }
            
            // Eliminar description
            if (Schema::hasColumn('personal_access_tokens', 'description')) {
                $table->dropColumn('description');
            }
        });
    }
};
