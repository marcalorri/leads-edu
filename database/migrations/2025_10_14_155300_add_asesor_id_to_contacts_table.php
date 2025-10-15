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
            $table->foreignId('asesor_id')->nullable()->after('tenant_id')->constrained('users')->nullOnDelete();
            $table->index('asesor_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('contacts', function (Blueprint $table) {
            $table->dropForeign(['asesor_id']);
            $table->dropIndex(['asesor_id']);
            $table->dropColumn('asesor_id');
        });
    }
};
