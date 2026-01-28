<?php
/**
 * Script para generar Matriz de Funcionalidades por Rol en Excel
 * Ejecutar desde la raíz del proyecto: php generar_matriz_excel.php
 */

require __DIR__ . '/vendor/autoload.php';

use PHPExcel;
use PHPExcel_IOFactory;
use PHPExcel_Style_Fill;
use PHPExcel_Style_Alignment;
use PHPExcel_Style_Border;
use PHPExcel_Cell_DataValidation;

echo "🚀 Generando Matriz de Funcionalidades por Rol...\n\n";

$excel = new PHPExcel();
$excel->getProperties()
    ->setCreator('OIEM Abastible')
    ->setTitle('Matriz de Funcionalidades por Rol')
    ->setDescription('Documento para revisión de Abastible');

// Estilos comunes
$headerStyle = [
    'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF'], 'size' => 11],
    'fill' => ['type' => PHPExcel_Style_Fill::FILL_SOLID, 'color' => ['rgb' => '1F4E79']],
    'alignment' => ['horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER, 'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER],
    'borders' => ['allborders' => ['style' => PHPExcel_Style_Border::BORDER_THIN]]
];

$subHeaderStyle = [
    'font' => ['bold' => true, 'size' => 10],
    'fill' => ['type' => PHPExcel_Style_Fill::FILL_SOLID, 'color' => ['rgb' => 'D6DCE4']],
    'borders' => ['allborders' => ['style' => PHPExcel_Style_Border::BORDER_THIN]]
];

