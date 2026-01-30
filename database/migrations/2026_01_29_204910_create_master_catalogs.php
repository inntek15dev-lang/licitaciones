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
        Schema::create('cat_estados', function (Blueprint $table) {
            $table->id();
            $table->string('nombre_estado', 50);
            $table->timestamps();
        });

        Schema::create('cat_tipos_licitacion', function (Blueprint $table) {
            $table->id();
            $table->string('nombre', 50);
            $table->timestamps();
        });

        Schema::table('licitaciones', function (Blueprint $table) {
            $table->foreignId('estado_id')->nullable()->after('estado')->constrained('cat_estados');
            $table->foreignId('tipo_cat_id')->nullable()->after('tipo_licitacion')->constrained('cat_tipos_licitacion');
        });
    }

    public function down(): void
    {
        Schema::table('licitaciones', function (Blueprint $table) {
            $table->dropForeign(['estado_id']);
            $table->dropColumn('estado_id');
            $table->dropForeign(['tipo_cat_id']);
            $table->dropColumn('tipo_cat_id');
        });
        Schema::dropIfExists('cat_tipos_licitacion');
        Schema::dropIfExists('cat_estados');
    }
};
