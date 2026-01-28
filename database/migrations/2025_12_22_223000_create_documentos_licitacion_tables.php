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
        // Documentos subidos por el Principal para la licitación
        Schema::create('documentos_licitacion', function (Blueprint $table) {
            $table->id();
            $table->foreignId('licitacion_id')->constrained('licitaciones')->onDelete('cascade');
            $table->string('nombre_documento');
            $table->text('descripcion_documento')->nullable();
            $table->string('ruta_archivo');
            $table->enum('tipo_documento', ['bases', 'anexo_tecnico', 'anexo_economico', 'plano', 'aclaracion', 'otro']);
            $table->foreignId('subido_por_usuario_id')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamp('fecha_subida')->useCurrent();
        });

        // Requisitos documentales que el Contratista debe cumplir
        Schema::create('requisitos_documentos_licitacion', function (Blueprint $table) {
            $table->id();
            $table->foreignId('licitacion_id')->constrained('licitaciones')->onDelete('cascade');
            $table->string('nombre_requisito');
            $table->text('descripcion_requisito')->nullable();
            $table->boolean('es_obligatorio')->default(true);
            $table->unsignedInteger('orden')->default(0);
            $table->timestamps();

            $table->index('licitacion_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('requisitos_documentos_licitacion');
        Schema::dropIfExists('documentos_licitacion');
    }
};
