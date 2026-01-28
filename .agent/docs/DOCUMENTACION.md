# Documentación Técnica y Funcional: OIEM Abastible

Este documento detalla los módulos del sistema OIEM (Observatorio de Información y Evaluación Mensual) bajo el formato de "Historia de Usuario" (Funcional) y "Ficha Técnica" (Técnico), describiendo qué hace el sistema y cómo lo logra internamente.

---

## Módulo 1: Dashboard Admin

### Historia de Usuario (El QUÉ)
"Como **Administrador**, quiero ver un resumen ejecutivo con KPIs de cumplimiento, cantidad de contratistas, registros y evidencias, además de los registros recientes, para monitorear el estado general del programa."

### Ficha Técnica / Blueprint (El CÓMO)

*   **Controlador**: `App\Http\Controllers\Admin\DashboardController`
*   **Vista**: `resources/views/admin/dashboard.blade.php`
*   **Datos / Inputs**:
    *   Usuario autenticado con rol `admin`.
*   **Lógica Oculta**:
    *   **Cumplimiento General**: Promedio de `porcentaje_cumplimiento` de todos los registros.
    *   **Total Contratistas**: Conteo de `user_id` distintos en registros.
    *   **Registros Recientes**: Últimos 10 registros con cálculo de `promedio_anual` por EECC.
*   **Outputs**:
    *   4 tarjetas KPI: Cumplimiento General, Contratistas, Registros, Evidencias.
    *   Tabla completa con 12 columnas (misma estructura que Registros).
    *   Barra de progreso visual para cumplimiento.

---

## Módulo 2: Dashboard Contratista

### Historia de Usuario (El QUÉ)
"Como **Contratista**, quiero ver mi porcentaje de cumplimiento actual, compararlo con la meta del programa, y tener acceso rápido a crear nuevos registros, para saber si estoy cumpliendo los objetivos de seguridad."

### Ficha Técnica / Blueprint (El CÓMO)

*   **Controlador**: `App\Http\Controllers\Contratista\DashboardController`
*   **Vista**: `resources/views/contratista/dashboard.blade.php`
*   **Datos / Inputs**:
    *   Usuario autenticado con rol `contratista`.
    *   Meta del programa desde `App\Models\Configuracion::getMetaPrograma()`.
*   **Lógica Oculta**:
    *   **Cumplimiento Actual**: Porcentaje del último registro enviado.
    *   **Semáforo Visual**: Verde (≥85%), Amarillo (≥60%), Rojo (<60%).
    *   **Progreso Mensual**: Gráfico de barras con historial de cumplimiento.
*   **Outputs**:
    *   Tarjetas de cumplimiento con indicador semáforo.
    *   Botón "Nuevo Registro" prominente.
    *   Historial de los últimos 6 meses.

---

## Módulo 3: Gestión de Registros (Admin)

### Historia de Usuario (El QUÉ)
"Como **Administrador**, quiero ver todos los registros enviados por los contratistas, filtrar por EECC, Dependencia y Periodo, y ordenar por cumplimiento o dotación, para evaluar el desempeño de cada empresa."

### Ficha Técnica / Blueprint (El CÓMO)

*   **Controlador**: `App\Http\Controllers\Admin\RegistroController`
*   **Vista**: `resources/views/admin/registros/index.blade.php`
*   **Modelo**: `App\Models\Registro`
*   **Filtros Disponibles**:
    *   EECC (dropdown dinámico)
    *   Dependencia (desde tabla maestra `dependencias`)
    *   Periodo (input mes/año)
*   **Ordenamiento Clickeable**:
    *   Mes Informado (periodo) ↑↓
    *   Dotación Total ↑↓
    *   Cumplimiento ↑↓
*   **Columnas de la Tabla**:
    | # | Columna | Descripción |
    |---|---------|-------------|
    | 1 | # | Correlativo con paginación |
    | 2 | Mes Informado | Formato "Enero 2025" |
    | 3 | Nombre EECC | Empresa Contratista |
    | 4 | Dependencia | Planta asignada |
    | 5 | Dotación Total | Personal total |
    | 6 | Supervisores | Cantidad |
    | 7 | Prevencionistas | Cantidad |
    | 8 | Personas Nuevas | Ingresos del mes |
    | 9 | Cumplimiento | % del mes (badge semáforo) |
    | 10 | % Promedio Año | Calculado dinámicamente |
    | 11 | Fecha Envío | Timestamp de creación |
    | 12 | Acciones | Ver detalle |
