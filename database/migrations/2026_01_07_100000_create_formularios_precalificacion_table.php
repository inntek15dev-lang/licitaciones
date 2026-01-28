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
        // Tabla principal: Formularios de Precalificación por Empresa Principal
        Schema::create('formularios_precalificacion', function (Blueprint $table) {
            $table->id();
            $table->foreignId('empresa_principal_id')->constrained('empresas_principales')->onDelete('cascade');
            $table->string('nombre', 255);
            $table->text('descripcion')->nullable();
            $table->boolean('activo')->default(true);
            $table->timestamps();
            
            // Un Principal no puede tener dos formularios con el mismo nombre
            $table->unique(['empresa_principal_id', 'nombre']);
        });

        // Tabla pivot: Requisitos de cada Formulario
        Schema::create('formulario_requisitos_precalificacion', function (Blueprint $table) {
            $table->id();
            // Usando nombres cortos para FK (MySQL tiene límite de 64 chars)
            $table->unsignedBigInteger('formulario_precalificacion_id');
            $table->unsignedBigInteger('catalogo_requisito_id');
            $table->boolean('obligatorio')->default(true);
            $table->unsignedInteger('orden')->default(0);
            $table->timestamps();
            
            // FKs con nombres cortos
            $table->foreign('formulario_precalificacion_id', 'fk_form_req_formulario')
                  ->references('id')->on('formularios_precalificacion')
                  ->onDelete('cascade');
            $table->foreign('catalogo_requisito_id', 'fk_form_req_catalogo')
                  ->references('id')->on('catalogo_requisitos_precalificacion')
                  ->onDelete('cascade');
            
            // No duplicar requisitos en el mismo formulario
            $table->unique(['formulario_precalificacion_id', 'catalogo_requisito_id'], 'form_req_unique');
        });

        // Agregar columna a licitaciones para relacionar con un formulario
        // Nota: SQLite no soporta bien ADD CONSTRAINT via ALTER TABLE
        // La relación funciona igual vía Eloquent
        Schema::table('licitaciones', function (Blueprint $table) {
            $table->unsignedBigInteger('formulario_precalificacion_id')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('licitaciones', function (Blueprint $table) {
            $table->dropColumn('formulario_precalificacion_id');
        });
        
        Schema::dropIfExists('formulario_requisitos_precalificacion');
        Schema::dropIfExists('formularios_precalificacion');
    }
};
