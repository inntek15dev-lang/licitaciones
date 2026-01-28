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
            // Quién puede precalificar: ryce, principal, ambos
            $table->enum('responsable_precalificacion', ['ryce', 'principal', 'ambos'])->default('ryce')->after('requiere_precalificacion');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('licitaciones', function (Blueprint $table) {
            $table->dropColumn('responsable_precalificacion');
        });
    }
};
