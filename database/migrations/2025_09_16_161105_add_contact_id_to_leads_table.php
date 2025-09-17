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
            // Añadir referencia al contacto
            $table->foreignId('contact_id')->nullable()->after('tenant_id')->constrained('contacts')->onDelete('set null');
            $table->index(['tenant_id', 'contact_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('leads', function (Blueprint $table) {
            $table->dropIndex(['tenant_id', 'contact_id']);
            $table->dropForeign(['contact_id']);
            $table->dropColumn('contact_id');
        });
    }
};
