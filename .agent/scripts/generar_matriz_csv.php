<?php
/**
 * Script para generar Matriz de Funcionalidades en formato CSV (compatible con Excel)
 * Ejecutar desde la raíz del proyecto: php generar_matriz_csv.php
 */

echo "🚀 Generando Matriz de Funcionalidades por Rol...\n\n";

// BOM para UTF-8 en Excel
$bom = chr(0xEF) . chr(0xBB) . chr(0xBF);

$modulos = [
    [
        'modulo' => '1. DASHBOARD',
        'funcionalidades' => [
            ['1.1', 'Administrador', 'Ver Dashboard con KPIs generales', '✅ Actual', '', ''],
            ['1.2', 'Administrador', 'Ver cantidad total de contratistas', '✅ Actual', '', ''],
            ['1.3', 'Administrador', 'Ver cantidad total de registros', '✅ Actual', '', ''],
            ['1.4', 'Administrador', 'Ver cantidad total de evidencias', '✅ Actual', '', ''],
            ['1.5', 'Administrador', 'Ver porcentaje de cumplimiento general', '✅ Actual', '', ''],
            ['1.6', 'Administrador', 'Ver tabla de registros recientes', '✅ Actual', '', ''],
            ['1.7', 'Administrador', 'Filtrar por EECC, Dependencia, Periodo', '✅ Actual', '', ''],
            ['1.8', 'Admin Contrato', 'Ver Dashboard limitado a sus contratistas', '✅ Actual', '', ''],
            ['1.9', 'Admin Contrato', 'Ver "Mis Contratistas" (solo asignados)', '✅ Actual', '', ''],
            ['1.10', 'Admin Contrato', 'Ver KPIs solo de sus contratistas', '✅ Actual', '', ''],
            ['1.11', 'Contratista', 'Ver Dashboard con su cumplimiento', '✅ Actual', '', ''],
            ['1.12', 'Contratista', 'Ver semáforo visual (verde/amarillo/rojo)', '✅ Actual', '', ''],
            ['1.13', 'Contratista', 'Ver meta del programa', '✅ Actual', '', ''],
            ['1.14', 'Contratista', 'Ver botón "Nuevo Registro" para el periodo actual', '✅ Actual', '', ''],
            ['1.15', 'Contratista', 'Ver servicios/dependencias asignados', '✅ Actual', '', ''],
        ]
    ],
    [
        'modulo' => '2. GESTIÓN DE REGISTROS',
        'funcionalidades' => [
            ['2.1', 'Administrador', 'Ver lista de todos los registros', '✅ Actual', '', ''],
            ['2.2', 'Administrador', 'Filtrar por EECC', '✅ Actual', '', ''],
            ['2.3', 'Administrador', 'Filtrar por Dependencia', '✅ Actual', '', ''],
            ['2.4', 'Administrador', 'Filtrar por Periodo (mes/año)', '✅ Actual', '', ''],
            ['2.5', 'Administrador', 'Filtrar por Estado de Auditoría', '✅ Actual', '', ''],
            ['2.6', 'Administrador', 'Ordenar por columnas', '✅ Actual', '', ''],
            ['2.7', 'Administrador', 'Ver detalle de un registro', '✅ Actual', '', ''],
            ['2.8', 'Administrador', 'Exportar registro a PDF', '✅ Actual', '', ''],
            ['2.9', 'Administrador', 'Ver trazabilidad (logs) del registro', '✅ Actual', '', ''],
            ['2.10', 'Administrador', 'Exportar trazabilidad a PDF', '✅ Actual', '', ''],
            ['2.11', 'Administrador', '⚠️ Reabrir registro auditado SIN solicitud', '✅ Actual', '', '¿Solo admin dios?'],
            ['2.12', 'Administrador', '⚠️ Eliminar registro', '✅ Actual', '', '¿Desactivar en producción?'],
            ['2.13', 'Admin Contrato', 'Ver registros solo de sus contratistas asignados', '✅ Actual', '', ''],
            ['2.14', 'Admin Contrato', 'Filtrar por EECC (solo sus asignados)', '✅ Actual', '', ''],
            ['2.15', 'Admin Contrato', 'Ver detalle de registro de sus contratistas', '✅ Actual', '', ''],
            ['2.16', 'Admin Contrato', 'Exportar registro a PDF', '✅ Actual', '', ''],
            ['2.17', 'Admin Contrato', 'Ver trazabilidad del registro', '✅ Actual', '', ''],
            ['2.18', 'Admin Contrato', 'NO puede eliminar registros', '❌ Bloqueado', '', ''],
            ['2.19', 'Admin Contrato', 'NO puede reabrir directamente (usa solicitud)', '❌ Bloqueado', '', ''],
            ['2.20', 'Contratista', 'Crear nuevo registro mensual', '✅ Actual', '', ''],
            ['2.21', 'Contratista', 'Seleccionar servicio/dependencia (si tiene varios)', '✅ Actual', '', ''],
            ['2.22', 'Contratista', 'Marcar cumple/no cumple por actividad', '✅ Actual', '', ''],
            ['2.23', 'Contratista', 'Agregar responsable por actividad', '✅ Actual', '', ''],
            ['2.24', 'Contratista', 'Agregar observaciones por actividad', '✅ Actual', '', ''],
            ['2.25', 'Contratista', 'Subir evidencias (hasta 4 por actividad)', '✅ Actual', '', ''],
            ['2.26', 'Contratista', 'Eliminar evidencia pendiente (antes de guardar)', '✅ Actual', '', ''],
            ['2.27', 'Contratista', 'Guardar registro (envío)', '✅ Actual', '', ''],
            ['2.28', 'Contratista', 'Editar registro NO auditado', '✅ Actual', '', ''],
            ['2.29', 'Contratista', 'Ver historial de sus registros', '✅ Actual', '', ''],
            ['2.30', 'Contratista', 'Ver detalle de su registro', '✅ Actual', '', ''],
            ['2.31', 'Contratista', 'Exportar registro a PDF', '✅ Actual', '', ''],
            ['2.32', 'Contratista', 'Ver trazabilidad de su registro', '✅ Actual', '', ''],
            ['2.33', 'Contratista', 'Editar registro REABIERTO (subsanación)', '✅ Actual', '', ''],
            ['2.34', 'Contratista', 'NO puede editar registro auditado', '❌ Bloqueado', '', ''],
            ['2.35', 'Contratista', 'NO puede eliminar registros', '❌ Bloqueado', '', ''],
        ]
    ],
    [
        'modulo' => '3. AUDITORÍA',
        'funcionalidades' => [
            ['3.1', 'Administrador', 'Iniciar auditoría de cualquier registro', '✅ Actual', '', ''],
            ['3.2', 'Administrador', 'Marcar cumple/no cumple auditor por actividad', '✅ Actual', '', ''],
            ['3.3', 'Administrador', 'Agregar observación de auditor por actividad', '✅ Actual', '', ''],
            ['3.4', 'Administrador', 'Agregar comentarios de auditoría al registro', '✅ Actual', '', ''],
            ['3.5', 'Administrador', 'Seleccionar tipo de auditoría (Sistema/Terreno)', '✅ Actual', '', ''],
            ['3.6', 'Administrador', 'Finalizar auditoría', '✅ Actual', '', ''],
            ['3.7', 'Administrador', 'Pausar y continuar auditoría después', '✅ Actual', '', ''],
            ['3.8', 'Administrador', 'Registrar hallazgos', '✅ Actual', '', ''],
            ['3.9', 'Administrador', 'Cambiar estado de hallazgo (abierto/cerrado)', '✅ Actual', '', ''],
            ['3.10', 'Admin Contrato', 'Iniciar auditoría solo de sus contratistas', '✅ Actual', '', ''],
            ['3.11', 'Admin Contrato', 'Marcar cumple/no cumple auditor por actividad', '✅ Actual', '', ''],
            ['3.12', 'Admin Contrato', 'Agregar observación de auditor por actividad', '✅ Actual', '', ''],
            ['3.13', 'Admin Contrato', 'Agregar comentarios de auditoría', '✅ Actual', '', ''],
            ['3.14', 'Admin Contrato', 'Seleccionar tipo de auditoría', '✅ Actual', '', ''],
            ['3.15', 'Admin Contrato', 'Finalizar auditoría', '✅ Actual', '', ''],
            ['3.16', 'Admin Contrato', 'Pausar y continuar auditoría después', '✅ Actual', '', ''],
            ['3.17', 'Admin Contrato', 'Registrar hallazgos', '✅ Actual', '', ''],
            ['3.18', 'Contratista', 'Ver resultado de auditoría en su registro', '✅ Actual', '', ''],
            ['3.19', 'Contratista', 'Ver comentarios del auditor', '✅ Actual', '', ''],
            ['3.20', 'Contratista', 'Ver hallazgos registrados', '✅ Actual', '', ''],
            ['3.21', 'Contratista', 'NO puede auditar', '❌ Bloqueado', '', ''],
        ]
    ],
    [
        'modulo' => '4. SOLICITUDES DE REAPERTURA',
        'funcionalidades' => [
            ['4.1', 'Administrador', 'Ver todas las solicitudes de reapertura', '✅ Actual', '', ''],
            ['4.2', 'Administrador', 'Filtrar solicitudes por estado', '✅ Actual', '', ''],
            ['4.3', 'Administrador', 'Aprobar solicitud de reapertura', '✅ Actual', '', ''],
            ['4.4', 'Administrador', 'Definir fecha límite de subsanación', '✅ Actual', '', ''],
            ['4.5', 'Administrador', 'Rechazar solicitud con comentario', '✅ Actual', '', ''],
            ['4.6', 'Admin Contrato', 'Ver solicitudes solo de sus contratistas', '✅ Actual', '', ''],
            ['4.7', 'Admin Contrato', 'Aprobar solicitud de reapertura', '✅ Actual', '', ''],
            ['4.8', 'Admin Contrato', 'Definir fecha límite de subsanación', '✅ Actual', '', ''],
            ['4.9', 'Admin Contrato', 'Rechazar solicitud con comentario', '✅ Actual', '', ''],
            ['4.10', 'Contratista', 'Crear solicitud de reapertura', '✅ Actual', '', ''],
            ['4.11', 'Contratista', 'Escribir motivo de la solicitud', '✅ Actual', '', ''],
            ['4.12', 'Contratista', 'Ver estado de sus solicitudes', '✅ Actual', '', ''],
            ['4.13', 'Contratista', 'Ver fecha límite de subsanación', '✅ Actual', '', ''],
            ['4.14', 'Contratista', 'Recibir email cuando solicitud es resuelta', '✅ Actual', '', ''],
            ['4.15', 'Contratista', 'NO puede aprobar/rechazar solicitudes', '❌ Bloqueado', '', ''],
        ]
    ],
    [
        'modulo' => '5. GESTIÓN DE CONTRATISTAS',
        'funcionalidades' => [
            ['5.1', 'Administrador', 'Ver lista de todos los contratistas', '✅ Actual', '', ''],
            ['5.2', 'Administrador', 'Filtrar por EECC, Dependencia, Servicio', '✅ Actual', '', ''],
            ['5.3', 'Administrador', '⚠️ Crear nuevo contratista manualmente', '✅ Actual', '', '¿Bloquear cuando ACEM esté conectado?'],
            ['5.4', 'Administrador', '⚠️ Editar datos del contratista', '✅ Actual', '', '¿Deben poder editar datos maestros?'],
            ['5.5', 'Administrador', 'Ver detalle de contratista', '✅ Actual', '', ''],
            ['5.6', 'Administrador', 'Agregar asignación servicio+dependencia', '✅ Actual', '', ''],
            ['5.7', 'Administrador', 'Editar asignación (admin contrato, fecha inicio)', '✅ Actual', '', ''],
            ['5.8', 'Administrador', 'Eliminar asignación', '✅ Actual', '', ''],
            ['5.9', 'Administrador', 'Asignar administrador de contrato', '✅ Actual', '', ''],
            ['5.10', 'Administrador', 'Activar/Desactivar contratista', '✅ Actual', '', ''],
            ['5.11', 'Administrador', '⚠️ Eliminar contratista', '✅ Actual', '', '¿Ocultar este botón?'],
            ['5.12', 'Administrador', 'Ver usuarios asociados del contratista', '✅ Actual', '', ''],
            ['5.13', 'Administrador', 'Agregar usuario asociado al contratista', '✅ Actual', '', ''],
            ['5.14', 'Admin Contrato', 'Ver solo sus contratistas asignados', '✅ Actual', '', ''],
            ['5.15', 'Admin Contrato', 'Ver detalle del contratista', '✅ Actual', '', ''],
            ['5.16', 'Admin Contrato', 'NO puede crear contratistas', '❌ Bloqueado', '', ''],
            ['5.17', 'Admin Contrato', 'NO puede editar contratistas', '❌ Bloqueado', '', ''],
            ['5.18', 'Admin Contrato', 'NO puede eliminar contratistas', '❌ Bloqueado', '', ''],
            ['5.19', 'Admin Contrato', 'NO puede cambiar asignaciones', '❌ Bloqueado', '', ''],
            ['5.20', 'Contratista', 'Ver sus propios datos', '✅ Actual', '', ''],
            ['5.21', 'Contratista', '⚠️ Crear usuarios operativos propios', '✅ Actual', '', '¿Solo contratista o también admin?'],
            ['5.22', 'Contratista', 'Editar usuarios asociados', '✅ Actual', '', ''],
            ['5.23', 'Contratista', 'Eliminar usuarios asociados', '✅ Actual', '', ''],
            ['5.24', 'Contratista', 'Asignar servicio/dependencia a usuario operativo', '✅ Actual', '', ''],
            ['5.25', 'Contratista', 'NO puede editar sus datos maestros', '❌ Bloqueado', '', ''],
            ['5.26', 'Contratista', 'NO puede ver otros contratistas', '❌ Bloqueado', '', ''],
        ]
    ],
    [
        'modulo' => '6. USUARIOS ABASTIBLE',
        'funcionalidades' => [
            ['6.1', 'Administrador', 'Ver lista de usuarios admin/admin_contrato', '✅ Actual', '', ''],
            ['6.2', 'Administrador', 'Filtrar por rol', '✅ Actual', '', ''],
            ['6.3', 'Administrador', 'Buscar por nombre/email', '✅ Actual', '', ''],
            ['6.4', 'Administrador', 'Crear nuevo usuario admin', '✅ Actual', '', ''],
            ['6.5', 'Administrador', 'Crear nuevo usuario admin_contrato', '✅ Actual', '', ''],
            ['6.6', 'Administrador', 'Editar usuario', '✅ Actual', '', ''],
            ['6.7', 'Administrador', 'Cambiar contraseña de usuario', '✅ Actual', '', ''],
            ['6.8', 'Administrador', 'Cambiar rol de usuario', '✅ Actual', '', ''],
            ['6.9', 'Administrador', 'Eliminar usuario', '✅ Actual', '', ''],
            ['6.10', 'Administrador', 'Activar/Desactivar usuario', '✅ Actual', '', ''],
            ['6.11', 'Admin Contrato', 'NO tiene acceso a gestión de usuarios', '❌ Bloqueado', '', ''],
            ['6.12', 'Admin Contrato', 'Editar su propio perfil', '✅ Actual', '', ''],
            ['6.13', 'Contratista', 'NO tiene acceso a este módulo', '❌ Bloqueado', '', ''],
        ]
    ],
    [
        'modulo' => '7. PROGRAMAS, ELEMENTOS Y ACTIVIDADES',
        'funcionalidades' => [
            ['7.1', 'Administrador', 'Ver lista de programas', '✅ Actual', '', ''],
            ['7.2', 'Administrador', 'Crear nuevo programa', '✅ Actual', '', ''],
            ['7.3', 'Administrador', 'Editar programa', '✅ Actual', '', ''],
            ['7.4', 'Administrador', 'Eliminar programa', '✅ Actual', '', ''],
            ['7.5', 'Administrador', 'Ver elementos de un programa', '✅ Actual', '', ''],
            ['7.6', 'Administrador', 'Crear nuevo elemento', '✅ Actual', '', ''],
            ['7.7', 'Administrador', 'Editar elemento', '✅ Actual', '', ''],
            ['7.8', 'Administrador', 'Eliminar elemento', '✅ Actual', '', ''],
            ['7.9', 'Administrador', 'Ver actividades de un elemento', '✅ Actual', '', ''],
            ['7.10', 'Administrador', 'Crear nueva actividad', '✅ Actual', '', ''],
            ['7.11', 'Administrador', 'Editar actividad (criterios, frecuencia, etc.)', '✅ Actual', '', ''],
            ['7.12', 'Administrador', 'Eliminar actividad', '✅ Actual', '', ''],
            ['7.13', 'Administrador', 'Marcar actividad como requiere evidencia', '✅ Actual', '', ''],
            ['7.14', 'Admin Contrato', 'Ver programas (solo lectura)', '✅ Actual', '', ''],
            ['7.15', 'Admin Contrato', 'Ver elementos (solo lectura)', '✅ Actual', '', ''],
            ['7.16', 'Admin Contrato', 'Ver actividades (solo lectura)', '✅ Actual', '', ''],
            ['7.17', 'Admin Contrato', 'NO puede crear/editar/eliminar', '❌ Bloqueado', '', ''],
            ['7.18', 'Contratista', 'Ver actividades de su programa (en formulario)', '✅ Actual', '', ''],
            ['7.19', 'Contratista', 'Ver criterios de aprobación', '✅ Actual', '', ''],
            ['7.20', 'Contratista', 'NO puede editar programas/elementos/actividades', '❌ Bloqueado', '', ''],
        ]
    ],
    [
        'modulo' => '8. SERVICIOS (Tipos de Contratista)',
        'funcionalidades' => [
            ['8.1', 'Administrador', 'Ver lista de servicios', '✅ Actual', '', ''],
            ['8.2', 'Administrador', 'Crear nuevo servicio', '✅ Actual', '', ''],
            ['8.3', 'Administrador', 'Editar servicio', '✅ Actual', '', ''],
            ['8.4', 'Administrador', 'Asignar programa al servicio', '✅ Actual', '', ''],
            ['8.5', 'Administrador', 'Eliminar servicio', '✅ Actual', '', ''],
            ['8.6', 'Administrador', 'Activar/Desactivar servicio', '✅ Actual', '', ''],
            ['8.7', 'Admin Contrato', 'Ver lista de servicios (solo lectura)', '✅ Actual', '', ''],
            ['8.8', 'Admin Contrato', 'NO puede crear/editar/eliminar servicios', '❌ Bloqueado', '', ''],
            ['8.9', 'Contratista', 'Ver sus servicios asignados', '✅ Actual', '', ''],
            ['8.10', 'Contratista', 'NO puede acceder a este módulo', '❌ Bloqueado', '', ''],
        ]
    ],
    [
        'modulo' => '9. DEPENDENCIAS (Plantas)',
        'funcionalidades' => [
            ['9.1', 'Administrador', 'Ver lista de dependencias', '✅ Actual', '', ''],
            ['9.2', 'Administrador', '⚠️ Crear nueva dependencia', '✅ Actual', '', '¿Bloquear cuando ACEM esté conectado?'],
            ['9.3', 'Administrador', 'Editar dependencia', '✅ Actual', '', ''],
            ['9.4', 'Administrador', 'Eliminar dependencia', '✅ Actual', '', ''],
            ['9.5', 'Admin Contrato', 'Ver dependencias (solo lectura)', '✅ Actual', '', ''],
            ['9.6', 'Admin Contrato', 'NO puede crear/editar/eliminar', '❌ Bloqueado', '', ''],
            ['9.7', 'Contratista', 'NO tiene acceso a este módulo', '❌ Bloqueado', '', ''],
        ]
    ],
    [
        'modulo' => '10. EVIDENCIAS',
        'funcionalidades' => [
            ['10.1', 'Administrador', 'Ver lista consolidada de todas las evidencias', '✅ Actual', '', ''],
            ['10.2', 'Administrador', 'Visualizar evidencia en navegador', '✅ Actual', '', ''],
            ['10.3', 'Administrador', 'Descargar evidencia', '✅ Actual', '', ''],
            ['10.4', 'Administrador', 'Filtrar evidencias', '✅ Actual', '', ''],
            ['10.5', 'Admin Contrato', 'Ver evidencias de sus contratistas', '✅ Actual', '', ''],
            ['10.6', 'Admin Contrato', 'Visualizar evidencia en navegador', '✅ Actual', '', ''],
            ['10.7', 'Admin Contrato', 'Descargar evidencia', '✅ Actual', '', ''],
            ['10.8', 'Contratista', 'Ver sus propias evidencias', '✅ Actual', '', ''],
            ['10.9', 'Contratista', 'Subir evidencia (hasta 4 por actividad)', '✅ Actual', '', ''],
            ['10.10', 'Contratista', 'Visualizar evidencia en navegador', '✅ Actual', '', ''],
            ['10.11', 'Contratista', 'Descargar evidencia', '✅ Actual', '', ''],
            ['10.12', 'Contratista', 'Eliminar evidencia (antes de auditoría)', '✅ Actual', '', ''],
        ]
    ],
    [
        'modulo' => '11. REPORTES',
        'funcionalidades' => [
            ['11.1', 'Administrador', 'Ver reportes consolidados', '✅ Actual', '', ''],
            ['11.2', 'Administrador', 'Filtrar por periodo, EECC, dependencia', '✅ Actual', '', ''],
            ['11.3', 'Administrador', 'Exportar a Excel', '✅ Actual', '', ''],
            ['11.4', 'Administrador', 'Exportar a PDF', '✅ Actual', '', ''],
            ['11.5', 'Admin Contrato', 'Ver reportes de sus contratistas', '✅ Actual', '', ''],
            ['11.6', 'Admin Contrato', 'Exportar a Excel', '✅ Actual', '', ''],
            ['11.7', 'Admin Contrato', 'Exportar a PDF', '✅ Actual', '', ''],
            ['11.8', 'Contratista', 'NO tiene acceso a reportes consolidados', '❌ Bloqueado', '', ''],
        ]
    ],
    [
        'modulo' => '12. NOTIFICACIONES EMAIL',
        'funcionalidades' => [
            ['12.1', 'Sistema', 'Email: Solicitud de reapertura creada → Admin de Contrato', '✅ Actual', '', ''],
            ['12.2', 'Sistema', 'Email: Solicitud aprobada → Contratista', '✅ Actual', '', ''],
            ['12.3', 'Sistema', 'Email: Solicitud rechazada → Contratista', '✅ Actual', '', ''],
            ['12.4', 'Sistema', '⚠️ Email: Auditoría completada → Contratista', '❌ No implementado', '', '¿Agregar?'],
            ['12.5', 'Sistema', '⚠️ Email: Fecha límite próxima a vencer → Contratista', '❌ No implementado', '', '¿Agregar?'],
            ['12.6', 'Sistema', '⚠️ Email: Nuevo registro enviado → Admin de Contrato', '❌ No implementado', '', '¿Agregar?'],
        ]
    ],
];

