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
        // Tabla de Empresas Principales (clientes de RyCE)
        Schema::create('empresas_principales', function (Blueprint $table) {
            $table->id();
            $table->string('razon_social');
            $table->string('rut', 20)->unique();
            $table->string('direccion')->nullable();
            $table->string('telefono', 25)->nullable();
            $table->string('email_contacto_principal', 100)->nullable();
            $table->string('persona_contacto_principal', 150)->nullable();
            $table->string('logo_url')->nullable();
            $table->boolean('activo')->default(true);
            $table->timestamps();

            $table->index('razon_social');
            $table->index('activo');
        });

        // Tabla de Empresas Contratistas (proveedores)
        Schema::create('empresas_contratistas', function (Blueprint $table) {
            $table->id();
            $table->string('razon_social');
            $table->string('rut', 20)->unique();
            $table->string('direccion')->nullable();
            $table->string('telefono', 25)->nullable();
            $table->string('email_contacto_principal', 100)->nullable();
            $table->string('persona_contacto_principal', 150)->nullable();
            $table->text('rubros_especialidad')->nullable();
            $table->boolean('documentacion_validada')->default(false);
            $table->boolean('activo')->default(true);
            $table->timestamps();

            $table->index('razon_social');
            $table->index('activo');
            $table->index('documentacion_validada');
        });

        // Agregar foreign keys a users
        Schema::table('users', function (Blueprint $table) {
            $table->foreign('empresa_principal_id')
                  ->references('id')->on('empresas_principales')
                  ->onDelete('set null');
            $table->foreign('empresa_contratista_id')
                  ->references('id')->on('empresas_contratistas')
                  ->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['empresa_principal_id']);
            $table->dropForeign(['empresa_contratista_id']);
        });

        Schema::dropIfExists('empresas_contratistas');
        Schema::dropIfExists('empresas_principales');
    }
};
