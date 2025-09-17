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
            // Eliminar foreign key constraint
            $table->dropForeign(['lead_id']);
            
            // Eliminar la columna lead_id
            $table->dropColumn('lead_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('contacts', function (Blueprint $table) {
            // Recrear la columna lead_id
            $table->foreignId('lead_id')->nullable()->constrained()->onDelete('cascade');
        });
    }
};
