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
        Schema::table('precalificaciones_contratistas', function (Blueprint $table) {
            // Organization
            $table->integer('nro_trabajadores')->nullable();
            $table->integer('anios_experiencia')->nullable();

            // Financial
            $table->decimal('capital_social', 15, 2)->nullable();
            $table->decimal('patrimonio_neto', 15, 2)->nullable();
            $table->decimal('ventas_ultimo_anio', 15, 2)->nullable();
            $table->string('moneda_financiera')->default('CLP'); // CLP, USD, UF

            // HSE (Safety)
            $table->decimal('tasa_accidentabilidad', 5, 2)->nullable(); // Last year
            $table->decimal('tasa_siniestralidad', 5, 2)->nullable(); // Last year
            $table->boolean('tiene_programa_prevencion')->default(false);

            // Quality / ISO
            $table->boolean('tiene_iso_9001')->default(false); // Quality
            $table->boolean('tiene_iso_14001')->default(false); // Environment
            $table->boolean('tiene_iso_45001')->default(false); // Health & Safety

            // Legal
            $table->string('nombre_representante_legal')->nullable();
            $table->string('rut_representante_legal')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('precalificaciones_contratistas', function (Blueprint $table) {
            $table->dropColumn([
                'nro_trabajadores',
                'anios_experiencia',
                'capital_social',
                'patrimonio_neto',
                'ventas_ultimo_anio',
                'moneda_financiera',
                'tasa_accidentabilidad',
                'tasa_siniestralidad',
                'tiene_programa_prevencion',
                'tiene_iso_9001',
                'tiene_iso_14001',
                'tiene_iso_45001',
                'nombre_representante_legal',
                'rut_representante_legal',
            ]);
        });
    }
};