*   **Lógica Oculta**:
    *   `promedio_anual`: Calculado en runtime como AVG de `porcentaje_cumplimiento` del mismo `user_id` y año.
    *   Filas alternadas: `bg-white` / `bg-sky-100`.

---

## Módulo 4: Formulario de Registro Mensual (Contratista)

### Historia de Usuario (El QUÉ)
"Como **Contratista**, quiero completar mi registro mensual indicando cumplimiento de actividades, subir hasta 4 evidencias por actividad (de una en una desde diferentes carpetas), y ver qué archivos ya subí, para reportar correctamente mis avances de seguridad."

### Ficha Técnica / Blueprint (El CÓMO)

*   **Componente Livewire**: `App\Livewire\Contratista\FormularioRegistro`
*   **Vista**: `resources/views/livewire/contratista/formulario-registro.blade.php`
*   **Modelos Afectados**: `Registro`, `RegistroActividad`, `Evidencia`
*   **Inputs**:
    *   Información del contratista (autocompletada)
    *   Periodo (mes/año)
    *   Por cada Elemento/Actividad:
        *   Cumple (Sí=1/No=0)
        *   Responsable (texto)
        *   Observaciones
        *   Evidencias (hasta 4 archivos por actividad)
*   **Lógica Oculta**:
    *   **Acumulación de Archivos**: Propiedad `$archivosAcumulados` permite seleccionar archivos uno a uno.
    *   **Límite de 4**: Valida `total_existentes + total_pendientes ≤ 4`.
    *   **Eliminar Pendiente**: Método `eliminarArchivoTemporal($actividadId, $index)`.
    *   **Cálculo de Cumplimiento**: `Registro->actualizarCumplimiento()` calcula % basado en actividades marcadas como "Cumple".
*   **Almacenamiento**: Disco `public`, ruta `storage/app/public/evidencias/{registro_id}/`.
*   **Tipos Permitidos**: PDF, JPG, JPEG, PNG (máx 10MB por archivo).
*   **UI Features**:
    *   Spinner animado durante carga de archivo.
    *   Botón ❌ grande para quitar archivos pendientes.
    *   "Criterio de aprobación:" visible antes de cada criterio.
    *   Botón "📎 + Cargar Evidencia" con contador de disponibles.

---

## Módulo 5: Gestión de Evidencias

### Historia de Usuario (El QUÉ)
"Como **Usuario**, quiero ver y/o descargar las evidencias subidas. Como **Contratista** puedo ver mis propias evidencias en el navegador. Como **Admin** puedo ver todas las evidencias y descargarlas."

### Ficha Técnica / Blueprint (El CÓMO)

#### Para Contratistas:
*   **Controlador**: `App\Http\Controllers\Contratista\EvidenciaController`
*   **Vista**: `resources/views/contratista/evidencias/index.blade.php`
*   **Rutas**:
    *   `contratista.evidencia.view` → Abre en navegador
    *   `contratista.evidencia.download` → Fuerza descarga
*   **Filtro**: Solo evidencias del usuario autenticado.

#### Para Administradores:
*   **Controlador**: `App\Http\Controllers\Admin\EvidenciaController`
*   **Vista**: `resources/views/admin/evidencias/index.blade.php`
*   **Rutas**:
    *   `admin.evidencias.view` → Abre en navegador
    *   `admin.evidencias.download` → Fuerza descarga
*   **Filtro**: Todas las evidencias del sistema.

*   **Lógica Oculta**:
    *   `Storage::disk('public')->response()` para visualización inline.
    *   `Storage::disk('public')->download()` para descarga forzada.
    *   Validación de pertenencia antes de servir archivo.

---

## Módulo 6: Elementos y Actividades

### Historia de Usuario (El QUÉ)
"Como **Administrador**, quiero gestionar los Elementos del programa (ej: Investigación de Accidentes) y sus Actividades asociadas (ej: Envío de informe en plazo), para definir qué deben reportar los contratistas."

### Ficha Técnica / Blueprint (El CÓMO)

*   **Controladores**: 
    *   `App\Http\Controllers\Admin\ElementoController`
    *   `App\Http\Controllers\Admin\ActividadController`