$cellStyle = [
    'borders' => ['allborders' => ['style' => PHPExcel_Style_Border::BORDER_THIN]],
    'alignment' => ['vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER, 'wrapText' => true]
];

// Definición de módulos y funcionalidades
$modulos = [
    'RESUMEN' => [
        'titulo' => 'RESUMEN DE DECISIONES',
        'descripcion' => 'Vista consolidada de todas las decisiones',
        'tipo' => 'resumen'
    ],
    '1. Dashboard' => [
        'titulo' => 'MÓDULO 1: DASHBOARD',
        'roles' => [
            'Administrador (admin)' => [
                ['1.1', 'Ver Dashboard con KPIs generales', '✅ Actual', ''],
                ['1.2', 'Ver cantidad total de contratistas', '✅ Actual', ''],
                ['1.3', 'Ver cantidad total de registros', '✅ Actual', ''],
                ['1.4', 'Ver cantidad total de evidencias', '✅ Actual', ''],
                ['1.5', 'Ver porcentaje de cumplimiento general', '✅ Actual', ''],
                ['1.6', 'Ver tabla de registros recientes', '✅ Actual', ''],
                ['1.7', 'Filtrar por EECC, Dependencia, Periodo', '✅ Actual', ''],
            ],
            'Admin de Contrato (administrador_contrato)' => [
                ['1.8', 'Ver Dashboard limitado a sus contratistas asignados', '✅ Actual', ''],
                ['1.9', 'Ver "Mis Contratistas" (solo asignados)', '✅ Actual', ''],
                ['1.10', 'Ver KPIs solo de sus contratistas', '✅ Actual', ''],
            ],
            'Contratista (contratista)' => [
                ['1.11', 'Ver Dashboard con su cumplimiento', '✅ Actual', ''],
                ['1.12', 'Ver semáforo visual (verde/amarillo/rojo)', '✅ Actual', ''],
                ['1.13', 'Ver meta del programa', '✅ Actual', ''],
                ['1.14', 'Ver botón "Nuevo Registro" para el periodo actual', '✅ Actual', ''],
                ['1.15', 'Ver servicios/dependencias asignados', '✅ Actual', ''],
            ],
        ]
    ],
    '2. Registros' => [
        'titulo' => 'MÓDULO 2: GESTIÓN DE REGISTROS',
        'roles' => [
            'Administrador (admin)' => [
                ['2.1', 'Ver lista de todos los registros', '✅ Actual', ''],
                ['2.2', 'Filtrar por EECC', '✅ Actual', ''],
                ['2.3', 'Filtrar por Dependencia', '✅ Actual', ''],
                ['2.4', 'Filtrar por Periodo (mes/año)', '✅ Actual', ''],
                ['2.5', 'Filtrar por Estado de Auditoría', '✅ Actual', ''],
                ['2.6', 'Ordenar por columnas', '✅ Actual', ''],
                ['2.7', 'Ver detalle de un registro', '✅ Actual', ''],
                ['2.8', 'Exportar registro a PDF', '✅ Actual', ''],
                ['2.9', 'Ver trazabilidad (logs) del registro', '✅ Actual', ''],
                ['2.10', 'Exportar trazabilidad a PDF', '✅ Actual', ''],
                ['2.11', '⚠️ Reabrir registro auditado SIN solicitud', '✅ Actual', '¿Solo admin dios?'],
                ['2.12', '⚠️ Eliminar registro', '✅ Actual', '¿Desactivar en producción?'],
            ],
            'Admin de Contrato (administrador_contrato)' => [
                ['2.13', 'Ver registros solo de sus contratistas asignados', '✅ Actual', ''],
                ['2.14', 'Filtrar por EECC (solo sus asignados)', '✅ Actual', ''],
                ['2.15', 'Ver detalle de registro de sus contratistas', '✅ Actual', ''],
                ['2.16', 'Exportar registro a PDF', '✅ Actual', ''],
                ['2.17', 'Ver trazabilidad del registro', '✅ Actual', ''],
                ['2.18', 'NO puede eliminar registros', '❌ Bloqueado', ''],
                ['2.19', 'NO puede reabrir directamente (usa solicitud)', '❌ Bloqueado', ''],
            ],
            'Contratista (contratista)' => [
                ['2.20', 'Crear nuevo registro mensual', '✅ Actual', ''],
                ['2.21', 'Seleccionar servicio/dependencia (si tiene varios)', '✅ Actual', ''],
                ['2.22', 'Marcar cumple/no cumple por actividad', '✅ Actual', ''],
                ['2.23', 'Agregar responsable por actividad', '✅ Actual', ''],
                ['2.24', 'Agregar observaciones por actividad', '✅ Actual', ''],
                ['2.25', 'Subir evidencias (hasta 4 por actividad)', '✅ Actual', ''],
                ['2.26', 'Eliminar evidencia pendiente (antes de guardar)', '✅ Actual', ''],
                ['2.27', 'Guardar registro (envío)', '✅ Actual', ''],
                ['2.28', 'Editar registro NO auditado', '✅ Actual', ''],
                ['2.29', 'Ver historial de sus registros', '✅ Actual', ''],
                ['2.30', 'Ver detalle de su registro', '✅ Actual', ''],
                ['2.31', 'Exportar registro a PDF', '✅ Actual', ''],
                ['2.32', 'Ver trazabilidad de su registro', '✅ Actual', ''],
                ['2.33', 'Editar registro REABIERTO (subsanación)', '✅ Actual', ''],
                ['2.34', 'NO puede editar registro auditado', '❌ Bloqueado', ''],
                ['2.35', 'NO puede eliminar registros', '❌ Bloqueado', ''],
            ],
        ]
    ],
    '3. Auditoría' => [
        'titulo' => 'MÓDULO 3: AUDITORÍA',
        'roles' => [
            'Administrador (admin)' => [
                ['3.1', 'Iniciar auditoría de cualquier registro', '✅ Actual', ''],
                ['3.2', 'Marcar cumple/no cumple auditor por actividad', '✅ Actual', ''],
                ['3.3', 'Agregar observación de auditor por actividad', '✅ Actual', ''],
                ['3.4', 'Agregar comentarios de auditoría al registro', '✅ Actual', ''],
                ['3.5', 'Seleccionar tipo de auditoría (Sistema/Terreno)', '✅ Actual', ''],
                ['3.6', 'Finalizar auditoría', '✅ Actual', ''],
                ['3.7', 'Pausar y continuar auditoría después', '✅ Actual', ''],
                ['3.8', 'Registrar hallazgos', '✅ Actual', ''],
                ['3.9', 'Cambiar estado de hallazgo (abierto/cerrado)', '✅ Actual', ''],
            ],
            'Admin de Contrato (administrador_contrato)' => [
                ['3.10', 'Iniciar auditoría solo de sus contratistas', '✅ Actual', ''],
                ['3.11', 'Marcar cumple/no cumple auditor por actividad', '✅ Actual', ''],
                ['3.12', 'Agregar observación de auditor por actividad', '✅ Actual', ''],
                ['3.13', 'Agregar comentarios de auditoría', '✅ Actual', ''],
                ['3.14', 'Seleccionar tipo de auditoría', '✅ Actual', ''],
                ['3.15', 'Finalizar auditoría', '✅ Actual', ''],
                ['3.16', 'Pausar y continuar auditoría después', '✅ Actual', ''],
                ['3.17', 'Registrar hallazgos', '✅ Actual', ''],
            ],
            'Contratista (contratista)' => [
                ['3.18', 'Ver resultado de auditoría en su registro', '✅ Actual', ''],
                ['3.19', 'Ver comentarios del auditor', '✅ Actual', ''],
                ['3.20', 'Ver hallazgos registrados', '✅ Actual', ''],
                ['3.21', 'NO puede auditar', '❌ Bloqueado', ''],
            ],
        ]
    ],
    '4. Reapertura' => [
        'titulo' => 'MÓDULO 4: SOLICITUDES DE REAPERTURA',
        'roles' => [
            'Administrador (admin)' => [
                ['4.1', 'Ver todas las solicitudes de reapertura', '✅ Actual', ''],
                ['4.2', 'Filtrar solicitudes por estado', '✅ Actual', ''],
                ['4.3', 'Aprobar solicitud de reapertura', '✅ Actual', ''],
                ['4.4', 'Definir fecha límite de subsanación', '✅ Actual', ''],
                ['4.5', 'Rechazar solicitud con comentario', '✅ Actual', ''],
            ],
            'Admin de Contrato (administrador_contrato)' => [
                ['4.6', 'Ver solicitudes solo de sus contratistas', '✅ Actual', ''],
                ['4.7', 'Aprobar solicitud de reapertura', '✅ Actual', ''],
                ['4.8', 'Definir fecha límite de subsanación', '✅ Actual', ''],
                ['4.9', 'Rechazar solicitud con comentario', '✅ Actual', ''],
            ],
            'Contratista (contratista)' => [
                ['4.10', 'Crear solicitud de reapertura', '✅ Actual', ''],
                ['4.11', 'Escribir motivo de la solicitud', '✅ Actual', ''],
                ['4.12', 'Ver estado de sus solicitudes', '✅ Actual', ''],
                ['4.13', 'Ver fecha límite de subsanación', '✅ Actual', ''],
                ['4.14', 'Recibir email cuando solicitud es resuelta', '✅ Actual', ''],
                ['4.15', 'NO puede aprobar/rechazar solicitudes', '❌ Bloqueado', ''],
            ],
        ]
    ],
    '5. Contratistas' => [
        'titulo' => 'MÓDULO 5: GESTIÓN DE CONTRATISTAS',
        'roles' => [
            'Administrador (admin)' => [
                ['5.1', 'Ver lista de todos los contratistas', '✅ Actual', ''],
                ['5.2', 'Filtrar por EECC, Dependencia, Servicio', '✅ Actual', ''],
                ['5.3', '⚠️ Crear nuevo contratista manualmente', '✅ Actual', '¿Bloquear cuando ACEM esté conectado?'],
                ['5.4', '⚠️ Editar datos del contratista', '✅ Actual', '¿Deben poder editar datos maestros?'],
                ['5.5', 'Ver detalle de contratista', '✅ Actual', ''],
                ['5.6', 'Agregar asignación servicio+dependencia', '✅ Actual', ''],
                ['5.7', 'Editar asignación (admin contrato, fecha inicio)', '✅ Actual', ''],
                ['5.8', 'Eliminar asignación', '✅ Actual', ''],
                ['5.9', 'Asignar administrador de contrato', '✅ Actual', ''],
                ['5.10', 'Activar/Desactivar contratista', '✅ Actual', ''],
                ['5.11', '⚠️ Eliminar contratista', '✅ Actual', '¿Ocultar este botón?'],
                ['5.12', 'Ver usuarios asociados del contratista', '✅ Actual', ''],
                ['5.13', 'Agregar usuario asociado al contratista', '✅ Actual', ''],
            ],
            'Admin de Contrato (administrador_contrato)' => [
                ['5.14', 'Ver solo sus contratistas asignados', '✅ Actual', ''],
                ['5.15', 'Ver detalle del contratista', '✅ Actual', ''],
                ['5.16', 'NO puede crear contratistas', '❌ Bloqueado', ''],
                ['5.17', 'NO puede editar contratistas', '❌ Bloqueado', ''],
                ['5.18', 'NO puede eliminar contratistas', '❌ Bloqueado', ''],
                ['5.19', 'NO puede cambiar asignaciones', '❌ Bloqueado', ''],
            ],
            'Contratista (contratista)' => [
                ['5.20', 'Ver sus propios datos', '✅ Actual', ''],
                ['5.21', '⚠️ Crear usuarios operativos propios', '✅ Actual', '¿Solo contratista o también admin?'],
                ['5.22', 'Editar usuarios asociados', '✅ Actual', ''],
                ['5.23', 'Eliminar usuarios asociados', '✅ Actual', ''],
                ['5.24', 'Asignar servicio/dependencia a usuario operativo', '✅ Actual', ''],
                ['5.25', 'NO puede editar sus datos maestros', '❌ Bloqueado', ''],
                ['5.26', 'NO puede ver otros contratistas', '❌ Bloqueado', ''],
            ],
        ]
    ],
    '6. Usuarios' => [
        'titulo' => 'MÓDULO 6: GESTIÓN DE USUARIOS ABASTIBLE',
        'roles' => [
            'Administrador (admin)' => [
                ['6.1', 'Ver lista de usuarios admin/admin_contrato', '✅ Actual', ''],
                ['6.2', 'Filtrar por rol', '✅ Actual', ''],
                ['6.3', 'Buscar por nombre/email', '✅ Actual', ''],
                ['6.4', 'Crear nuevo usuario admin', '✅ Actual', ''],
                ['6.5', 'Crear nuevo usuario admin_contrato', '✅ Actual', ''],
                ['6.6', 'Editar usuario', '✅ Actual', ''],
                ['6.7', 'Cambiar contraseña de usuario', '✅ Actual', ''],
                ['6.8', 'Cambiar rol de usuario', '✅ Actual', ''],
                ['6.9', 'Eliminar usuario', '✅ Actual', ''],
                ['6.10', 'Activar/Desactivar usuario', '✅ Actual', ''],
            ],
            'Admin de Contrato (administrador_contrato)' => [
                ['6.11', 'NO tiene acceso a gestión de usuarios', '❌ Bloqueado', ''],
                ['6.12', 'Editar su propio perfil', '✅ Actual', ''],
            ],
            'Contratista (contratista)' => [
                ['6.13', 'NO tiene acceso a este módulo', '❌ Bloqueado', ''],
            ],
        ]
    ],
    '7. Programas' => [
        'titulo' => 'MÓDULO 7: PROGRAMAS, ELEMENTOS Y ACTIVIDADES',
        'roles' => [
            'Administrador (admin)' => [
                ['7.1', 'Ver lista de programas', '✅ Actual', ''],
                ['7.2', 'Crear nuevo programa', '✅ Actual', ''],
                ['7.3', 'Editar programa', '✅ Actual', ''],
                ['7.4', 'Eliminar programa', '✅ Actual', ''],
                ['7.5', 'Ver elementos de un programa', '✅ Actual', ''],
                ['7.6', 'Crear nuevo elemento', '✅ Actual', ''],
                ['7.7', 'Editar elemento', '✅ Actual', ''],
                ['7.8', 'Eliminar elemento', '✅ Actual', ''],
                ['7.9', 'Ver actividades de un elemento', '✅ Actual', ''],
                ['7.10', 'Crear nueva actividad', '✅ Actual', ''],
                ['7.11', 'Editar actividad (criterios, frecuencia, etc.)', '✅ Actual', ''],
                ['7.12', 'Eliminar actividad', '✅ Actual', ''],
                ['7.13', 'Marcar actividad como requiere evidencia', '✅ Actual', ''],
            ],
            'Admin de Contrato (administrador_contrato)' => [
                ['7.14', 'Ver programas (solo lectura)', '✅ Actual', ''],
                ['7.15', 'Ver elementos (solo lectura)', '✅ Actual', ''],
                ['7.16', 'Ver actividades (solo lectura)', '✅ Actual', ''],
                ['7.17', 'NO puede crear/editar/eliminar', '❌ Bloqueado', ''],
            ],
            'Contratista (contratista)' => [
                ['7.18', 'Ver actividades de su programa (en formulario)', '✅ Actual', ''],
                ['7.19', 'Ver criterios de aprobación', '✅ Actual', ''],
                ['7.20', 'NO puede editar programas/elementos/actividades', '❌ Bloqueado', ''],
            ],
        ]
    ],
    '8. Servicios' => [
        'titulo' => 'MÓDULO 8: SERVICIOS (Tipos de Contratista)',
        'roles' => [
            'Administrador (admin)' => [
                ['8.1', 'Ver lista de servicios', '✅ Actual', ''],
                ['8.2', 'Crear nuevo servicio', '✅ Actual', ''],
                ['8.3', 'Editar servicio', '✅ Actual', ''],
                ['8.4', 'Asignar programa al servicio', '✅ Actual', ''],
                ['8.5', 'Eliminar servicio', '✅ Actual', ''],
                ['8.6', 'Activar/Desactivar servicio', '✅ Actual', ''],
            ],
            'Admin de Contrato (administrador_contrato)' => [
                ['8.7', 'Ver lista de servicios (solo lectura)', '✅ Actual', ''],
                ['8.8', 'NO puede crear/editar/eliminar servicios', '❌ Bloqueado', ''],
            ],
            'Contratista (contratista)' => [
                ['8.9', 'Ver sus servicios asignados', '✅ Actual', ''],
                ['8.10', 'NO puede acceder a este módulo', '❌ Bloqueado', ''],
            ],
        ]
    ],
    '9. Dependencias' => [
        'titulo' => 'MÓDULO 9: DEPENDENCIAS (Plantas)',
        'roles' => [
            'Administrador (admin)' => [
                ['9.1', 'Ver lista de dependencias', '✅ Actual', ''],
                ['9.2', '⚠️ Crear nueva dependencia', '✅ Actual', '¿Bloquear cuando ACEM esté conectado?'],
                ['9.3', 'Editar dependencia', '✅ Actual', ''],
                ['9.4', 'Eliminar dependencia', '✅ Actual', ''],
            ],
            'Admin de Contrato (administrador_contrato)' => [
                ['9.5', 'Ver dependencias (solo lectura)', '✅ Actual', ''],
                ['9.6', 'NO puede crear/editar/eliminar', '❌ Bloqueado', ''],
            ],
            'Contratista (contratista)' => [
                ['9.7', 'NO tiene acceso a este módulo', '❌ Bloqueado', ''],
            ],
        ]
    ],
    '10. Evidencias' => [
        'titulo' => 'MÓDULO 10: EVIDENCIAS',
        'roles' => [
            'Administrador (admin)' => [
                ['10.1', 'Ver lista consolidada de todas las evidencias', '✅ Actual', ''],
                ['10.2', 'Visualizar evidencia en navegador', '✅ Actual', ''],
                ['10.3', 'Descargar evidencia', '✅ Actual', ''],
                ['10.4', 'Filtrar evidencias', '✅ Actual', ''],
            ],
            'Admin de Contrato (administrador_contrato)' => [
                ['10.5', 'Ver evidencias de sus contratistas', '✅ Actual', ''],
                ['10.6', 'Visualizar evidencia en navegador', '✅ Actual', ''],
                ['10.7', 'Descargar evidencia', '✅ Actual', ''],
            ],
            'Contratista (contratista)' => [
                ['10.8', 'Ver sus propias evidencias', '✅ Actual', ''],
                ['10.9', 'Subir evidencia (hasta 4 por actividad)', '✅ Actual', ''],
                ['10.10', 'Visualizar evidencia en navegador', '✅ Actual', ''],
                ['10.11', 'Descargar evidencia', '✅ Actual', ''],
                ['10.12', 'Eliminar evidencia (antes de auditoría)', '✅ Actual', ''],
            ],
        ]
    ],
    '11. Reportes' => [
        'titulo' => 'MÓDULO 11: REPORTES',
        'roles' => [
            'Administrador (admin)' => [
                ['11.1', 'Ver reportes consolidados', '✅ Actual', ''],
                ['11.2', 'Filtrar por periodo, EECC, dependencia', '✅ Actual', ''],
                ['11.3', 'Exportar a Excel', '✅ Actual', ''],
                ['11.4', 'Exportar a PDF', '✅ Actual', ''],
            ],
            'Admin de Contrato (administrador_contrato)' => [
                ['11.5', 'Ver reportes de sus contratistas', '✅ Actual', ''],
                ['11.6', 'Exportar a Excel', '✅ Actual', ''],
                ['11.7', 'Exportar a PDF', '✅ Actual', ''],
            ],
            'Contratista (contratista)' => [
                ['11.8', 'NO tiene acceso a reportes consolidados', '❌ Bloqueado', ''],
            ],
        ]
    ],
    '12. Emails' => [
        'titulo' => 'MÓDULO 12: NOTIFICACIONES POR EMAIL',
        'roles' => [
            'Eventos Actuales' => [
                ['12.1', 'Solicitud de reapertura creada → Admin de Contrato', '✅ Actual', ''],
                ['12.2', 'Solicitud aprobada → Contratista', '✅ Actual', ''],
                ['12.3', 'Solicitud rechazada → Contratista', '✅ Actual', ''],
            ],
            'Eventos Pendientes (¿Implementar?)' => [
                ['12.4', 'Auditoría completada → Contratista', '❌ No implementado', '¿Agregar?'],
                ['12.5', 'Fecha límite próxima a vencer → Contratista', '❌ No implementado', '¿Agregar?'],
                ['12.6', 'Nuevo registro enviado → Admin de Contrato', '❌ No implementado', '¿Agregar?'],
            ],
        ]
    ],
];

// Crear hojas
$sheetIndex = 0;
foreach ($modulos as $key => $modulo) {
    if ($sheetIndex > 0) {
        $excel->createSheet();
    }
    $excel->setActiveSheetIndex($sheetIndex);
    $sheet = $excel->getActiveSheet();
    $sheet->setTitle(substr($key, 0, 31)); // Límite de 31 caracteres
    
    if (isset($modulo['tipo']) && $modulo['tipo'] === 'resumen') {
        // Hoja de resumen
        $sheet->setCellValue('A1', 'MATRIZ DE FUNCIONALIDADES POR ROL - OIEM ABASTIBLE');
        $sheet->mergeCells('A1:E1');
        $sheet->getStyle('A1')->applyFromArray([
            'font' => ['bold' => true, 'size' => 16, 'color' => ['rgb' => '1F4E79']],
            'alignment' => ['horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER]
        ]);
        
        $sheet->setCellValue('A3', 'INSTRUCCIONES:');
        $sheet->getStyle('A3')->getFont()->setBold(true);
        
        $sheet->setCellValue('A4', '1. Revise cada pestaña (módulo) de este documento');
        $sheet->setCellValue('A5', '2. En la columna "DECISIÓN" seleccione la opción deseada del menú desplegable');
        $sheet->setCellValue('A6', '3. Use la columna "OBSERVACIONES" para agregar comentarios');
        $sheet->setCellValue('A7', '4. Las funcionalidades marcadas con ⚠️ requieren decisión urgente');
        
        $sheet->setCellValue('A9', 'OPCIONES DE DECISIÓN:');
        $sheet->getStyle('A9')->getFont()->setBold(true);
        
        $sheet->setCellValue('A10', '✅ Mantener - La funcionalidad se mantiene como está');
        $sheet->setCellValue('A11', '❌ Eliminar - Se elimina la funcionalidad');
        $sheet->setCellValue('A12', '🔄 Mover - Se mueve a otro rol (especificar en observaciones)');
        $sheet->setCellValue('A13', '➕ Agregar - Agregar nueva funcionalidad (especificar en observaciones)');
        $sheet->setCellValue('A14', '⏸️ Pendiente - Requiere más discusión');
        
        $sheet->setCellValue('A16', 'ROLES DEL SISTEMA:');
        $sheet->getStyle('A16')->getFont()->setBold(true);
        
        $sheet->setCellValue('A17', 'Administrador (admin) - Control total del sistema');
        $sheet->setCellValue('A18', 'Admin de Contrato (administrador_contrato) - Audita contratistas asignados');
        $sheet->setCellValue('A19', 'Contratista (contratista) - Ingresa registros mensuales');
        
        $sheet->getColumnDimension('A')->setWidth(80);
        
    } else {
        // Hojas de módulos
        $row = 1;
        
        // Título del módulo
        $sheet->setCellValue('A' . $row, $modulo['titulo']);
        $sheet->mergeCells('A' . $row . ':E' . $row);
        $sheet->getStyle('A' . $row)->applyFromArray([
            'font' => ['bold' => true, 'size' => 14, 'color' => ['rgb' => '1F4E79']],
            'fill' => ['type' => PHPExcel_Style_Fill::FILL_SOLID, 'color' => ['rgb' => 'BDD7EE']]
        ]);
        $row += 2;
        
        foreach ($modulo['roles'] as $rolNombre => $funcionalidades) {
            // Subheader del rol
            $sheet->setCellValue('A' . $row, $rolNombre);
            $sheet->mergeCells('A' . $row . ':E' . $row);
            $sheet->getStyle('A' . $row . ':E' . $row)->applyFromArray($subHeaderStyle);
            $row++;
            
            // Headers de columnas
            $sheet->setCellValue('A' . $row, '#');
            $sheet->setCellValue('B' . $row, 'FUNCIONALIDAD');
            $sheet->setCellValue('C' . $row, 'ESTADO ACTUAL');
            $sheet->setCellValue('D' . $row, 'DECISIÓN');
            $sheet->setCellValue('E' . $row, 'OBSERVACIONES');
            $sheet->getStyle('A' . $row . ':E' . $row)->applyFromArray($headerStyle);
            $row++;
            
            // Funcionalidades
            foreach ($funcionalidades as $func) {
                $sheet->setCellValue('A' . $row, $func[0]);
                $sheet->setCellValue('B' . $row, $func[1]);
                $sheet->setCellValue('C' . $row, $func[2]);
                $sheet->setCellValue('D' . $row, ''); // Para dropdown
                $sheet->setCellValue('E' . $row, $func[3]);
                
                $sheet->getStyle('A' . $row . ':E' . $row)->applyFromArray($cellStyle);
                
                // Agregar dropdown en columna D
                $validation = $sheet->getCell('D' . $row)->getDataValidation();
                $validation->setType(PHPExcel_Cell_DataValidation::TYPE_LIST);
                $validation->setErrorStyle(PHPExcel_Cell_DataValidation::STYLE_INFORMATION);
                $validation->setAllowBlank(true);
                $validation->setShowDropDown(true);
                $validation->setFormula1('"✅ Mantener,❌ Eliminar,🔄 Mover,➕ Agregar,⏸️ Pendiente"');
                
                // Color de fondo para filas con advertencia
                if (strpos($func[1], '⚠️') !== false) {
                    $sheet->getStyle('A' . $row . ':E' . $row)->getFill()
                        ->setFillType(PHPExcel_Style_Fill::FILL_SOLID)
                        ->getStartColor()->setRGB('FFF2CC');
                }
                
                $row++;
            }
            $row++; // Espacio entre roles
        }
        
        // Ajustar anchos de columna
        $sheet->getColumnDimension('A')->setWidth(8);
        $sheet->getColumnDimension('B')->setWidth(55);
        $sheet->getColumnDimension('C')->setWidth(15);
        $sheet->getColumnDimension('D')->setWidth(18);
        $sheet->getColumnDimension('E')->setWidth(40);
    }
    
    $sheetIndex++;
}

// Guardar archivo
$excel->setActiveSheetIndex(0);
$filename = __DIR__ . '/MATRIZ_FUNCIONALIDADES_OIEM.xlsx';

$writer = PHPExcel_IOFactory::createWriter($excel, 'Excel2007');
$writer->save($filename);

echo "✅ Archivo generado exitosamente:\n";
echo "   📁 {$filename}\n\n";
echo "📧 Ahora puedes enviar este archivo a Abastible para su revisión.\n";
