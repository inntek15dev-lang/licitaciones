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
        Schema::table('documentos_licitacion', function (Blueprint $table) {
            $table->boolean('es_precalificacion')->default(false)->after('tipo_documento');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('documentos_licitacion', function (Blueprint $table) {
            $table->dropColumn('es_precalificacion');
        });
    }
};