*   **Modelos**: `Elemento`, `Actividad`
*   **Relaciones**:
    *   `Elemento hasMany Actividad`
    *   `Actividad belongsTo Elemento`
*   **Campos de Elemento**:
    *   código, nombre, descripcion, orden, activo
*   **Campos de Actividad**:
    *   código, descripción, criterios, frecuencia, requiere_evidencia, orden, activo
*   **Rutas**: CRUD anidado `admin/elementos/{elemento}/actividades`.

---

## Módulo 7: Gestión de Dependencias (CRUD)

### Historia de Usuario (El QUÉ)
"Como **Administrador**, quiero gestionar el catálogo de Dependencias (plantas) desde una interfaz simple, con formulario a la izquierda y tabla a la derecha, para mantener la data maestra actualizada."

### Ficha Técnica / Blueprint (El CÓMO)

*   **Controlador**: `App\Http\Controllers\Admin\DependenciaController`
*   **Modelo**: `App\Models\Dependencia`
*   **Vista**: `resources/views/admin/dependencias/index.blade.php`
*   **Campos**: nombre (string), activo (boolean)
*   **Layout**:
    *   Columna izquierda (1/3): Formulario crear/editar
    *   Columna derecha (2/3): Tabla con acciones
*   **Lógica Oculta**:
    *   Nombres guardados en MAYÚSCULAS automáticamente.
    *   JavaScript para alternar entre modo "Nuevo" y "Editar".
    *   Confirmación antes de eliminar.

---

## Módulo 8: Configuración del Sistema

### Historia de Usuario (El QUÉ)
"Como **Administrador**, quiero configurar parámetros globales del sistema como la Meta del Programa (%), para que se reflejen dinámicamente en todos los dashboards."

### Ficha Técnica / Blueprint (El CÓMO)

*   **Controlador**: `App\Http\Controllers\Admin\ConfiguracionController`
*   **Modelo**: `App\Models\Configuracion`
*   **Vista**: `resources/views/admin/configuracion/index.blade.php`
*   **Estructura de Tabla**:
    *   `key` (string, único)
    *   `value` (string)
    *   `description` (string)
    *   `type` (string: integer, string, boolean)
*   **Métodos Estáticos**:
    *   `Configuracion::get($key, $default)` → Obtiene valor
    *   `Configuracion::set($key, $value)` → Guarda valor
    *   `Configuracion::getMetaPrograma()` → Shortcut para meta_programa
*   **Uso en Vistas**:
    ```php
    $metaPrograma = Configuracion::getMetaPrograma(); // 85 por defecto
    ```

---

## Módulo 9: Reportes y Exportación

### Historia de Usuario (El QUÉ)
"Como **Administrador**, quiero generar reportes consolidados de cumplimiento y exportarlos a Excel/PDF, para presentar informes a la gerencia."

### Ficha Técnica / Blueprint (El CÓMO)

*   **Controlador**: `App\Http\Controllers\Admin\ReporteController`
*   **Vista**: `resources/views/admin/reportes/index.blade.php`
*   **Rutas**:
    *   `admin.reportes.index` → Vista principal
    *   `admin.reportes.excel` → Exportar Excel
    *   `admin.reportes.pdf` → Exportar PDF
*   **Filtros**: Por periodo, EECC, Dependencia.

---

## Módulo 10: Historial del Contratista

### Historia de Usuario (El QUÉ)
"Como **Contratista**, quiero ver el historial de todos mis registros enviados, con su porcentaje de cumplimiento y estado, para hacer seguimiento de mi desempeño."

### Ficha Técnica / Blueprint (El CÓMO)

*   **Controlador**: `App\Http\Controllers\Contratista\HistorialController`
*   **Vista**: `resources/views/contratista/historial/index.blade.php`
*   **Datos**: Registros del usuario ordenados por fecha descendente.
*   **Acciones**: Ver detalle, Editar (si el mes está abierto).

---

## Módulo 11: Autenticación y Roles

### Historia de Usuario (El QUÉ)
"Como **Usuario**, quiero iniciar sesión con mi email y contraseña, y ver solo las opciones correspondientes a mi rol (Admin o Contratista)."

### Ficha Técnica / Blueprint (El CÓMO)

*   **Paquete**: Laravel Breeze + Livewire
*   **Middleware**: `role:admin`, `role:contratista`
*   **Rutas Protegidas**:
    *   `/admin/*` → Solo rol `admin`
    *   `/contratista/*` → Solo rol `contratista`
