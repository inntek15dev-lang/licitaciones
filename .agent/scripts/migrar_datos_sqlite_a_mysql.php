<?php
/**
 * Script para migrar datos de SQLite a MySQL
 * Ejecutar: php migrar_datos_sqlite_a_mysql.php
 */

require __DIR__ . '/vendor/autoload.php';

// Cargar la aplicación Laravel
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

echo "🚀 Iniciando migración de datos SQLite → MySQL\n\n";

// Conectar a SQLite (fuente)
$sqlitePath = __DIR__ . '/database/database_backup_2026-01-12.sqlite';

if (!file_exists($sqlitePath)) {
    die("❌ No se encontró el archivo SQLite: {$sqlitePath}\n");
}

$sqlite = new PDO("sqlite:{$sqlitePath}");
$sqlite->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

// Tablas a migrar (en orden por dependencias)
$tablas = [
    'users',
    'dependencias',
    'programas',
    'tipos_contratistas',
    'elementos',
    'actividades',
    'contratista_asignaciones',
    'registros',
    'registro_actividades',
    'evidencias',
    'hallazgos',
    'auditoria_comentarios',
    'solicitudes_reapertura',
    'registro_logs',
    'configuraciones',
];

// Deshabilitar verificación de foreign keys temporalmente
DB::statement('SET FOREIGN_KEY_CHECKS=0');

foreach ($tablas as $tabla) {
    echo "📋 Migrando tabla: {$tabla}... ";
    
    try {
        // Verificar si la tabla existe en SQLite
        $checkTable = $sqlite->query("SELECT name FROM sqlite_master WHERE type='table' AND name='{$tabla}'");
        if ($checkTable->fetch() === false) {
            echo "⏭️ No existe en SQLite\n";
            continue;
        }
        
        // Obtener datos de SQLite
        $stmt = $sqlite->query("SELECT * FROM {$tabla}");
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        if (empty($rows)) {
            echo "⏭️ Sin datos\n";
            continue;
        }
        
        // Limpiar tabla MySQL (excepto migrations)
        if ($tabla !== 'migrations') {
            DB::table($tabla)->truncate();
        }
        
        // Insertar en chunks para evitar problemas de memoria
        $chunks = array_chunk($rows, 100);
        $total = 0;
        
        foreach ($chunks as $chunk) {
            // Filtrar columnas que no existan en MySQL
            $columnasMysql = Schema::getColumnListing($tabla);
            
            $chunkFiltrado = array_map(function($row) use ($columnasMysql) {
                return array_intersect_key($row, array_flip($columnasMysql));
            }, $chunk);
            
            DB::table($tabla)->insert($chunkFiltrado);
            $total += count($chunk);
        }
        
        echo "✅ {$total} registros\n";
        
    } catch (\Exception $e) {
        echo "❌ Error: " . $e->getMessage() . "\n";
    }
}

// Migrar tabla de roles y permisos de Spatie
$tablasSpatie = [
    'roles',
    'permissions', 
    'model_has_roles',
    'model_has_permissions',
    'role_has_permissions',
];

foreach ($tablasSpatie as $tabla) {
    echo "📋 Migrando tabla Spatie: {$tabla}... ";
    
    try {
        $checkTable = $sqlite->query("SELECT name FROM sqlite_master WHERE type='table' AND name='{$tabla}'");
        if ($checkTable->fetch() === false) {
            echo "⏭️ No existe\n";
            continue;
        }
        
        $stmt = $sqlite->query("SELECT * FROM {$tabla}");
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        if (empty($rows)) {
            echo "⏭️ Sin datos\n";
            continue;
        }
        
        // Las tablas de Spatie ya tienen datos del seeder, solo actualizar si hay más
        $existentes = DB::table($tabla)->count();
        if ($existentes >= count($rows)) {
            echo "⏭️ Ya tiene {$existentes} registros\n";
            continue;
        }
        
        DB::table($tabla)->truncate();
        
        $chunks = array_chunk($rows, 100);
        $total = 0;
        
        foreach ($chunks as $chunk) {
            DB::table($tabla)->insert($chunk);
            $total += count($chunk);
        }
        
        echo "✅ {$total} registros\n";
        
    } catch (\Exception $e) {
        echo "❌ Error: " . $e->getMessage() . "\n";
    }
}

// Rehabilitar foreign keys
DB::statement('SET FOREIGN_KEY_CHECKS=1');

echo "\n✅ ¡Migración completada!\n";
echo "\n📊 Resumen de datos en MySQL:\n";

foreach ($tablas as $tabla) {
    try {
        $count = DB::table($tabla)->count();
        echo "   - {$tabla}: {$count} registros\n";
    } catch (\Exception $e) {
        // Tabla no existe o error
    }
}

echo "\n🔑 Ahora puedes ingresar al sistema con los usuarios que tenías en SQLite.\n";
