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
            // Solo agregar description si no existe
            if (!Schema::hasColumn('personal_access_tokens', 'description')) {
                $table->text('description')->nullable()->after('name');
            }
            
            // Agregar foreign key y índice si tenant_id existe pero no tiene constraints
            try {
                $table->foreign('tenant_id')->references('id')->on('tenants')->onDelete('cascade');
            } catch (\Exception $e) {
                // La foreign key ya existe
            }
            
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
            try {
                $table->dropForeign(['tenant_id']);
            } catch (\Exception $e) {
                // Foreign key no existe
            }
            
            try {
                $table->dropIndex(['tenant_id', 'tokenable_id']);
            } catch (\Exception $e) {
                // Índice no existe
            }
            
            if (Schema::hasColumn('personal_access_tokens', 'description')) {
                $table->dropColumn('description');
            }
        });
    }
};