*   **Navegación Dinámica**: `resources/views/livewire/layout/navigation.blade.php`
    *   Muestra menú según `auth()->user()->role`.
*   **Gestión por Contratistas**: Los contratistas pueden gestionar sus propios usuarios con rol `usuario_contratista`, asignándoles servicios y dependencias específicos de su propia cartera.

---

## Módulo 12: Jerarquía de Usuarios y Operatividad
### Historia de Usuario (El QUÉ)
"Como **Contratista**, quiero crear trabajadores operativos que solo vean y registren información de un servicio y dependencia específico, para delegar la carga de datos sin exponer toda la información de la empresa."

### Ficha Técnica (El CÓMO)
*   **Role**: `usuario_contratista`
*   **Vinculación**: Campo `parent_id` en tabla `users` que apunta al ID del contratista dueño.
*   **Restricción de Datos**:
    *   **Dashboard**: Filtra estadísticas solo para el `tipo_contratista_id` y `dependencia_id` asignado al usuario operativo.
    *   **Registro**: El formulario Livewire detecta al trabajador y bloquea la selección a su asignación única. Los datos se guardan bajo el `user_id` de la empresa (parent) para consolidación.
    *   **Historial**: Filtra registros de la empresa por la dependencia del trabajador.

---

## Stack Tecnológico

| Componente | Tecnología | Versión |
|------------|------------|---------|
| Framework | Laravel | 12.x |
| Frontend Reactivo | Livewire | 3.x |
| CSS | Tailwind CSS | 3.x |
| Base de Datos | MySQL | 8.x |
| Almacenamiento | Laravel Storage (Disco Public) | - |
| Autenticación | Laravel Breeze | - |
| Fechas | Carbon | - |

---

## Estructura de Base de Datos

### Tablas Principales

| Tabla | Descripción |
|-------|-------------|
| `users` | Usuarios del sistema (Admin/Contratista) |
| `elementos` | Elementos del programa de seguridad |
| `actividades` | Actividades por elemento |
| `registros` | Registros mensuales de contratistas |
| `registro_actividades` | Detalle de cumplimiento por actividad |
| `evidencias` | Archivos adjuntos a las actividades |
| `dependencias` | Catálogo de dependencias/plantas |
| `contratista_asignaciones` | Vínculo entre Contratista, Servicio y Dependencia |
| `configuraciones` | Parámetros del sistema |

### Relaciones Principales

```
User (1) ──── (N) Registro
Registro (1) ──── (N) RegistroActividad
RegistroActividad (1) ──── (N) Evidencia
Elemento (1) ──── (N) Actividad
```

---

## Módulo 13: Sistema de Solicitudes de Reapertura

### Historia de Usuario (El QUÉ)
"Como **Contratista**, quiero solicitar la reapertura de un registro ya auditado para corregir errores, especificando el motivo, y como **Administrador de Contrato**, quiero revisar, aprobar o rechazar esas solicitudes, definiendo una fecha límite para la subsanación."

### Ficha Técnica / Blueprint (El CÓMO)

*   **Modelo**: `App\Models\SolicitudReapertura`
*   **Controladores**: 
    *   `App\Http\Controllers\Contratista\SolicitudReaperturaController`
    *   `App\Http\Controllers\Admin\SolicitudReaperturaController`
*   **Tabla**: `solicitudes_reapertura`
*   **Campos**:
    *   `registro_id` - Registro al que aplica la solicitud
    *   `solicitante_id` - Usuario contratista que solicita
    *   `motivo` - Justificación de la reapertura
    *   `estado` - Enum: `pendiente`, `aprobada`, `rechazada`
    *   `aprobador_id` - Admin que resuelve la solicitud
    *   `comentario_respuesta` - Respuesta del administrador
    *   `fecha_limite_subsanacion` - Plazo para subsanar (definido al aprobar)
    *   `fecha_respuesta` - Timestamp de la resolución
*   **Flujo**:
    1. Contratista crea solicitud desde historial (registro auditado)
    2. Admin ve solicitudes pendientes en panel de administración
    3. Admin aprueba (con fecha límite) o rechaza (con comentario)
    4. Contratista recibe email con resolución
    5. Si aprobada, contratista puede editar hasta la fecha límite
    6. Después del plazo, el registro se cierra automáticamente
