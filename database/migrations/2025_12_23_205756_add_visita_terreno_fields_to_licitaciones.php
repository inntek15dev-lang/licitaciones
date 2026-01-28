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
            // Campos adicionales para visita a terreno
            $table->string('lugar_visita_terreno')->nullable()->after('contacto_visita_terreno');
            $table->string('email_contacto_visita')->nullable()->after('lugar_visita_terreno');
            $table->string('telefono_contacto_visita')->nullable()->after('email_contacto_visita');
            $table->boolean('visita_terreno_obligatoria')->default(false)->after('telefono_contacto_visita');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('licitaciones', function (Blueprint $table) {
            $table->dropColumn([
                'lugar_visita_terreno',
                'email_contacto_visita',
                'telefono_contacto_visita',
                'visita_terreno_obligatoria',
            ]);
        });
    }
};
