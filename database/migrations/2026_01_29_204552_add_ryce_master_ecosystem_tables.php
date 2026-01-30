<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // 1. Catálogo Motivos Rechazo
        Schema::create('cat_motivos_rechazo', function (Blueprint $table) {
            $table->id();
            $table->string('motivo', 100);
            $table->string('etapa_aplicable', 50)->comment('Inicial, Cierre, etc.');
            $table->timestamps();
        });

        // 2. Modificaciones a Licitaciones
        Schema::table('licitaciones', function (Blueprint $table) {
            $table->boolean('es_interesante')->default(false)->after('estado');
        });

        // 3. Revisiones de Calidad (Control Calidad)
        Schema::create('revisiones_calidad', function (Blueprint $table) {
            $table->id();
            $table->foreignId('licitacion_id')->constrained('licitaciones')->onDelete('cascade');
            $table->boolean('contiene_errores')->default(false);
            $table->text('observaciones')->nullable();
            $table->timestamps();
        });

        // 4. Lecciones Aprendidas (Cierre/Post-Mortem)
        Schema::create('lecciones_aprendidas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('licitacion_id')->constrained('licitaciones')->onDelete('cascade');
            $table->foreignId('motivo_id')->nullable()->constrained('cat_motivos_rechazo');
            $table->text('analisis_detalle')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('lecciones_aprendidas');
        Schema::dropIfExists('revisiones_calidad');
        Schema::table('licitaciones', function (Blueprint $table) {
            $table->dropColumn('es_interesante');
        });
        Schema::dropIfExists('cat_motivos_rechazo');
    }
};
