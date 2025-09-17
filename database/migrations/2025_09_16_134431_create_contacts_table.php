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
        Schema::create('contacts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tenant_id')->constrained()->onDelete('cascade');
            $table->foreignId('lead_id')->unique()->constrained()->onDelete('cascade');
            $table->string('nombre_completo', 255);
            $table->string('telefono_principal', 20);
            $table->string('telefono_secundario', 20)->nullable();
            $table->string('email_principal', 255);
            $table->string('email_secundario', 255)->nullable();
            $table->text('direccion')->nullable();
            $table->string('ciudad', 100)->nullable();
            $table->string('codigo_postal', 10)->nullable();
            $table->foreignId('provincia_id')->nullable()->constrained('provinces')->onDelete('set null');
            $table->date('fecha_nacimiento')->nullable();
            $table->string('dni_nie', 20)->nullable();
            $table->string('profesion', 100)->nullable();
            $table->string('empresa', 150)->nullable();
            $table->text('notas_contacto')->nullable();
            $table->enum('preferencia_comunicacion', ['email', 'telefono', 'whatsapp', 'sms']);
            $table->timestamps();
            $table->softDeletes();
            
            // Índices
            $table->index('tenant_id');
            $table->unique(['tenant_id', 'dni_nie'], 'contacts_tenant_dni_unique');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('contacts');
    }
};
