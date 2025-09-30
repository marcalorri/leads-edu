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
            // Primero eliminar las foreign key constraints existentes
            $table->dropForeign(['fase_venta_id']);
            $table->dropForeign(['curso_id']);
            $table->dropForeign(['sede_id']);
            $table->dropForeign(['modalidad_id']);
            $table->dropForeign(['provincia_id']);
            $table->dropForeign(['origen_id']);
        });

        Schema::table('leads', function (Blueprint $table) {
            // Ahora hacer las columnas nullable
            $table->unsignedBigInteger('fase_venta_id')->nullable()->change();
            $table->unsignedBigInteger('curso_id')->nullable()->change();
            $table->unsignedBigInteger('sede_id')->nullable()->change();
            $table->unsignedBigInteger('modalidad_id')->nullable()->change();
            $table->unsignedBigInteger('provincia_id')->nullable()->change();
            $table->unsignedBigInteger('origen_id')->nullable()->change();
        });

        Schema::table('leads', function (Blueprint $table) {
            // Recrear las foreign keys con nullable
            $table->foreign('fase_venta_id')->references('id')->on('sales_phases')->onDelete('set null');
            $table->foreign('curso_id')->references('id')->on('courses')->onDelete('set null');
            $table->foreign('sede_id')->references('id')->on('campuses')->onDelete('set null');
            $table->foreign('modalidad_id')->references('id')->on('modalities')->onDelete('set null');
            $table->foreign('provincia_id')->references('id')->on('provinces')->onDelete('set null');
            $table->foreign('origen_id')->references('id')->on('origins')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('leads', function (Blueprint $table) {
            // Revertir los cambios
            $table->dropForeign(['fase_venta_id']);
            $table->dropForeign(['curso_id']);
            $table->dropForeign(['sede_id']);
            $table->dropForeign(['modalidad_id']);
            $table->dropForeign(['provincia_id']);
            $table->dropForeign(['origen_id']);
        });

        Schema::table('leads', function (Blueprint $table) {
            $table->unsignedBigInteger('fase_venta_id')->nullable(false)->change();
            $table->unsignedBigInteger('curso_id')->nullable(false)->change();
            $table->unsignedBigInteger('sede_id')->nullable(false)->change();
            $table->unsignedBigInteger('modalidad_id')->nullable(false)->change();
            $table->unsignedBigInteger('provincia_id')->nullable(false)->change();
            $table->unsignedBigInteger('origen_id')->nullable(false)->change();
        });

        Schema::table('leads', function (Blueprint $table) {
            $table->foreign('fase_venta_id')->references('id')->on('sales_phases')->onDelete('restrict');
            $table->foreign('curso_id')->references('id')->on('courses')->onDelete('restrict');
            $table->foreign('sede_id')->references('id')->on('campuses')->onDelete('restrict');
            $table->foreign('modalidad_id')->references('id')->on('modalities')->onDelete('restrict');
            $table->foreign('provincia_id')->references('id')->on('provinces')->onDelete('restrict');
            $table->foreign('origen_id')->references('id')->on('origins')->onDelete('restrict');
        });
    }
};