// Crear archivo CSV
$filename = __DIR__ . '/MATRIZ_FUNCIONALIDADES_OIEM.csv';
$fp = fopen($filename, 'w');

// Escribir BOM
fwrite($fp, $bom);

// Header
fputcsv($fp, ['MÓDULO', '#', 'ROL', 'FUNCIONALIDAD', 'ESTADO ACTUAL', 'DECISIÓN (Mantener/Eliminar/Mover/Agregar/Pendiente)', 'OBSERVACIONES'], ';');

// Escribir datos
foreach ($modulos as $modulo) {
    // Línea vacía entre módulos
    fputcsv($fp, ['', '', '', '', '', '', ''], ';');
    
    foreach ($modulo['funcionalidades'] as $func) {
        fputcsv($fp, [
            $modulo['modulo'],
            $func[0],
            $func[1],
            $func[2],
            $func[3],
            $func[4],
            $func[5]
        ], ';');
    }
}

fclose($fp);

echo "✅ Archivo CSV generado exitosamente:\n";
echo "   📁 {$filename}\n\n";
echo "📧 INSTRUCCIONES:\n";
echo "   1. Abre el archivo con Excel (doble clic)\n";
echo "   2. Si los datos no se separan en columnas:\n";
echo "      - Ir a Datos > Texto en columnas\n";
echo "      - Seleccionar 'Delimitado' > 'Punto y coma'\n";
echo "   3. La columna 'DECISIÓN' es para que Abastible complete\n";
echo "   4. Guardar como .xlsx para mejor formato\n\n";
