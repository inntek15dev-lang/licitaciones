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
        Schema::table('licitaciones', function (Blueprint $table) {
            // Campos de precalificación
            $table->boolean('requiere_precalificacion')->default(false)->after('visita_terreno_obligatoria');
            $table->dateTime('fecha_inicio_precalificacion')->nullable()->after('requiere_precalificacion');
            $table->dateTime('fecha_fin_precalificacion')->nullable()->after('fecha_inicio_precalificacion');
            
            // Entrevista para precalificación
            $table->boolean('requiere_entrevista')->default(false)->after('fecha_fin_precalificacion');
            $table->dateTime('fecha_entrevista')->nullable()->after('requiere_entrevista');
            $table->string('lugar_entrevista')->nullable()->after('fecha_entrevista');
            $table->text('notas_precalificacion')->nullable()->after('lugar_entrevista');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('licitaciones', function (Blueprint $table) {
            $table->dropColumn([
                'requiere_precalificacion',
                'fecha_inicio_precalificacion',
                'fecha_fin_precalificacion',
                'requiere_entrevista',
                'fecha_entrevista',
                'lugar_entrevista',
                'notas_precalificacion',
            ]);
        });
    }
};
