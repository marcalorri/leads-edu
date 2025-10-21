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
        // Añadir country_id a leads
        Schema::table('leads', function (Blueprint $table) {
            $table->foreignId('country_id')
                ->nullable()
                ->after('provincia_id')
                ->constrained('countries')
                ->onDelete('set null');
            
            $table->index('country_id');
        });
        
        // Añadir country_id a contacts
        Schema::table('contacts', function (Blueprint $table) {
            $table->foreignId('country_id')
                ->nullable()
                ->after('provincia_id')
                ->constrained('countries')
                ->onDelete('set null');
            
            $table->index('country_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('leads', function (Blueprint $table) {
            $table->dropForeign(['country_id']);
            $table->dropIndex(['country_id']);
            $table->dropColumn('country_id');
        });
        
        Schema::table('contacts', function (Blueprint $table) {
            $table->dropForeign(['country_id']);
            $table->dropIndex(['country_id']);
            $table->dropColumn('country_id');
        });
    }
};
