<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Normalizar posibles valores antiguos a los estados definitivos
        // Si alguna vez existieron estos valores, los mapeamos:
        // - nuevo, contactado, interesado -> abierto
        // - matriculado -> ganado

        DB::table('leads')
            ->whereIn('estado', ['nuevo', 'contactado', 'interesado'])
            ->update(['estado' => 'abierto']);

        DB::table('leads')
            ->where('estado', 'matriculado')
            ->update(['estado' => 'ganado']);

        Schema::table('leads', function (Blueprint $table) {
            $table->enum('estado', ['abierto', 'ganado', 'perdido'])->change();
        });
    }
};