*   **Notificaciones por Email**:
    *   `App\Mail\SolicitudReaperturaCreada` - Notifica al admin
    *   `App\Mail\SolicitudReaperturaResuelta` - Notifica al contratista (aprobada/rechazada)

---

## Módulo 14: Trazabilidad de Registros (Logs)

### Historia de Usuario (El QUÉ)
"Como **Administrador**, quiero ver un historial detallado de todas las acciones realizadas sobre un registro (creación, edición, auditoría, reaperturas), para tener trazabilidad completa de quién hizo qué y cuándo."

### Ficha Técnica / Blueprint (El CÓMO)

*   **Modelo**: `App\Models\RegistroLog`
*   **Tabla**: `registro_logs`
*   **Campos**:
    *   `registro_id` - Registro al que pertenece el log
    *   `user_id` - Usuario que realizó la acción
    *   `accion` - Tipo de acción (ver lista abajo)
    *   `descripcion` - Descripción adicional
    *   `datos_anteriores` - JSON con estado previo
    *   `datos_nuevos` - JSON con estado nuevo
    *   `ip_address` - IP del usuario
*   **Acciones Registradas**:
    | Código | Etiqueta |
    |--------|----------|
    | `crear` | 📝 Registro Creado |
    | `editar` | ✏️ Registro Editado |
    | `solicitar_reapertura` | 🔔 Solicitud de Reapertura |
    | `aprobar_reapertura` | ✅ Reapertura Aprobada |
    | `rechazar_reapertura` | ❌ Reapertura Rechazada |
    | `reabrir` | 🔓 Registro Reabierto |
    | `subsanar` | 📩 Subsanación Enviada |
    | `iniciar_auditoria` | 🔍 Auditoría Iniciada |
    | `completar_auditoria` | ✓ Auditoría Completada |
    | `comentario_auditoria` | 💬 Comentario de Auditoría |
*   **Helper Estático**:
    ```php
    RegistroLog::registrar($registroId, 'accion', 'descripción opcional', $datosAnteriores, $datosNuevos);
    ```
*   **Exportación**: Disponible en PDF desde la vista de detalle del registro.

---

## Módulo 15: Estados de Auditoría

### Historia de Usuario (El QUÉ)
"Como **Administrador de Contrato**, quiero ver el estado actual de auditoría de cada registro (pendiente, auditando, auditada por terreno, auditada por sistema, reabierto), para saber en qué fase se encuentra cada uno."

### Ficha Técnica / Blueprint (El CÓMO)

*   **Campo**: `registros.estado_auditoria`
*   **Estados Disponibles**:
    | Estado | Descripción |
    |--------|-------------|
    | `pendiente` | Registro enviado, sin auditar |
    | `auditando` | Auditoría en proceso |
    | `auditada_terreno` | Auditoría de terreno completada |
    | `auditada_sistema` | Auditoría de sistema completada |
    | `reabierto` | Registro reabierto para subsanación |
*   **Lógica Oculta**:
    *   Estado cambia automáticamente al iniciar/completar auditoría
    *   Estado cambia a `reabierto` al aprobar solicitud de reapertura
    *   Se refleja en Dashboard y tablas de registros

---

## Módulo 16: Comentarios de Auditoría

### Historia de Usuario (El QUÉ)
"Como **Administrador de Contrato**, quiero agregar comentarios durante el proceso de auditoría de un registro, para documentar observaciones y hallazgos que el contratista debe conocer."

### Ficha Técnica / Blueprint (El CÓMO)

*   **Modelo**: `App\Models\AuditoriaComentario`
*   **Tabla**: `auditoria_comentarios`
*   **Campos**:
    *   `registro_id` - Registro auditado
    *   `user_id` - Auditor que comenta
    *   `comentario` - Texto del comentario
*   **Relaciones**:
    *   `registro()` → `BelongsTo Registro`
    *   `auditor()` → `BelongsTo User`
*   **Visualización**: Los comentarios aparecen en la vista de detalle del registro tanto para Admin como para Contratista.

---

## Módulo 17: Subsanación de Actividades

### Historia de Usuario (El QUÉ)
"Como **Contratista**, después de una reapertura aprobada, quiero corregir las actividades marcadas como incumplidas y registrar cuándo fueron subsanadas, para que quede constancia del cumplimiento posterior."

### Ficha Técnica / Blueprint (El CÓMO)

