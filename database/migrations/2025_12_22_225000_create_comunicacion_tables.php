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
        // Consultas y Respuestas (P&R)
        Schema::create('consultas_respuestas_licitacion', function (Blueprint $table) {
            $table->id();
            $table->foreignId('licitacion_id')->constrained('licitaciones')->onDelete('cascade');
            $table->foreignId('contratista_id')->nullable()->constrained('empresas_contratistas')->onDelete('set null');
            $table->foreignId('usuario_pregunta_id')->nullable()->constrained('users')->onDelete('set null');
            $table->text('texto_pregunta');
            $table->timestamp('fecha_pregunta')->useCurrent();
            $table->foreignId('usuario_respuesta_id')->nullable()->constrained('users')->onDelete('set null');
            $table->text('texto_respuesta')->nullable();
            $table->timestamp('fecha_respuesta')->nullable();
            $table->boolean('es_publica')->default(false);
            $table->unsignedBigInteger('documento_adjunto_respuesta_id')->nullable();

            $table->index('contratista_id');
            $table->index('usuario_pregunta_id');
            $table->index('usuario_respuesta_id');
            $table->index('es_publica');
        });

        // Notificaciones internas
        Schema::create('notificaciones', function (Blueprint $table) {
            $table->id();
            $table->foreignId('usuario_destino_id')->constrained('users')->onDelete('cascade');
            $table->string('tipo_notificacion', 50);
            $table->text('mensaje');
            $table->string('url_destino')->nullable();
            $table->boolean('leida')->default(false);
            $table->timestamp('created_at')->useCurrent();

            $table->index('leida');
        });

        // Contratistas invitados a licitaciones privadas
        Schema::create('licitacion_contratistas_invitados', function (Blueprint $table) {
            $table->foreignId('licitacion_id')->constrained('licitaciones')->onDelete('cascade');
            $table->foreignId('contratista_id')->constrained('empresas_contratistas')->onDelete('cascade');
            $table->timestamp('fecha_invitacion')->useCurrent();
            $table->enum('estado_invitacion', ['enviada', 'vista', 'aceptada', 'rechazada'])->default('enviada');
            $table->primary(['licitacion_id', 'contratista_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('licitacion_contratistas_invitados');
        Schema::dropIfExists('notificaciones');
        Schema::dropIfExists('consultas_respuestas_licitacion');
    }
};
