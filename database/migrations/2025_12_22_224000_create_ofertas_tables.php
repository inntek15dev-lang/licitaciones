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
        // Ofertas enviadas por Contratistas
        Schema::create('ofertas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('licitacion_id')->constrained('licitaciones')->onDelete('cascade');
            $table->foreignId('contratista_id')->constrained('empresas_contratistas')->onDelete('restrict');
            $table->foreignId('usuario_presenta_id')->constrained('users')->onDelete('restrict');
            $table->timestamp('fecha_presentacion')->useCurrent();
            $table->decimal('monto_oferta_economica', 15, 2);
            $table->string('moneda_oferta', 5);
            $table->unsignedInteger('validez_oferta_dias')->nullable();
            $table->text('comentarios_oferta')->nullable();
            $table->enum('estado_oferta', [
                'pendiente_precalificacion_ryce',
                'precalificada_por_ryce',
                'no_precalificada_ryce',
                'presentada',
                'en_evaluacion_principal',
                'adjudicada',
                'no_adjudicada',
                'retirada'
            ])->default('pendiente_precalificacion_ryce');
            $table->text('comentarios_precalificacion_ryce')->nullable();
            $table->foreignId('usuario_precalificador_ryce_id')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamp('fecha_precalificacion_ryce')->nullable();
            $table->timestamp('fecha_actualizacion_estado')->useCurrent()->useCurrentOnUpdate();

            $table->unique(['licitacion_id', 'contratista_id']);
            $table->index('estado_oferta');
        });

        // Documentos de la oferta
        Schema::create('documentos_oferta', function (Blueprint $table) {
            $table->id();
            $table->foreignId('oferta_id')->constrained('ofertas')->onDelete('cascade');
            $table->string('nombre_documento');
            $table->text('descripcion_documento')->nullable();
            $table->string('ruta_archivo');
            $table->enum('tipo_documento', ['propuesta_tecnica', 'propuesta_economica', 'garantia_seriedad', 'certificado', 'otro']);
            $table->timestamp('fecha_subida')->useCurrent();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('documentos_oferta');
        Schema::dropIfExists('ofertas');
    }
};
