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
        Schema::create('precalificaciones_contratistas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('licitacion_id')->constrained('licitaciones')->onDelete('cascade');
            $table->foreignId('contratista_id')->constrained('empresas_contratistas')->onDelete('cascade');
            $table->enum('estado', ['pendiente', 'aprobada', 'rechazada', 'rectificando'])->default('pendiente');
            $table->timestamp('fecha_solicitud')->useCurrent();
            $table->timestamp('fecha_resolucion')->nullable();
            $table->foreignId('revisado_por_usuario_id')->nullable()->constrained('users')->onDelete('set null');
            $table->enum('tipo_revisor', ['ryce', 'principal'])->nullable();
            $table->text('motivo_rechazo')->nullable();
            $table->text('comentarios_contratista')->nullable();
            $table->text('comentarios_rectificacion')->nullable();
            $table->timestamps();

            // Un contratista solo puede tener una precalificación activa por licitación
            $table->unique(['licitacion_id', 'contratista_id'], 'precal_lic_cont_unique');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('precalificaciones_contratistas');
    }
};
