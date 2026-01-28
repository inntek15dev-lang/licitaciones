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
        Schema::table('users', function (Blueprint $table) {
            $table->string('nombre_completo')->nullable()->after('name');
            $table->unsignedBigInteger('empresa_principal_id')->nullable()->after('password');
            $table->unsignedBigInteger('empresa_contratista_id')->nullable()->after('empresa_principal_id');
            $table->boolean('activo')->default(true)->after('empresa_contratista_id');
            $table->timestamp('ultimo_login')->nullable()->after('activo');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'nombre_completo',
                'empresa_principal_id',
                'empresa_contratista_id',
                'activo',
                'ultimo_login',
            ]);
        });
    }
};

