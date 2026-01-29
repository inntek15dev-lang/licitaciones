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

            // 1. Financial KPIs (Specific Matrix Ratios)
            $table->decimal('ind_liquidez', 8, 2)->nullable()->comment('Ind. Liquidez');
            $table->decimal('ind_leverage', 8, 2)->nullable()->comment('Ind. Leverage (Endeudamiento)');
            $table->decimal('monto_ebitda', 15, 2)->nullable()->comment('EBITDA ($)');
            $table->decimal('deuda_comercial_monto', 15, 2)->default(0)->comment('D. COM ($)');
            $table->boolean('deuda_tributaria_al_dia')->default(true)->comment('D. TRI (Status)');

            // 2. HSE Comparative (Periodo Anterior vs Actual)
            $table->decimal('hse_tat_anterior', 5, 2)->default(0)->comment('T.A.T Periodo Anterior');
            $table->decimal('hse_tst_anterior', 5, 2)->default(0)->comment('T.S.T Periodo Anterior');
            $table->decimal('hse_tat_actual', 5, 2)->default(0)->comment('T.A.T Periodo Actual');
            $table->decimal('hse_tst_actual', 5, 2)->default(0)->comment('T.S.T Periodo Actual');

            // 3. Labor & Legal Status
            $table->boolean('cumple_legal_vigencia')->default(true)->comment('Vigencia Legal');
            $table->boolean('cumple_laboral_multas')->default(true)->comment('Sin Multas Laborales');
            $table->boolean('cumple_laboral_deuda')->default(true)->comment('Sin Deuda Previsional');

            // 4. Scoring & Ranking
            $table->decimal('score_ranking', 5, 2)->nullable()->comment('RKG % Global');
            $table->decimal('score_seguridad', 5, 2)->nullable()->comment('SEG % Seguridad');

            // 5. Audit
            $table->boolean('bloqueado_por_migracion')->default(false);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('precalificaciones_contratistas', function (Blueprint $table) {
            $table->dropColumn([
                'ind_liquidez',
                'ind_leverage',
                'monto_ebitda',
                'deuda_comercial_monto',
                'deuda_tributaria_al_dia',
                'hse_tat_anterior',
                'hse_tst_anterior',
                'hse_tat_actual',
                'hse_tst_actual',
                'cumple_legal_vigencia',
                'cumple_laboral_multas',
                'cumple_laboral_deuda',
                'score_ranking',
                'score_seguridad',
                'bloqueado_por_migracion'
            ]);
        });
    }
};
