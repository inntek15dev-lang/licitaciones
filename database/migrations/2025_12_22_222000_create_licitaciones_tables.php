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
        // Tabla de Categorías de Licitación
        Schema::create('categorias_licitaciones', function (Blueprint $table) {
            $table->id();
            $table->string('nombre_categoria', 100)->unique();
            $table->text('descripcion')->nullable();
            $table->timestamps();
        });

        // Tabla principal de Licitaciones
        Schema::create('licitaciones', function (Blueprint $table) {
            $table->id();
            $table->foreignId('principal_id')->constrained('empresas_principales')->onDelete('cascade');
            $table->foreignId('usuario_creador_id')->constrained('users')->onDelete('restrict');
            $table->string('codigo_licitacion', 50)->unique();
            $table->string('titulo');
            $table->text('descripcion_corta')->nullable();
            $table->longText('descripcion_larga')->nullable();
            $table->enum('tipo_licitacion', ['publica', 'privada_invitacion'])->default('publica');
            $table->enum('estado', [
                'borrador',
                'lista_para_publicar',
                'observada_por_ryce',
                'publicada',
                'cerrada_consultas',
                'cerrada_ofertas',
                'en_evaluacion',
                'adjudicada',
                'desierta',
                'cancelada'
            ])->default('borrador');
            $table->dateTime('fecha_publicacion')->nullable();
            $table->dateTime('fecha_inicio_consultas')->nullable();
            $table->dateTime('fecha_cierre_consultas')->nullable();
            $table->dateTime('fecha_inicio_recepcion_ofertas')->nullable();
            $table->dateTime('fecha_cierre_recepcion_ofertas')->nullable();
            $table->date('fecha_adjudicacion_estimada')->nullable();
            $table->dateTime('fecha_adjudicacion_real')->nullable();
            $table->decimal('presupuesto_referencial', 15, 2)->nullable();
            $table->string('moneda_presupuesto', 5)->nullable();
            $table->string('lugar_ejecucion_trabajos')->nullable();
            $table->boolean('requiere_visita_terreno')->default(false);
            $table->dateTime('fecha_visita_terreno')->nullable();
            $table->string('contacto_visita_terreno')->nullable();
            $table->text('comentarios_revision_ryce')->nullable();
            $table->foreignId('usuario_revisor_ryce_id')->nullable()->constrained('users')->onDelete('set null');
            $table->text('motivo_cancelacion')->nullable();
            $table->text('motivo_desierta')->nullable();
            $table->timestamps();

            $table->index('estado');
            $table->index('titulo');
            $table->index('fecha_cierre_recepcion_ofertas');
        });

        // Tabla pivot: Licitación - Categorías (muchos a muchos)
        Schema::create('licitacion_categorias', function (Blueprint $table) {
            $table->foreignId('licitacion_id')->constrained('licitaciones')->onDelete('cascade');
            $table->foreignId('categoria_id')->constrained('categorias_licitaciones')->onDelete('cascade');
            $table->primary(['licitacion_id', 'categoria_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('licitacion_categorias');
        Schema::dropIfExists('licitaciones');
        Schema::dropIfExists('categorias_licitaciones');
    }
};