*   **Campo**: `registro_actividades.subsanado_at`
*   **Tipo**: `timestamp`, nullable
*   **Lógica**:
    *   Se registra automáticamente cuando el contratista modifica una actividad durante el periodo de subsanación
    *   Permite diferenciar entre cumplimientos originales y subsanados
    *   Visible en reportes de auditoría

---

## Módulo 18: Gestión de Usuarios Activos

### Historia de Usuario (El QUÉ)
"Como **Administrador**, quiero poder desactivar usuarios sin eliminarlos, para mantener el historial de sus acciones pero impedir su acceso al sistema."

### Ficha Técnica / Blueprint (El CÓMO)

*   **Campo**: `users.activo`
*   **Tipo**: `boolean`, default `true`
*   **Lógica**:
    *   Usuarios con `activo = false` no pueden iniciar sesión
    *   El administrador puede activar/desactivar desde la gestión de usuarios
    *   Los registros históricos del usuario se mantienen intactos

---

## Estructura de Base de Datos (Actualizada)

### Tablas Principales

| Tabla | Descripción |
|-------|-------------|
| `users` | Usuarios del sistema (Admin/Contratista) con campo `activo` |
| `elementos` | Elementos del programa de seguridad |
| `actividades` | Actividades por elemento |
| `registros` | Registros mensuales con `estado_auditoria` |
| `registro_actividades` | Detalle con campo `subsanado_at` |
| `evidencias` | Archivos adjuntos a las actividades |
| `dependencias` | Catálogo de dependencias/plantas |
| `contratista_asignaciones` | Vínculo Contratista-Servicio-Dependencia |
| `configuraciones` | Parámetros del sistema |
| `solicitudes_reapertura` | Solicitudes de reapertura de registros |
| `registro_logs` | Trazabilidad de acciones sobre registros |
| `auditoria_comentarios` | Comentarios durante la auditoría |

### Relaciones Principales

```
User (1) ──── (N) Registro
Registro (1) ──── (N) RegistroActividad
Registro (1) ──── (N) RegistroLog
Registro (1) ──── (N) SolicitudReapertura
Registro (1) ──── (N) AuditoriaComentario
RegistroActividad (1) ──── (N) Evidencia
Elemento (1) ──── (N) Actividad
```

---

## Historial de Versiones

| Versión | Fecha | Cambios Principales |
| :--- | :--- | :--- |
| **v1.0** | 16/12/2024 | Lanzamiento inicial con todos los módulos base. |
| **v1.1** | 16/12/2024 | **Múltiples Evidencias**: Soporte para hasta 4 archivos por actividad con selección individual. <br> **Botones Ver/Descargar**: Separados para Admin y Contratista. |
| **v1.2** | 16/12/2024 | **CRUD Dependencias**: Gestión de plantas desde admin. <br> **Filtros y Ordenamiento**: Tabla de registros con filtro por dependencia y columnas ordenables. |
| **v1.3** | 16/12/2024 | **Promedio Anual**: Nueva columna calculada en tablas. <br> **UI Mejorada**: Spinner de carga, botones más grandes, criterios explícitos. |
| **v1.4** | 06/01/2026 | **Gestión de Usuarios para Contratistas**: Implementación de trabajadores operativos (`usuario_contratista`) con acceso restringido por asignación. <br> **Jerarquía de Datos**: Los registros se guardan bajo el ID de la empresa madre para consolidación total. <br> **Refinamiento UI Admin**: Tabla de contratistas extra-ancha (Full Width), optimización de anchos de columna y eliminación de acciones redundantes. |
| **v1.5** | 12/01/2026 | **Sistema de Solicitudes de Reapertura**: Flujo completo para que contratistas soliciten reabrir registros auditados, con aprobación/rechazo por admin y fecha límite de subsanación. <br> **Trazabilidad Completa**: Logs detallados de todas las acciones sobre registros con exportación a PDF. <br> **Estados de Auditoría**: Campo `estado_auditoria` para seguimiento del proceso. <br> **Comentarios de Auditoría**: Sistema para que auditores documenten hallazgos. <br> **Subsanación de Actividades**: Campo `subsanado_at` para registrar correcciones. <br> **Usuarios Activos**: Campo booleano para desactivar usuarios sin eliminarlos. <br> **Notificaciones por Email**: Emails automáticos para solicitudes de reapertura (creada y resuelta). |

