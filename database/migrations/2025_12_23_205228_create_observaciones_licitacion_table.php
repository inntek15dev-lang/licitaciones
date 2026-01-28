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
        Schema::create('observaciones_licitacion', function (Blueprint $table) {
            $table->id();
            $table->foreignId('licitacion_id')->constrained('licitaciones')->onDelete('cascade');
            $table->foreignId('usuario_revisor_id')->constrained('users')->onDelete('cascade');
            $table->text('observacion');
            $table->timestamp('fecha_observacion')->useCurrent();
            $table->boolean('resuelta')->default(false);
            $table->timestamp('fecha_resolucion')->nullable();
            
            $table->index('licitacion_id');
            $table->index('usuario_revisor_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('observaciones_licitacion');
    }
};
