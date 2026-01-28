# 📋 Pendientes del Proyecto OIEM Abastible

> **Última actualización**: 12 de enero de 2026  
> **Fuente**: Reunión de revisión del sistema (09/01/2026)  
> **Estado General**: 🟢 Presentación exitosa - Sistema funcional con todas las piezas implementadas

---

## � Resumen de la Reunión (09/01/2026)

La presentación fue muy bien recibida. Se demostró que **"el sistema tiene casi todas las piezas"** y ahora resta **"ordenar las piezas y generar algunas reglas del juego"**. Se validaron las funcionalidades implementadas y se identificaron las decisiones pendientes que Abastible debe tomar.

---

## 🔴 Requerimientos Pendientes de Desarrollo

### ~~1. Trazabilidad de Auditoría~~ ✅ IMPLEMENTADO
**Estado**: ✅ Implementado (v1.5 - 12/01/2026) - Ver Módulo 14 en DOCUMENTACION.md

---

### 2. Registro de Reuniones de Accountability
**Solicitado por**: karla aguirre  
**Descripción**: Cargar y almacenar los registros de las reuniones mensuales de accountability entre contratistas y administradores de contrato.

**Consideraciones**:
- Permitir adjuntar documentos (actas, presentaciones)
- Vincular con el periodo correspondiente
- Visible tanto para admin como para contratista

**Estado**: ⏳ Pendiente (Prioridad Baja)

---

### 3. Gestión de Acciones Correctivas / Brechas
**Solicitado por**: karla aguirre / Katherine Dominguez  
**Descripción**: Cuando el auditor detecta un incumplimiento (brecha), debe poder:
- Registrar las acciones correctivas acordadas
- Asignar responsables
- Definir fechas de compromiso
- Hacer seguimiento al cierre de las acciones

**Contexto de la reunión**: Katherine mencionó que durante la auditoría "el administrador de contrato se va a sentar con la empresa contratista [...] y va a definir porque va a detectar brechas [...] y va a definir generar compromisos."

**Estado**: ⏳ Pendiente (Prioridad Media)

---

### 4. Conexión con Base de Datos ACEM
**Solicitado por**: Francisco García / Katherine Dominguez  
**Descripción**: Integrar el sistema OIEM con la base de datos ACEM.

**Datos confirmados para traer desde ACEM**:
- ✅ Nombre de empresa
- ✅ RUT
- ✅ Teléfono
- ✅ Tipo de servicio
- ✅ Email de contacto

**Aclaraciones de la reunión**:
- El administrador de contrato en ACEM **NO es el mismo** que administra el programa OIEM
- Katherine: *"el administrador de contrato que tenemos en ACEM probablemente no es la misma persona que está configurada aquí"*
- Se debe permitir que el contratista indique quién administrará el programa dentro de la plataforma

**Próximo paso**: Abastible debe entregar estructura de datos y acceso a ACEM

**Estado**: ⏳ Pendiente (depende de IT Abastible)

---

### 5. Carga Masiva de Contratistas
**Solicitado por**: maria jose aguilera  
**Descripción**: Herramienta para cargar múltiples contratistas de forma masiva:
- Desde archivo Excel/CSV
- Directamente desde ACEM (cuando esté conectado)

**Estado**: ⏳ Pendiente

---

### 6. Carga de Histórico de Cumplimiento
**Solicitado por**: karla aguirre  
**Descripción**: Cargar datos históricos de cumplimiento (3-6 meses atrás) desde los Excel actuales.

**Decisión pendiente**: Definir cuántos meses retroactivos cargar.

**Estado**: 🟡 Por evaluar

---

## 🟢 Requerimientos ya Implementados

| # | Funcionalidad | Fecha |
|---|---------------|-------|
| 1 | Dos columnas de porcentaje (Contratista vs Auditor) | 06/01/2026 |
| 2 | Mostrar "Cumple" / "No Cumple" en vez de 0/1 | 06/01/2026 |
| 3 | Filtros por EECC, Dependencia, Programa, Auditoría | Implementado |
| 4 | Auditoría de Sistema y Terreno | Implementado |
| 5 | Mantenedor de Programas, Elementos y Actividades | Implementado |
| 6 | Gestión de usuarios operativos por contratista | 06/01/2026 |
| 7 | Periodo siguiente automático en Dashboard | 06/01/2026 |
| 8 | **Trazabilidad de Auditoría** - Logs detallados de todas las acciones | 12/01/2026 |
| 9 | **Sistema de Solicitudes de Reapertura** - Flujo completo con aprobación/rechazo y fecha límite | 12/01/2026 |
| 10 | **Estados de Auditoría** - Campo estado_auditoria en registros | 12/01/2026 |
| 11 | **Comentarios de Auditoría** - Documentar hallazgos durante auditoría | 12/01/2026 |
| 12 | **Subsanación de Actividades** - Campo subsanado_at para correcciones | 12/01/2026 |
| 13 | **Usuarios Activos** - Campo para desactivar usuarios sin eliminarlos | 12/01/2026 |
| 14 | **Notificaciones por Email** - Emails automáticos para reaperturas | 12/01/2026 |
| 15 | **Exportación PDF de Trazabilidad** - Historial exportable | 12/01/2026 |
| 16 | **Contratista multi-servicio/multi-dependencia** - Un contratista puede operar varios servicios en varias plantas | Validado 09/01 |
| 17 | **Periodo de inicio por asignación** - Cada servicio/dependencia tiene su fecha de inicio | Validado 09/01 |
| 18 | **Exportación PDF de Registro** - Informe completo con datos y trazabilidad | Validado 09/01 |
| 19 | **Pausar/Continuar Auditoría** - Admin puede guardar progreso de auditoría | Validado 09/01 |

---

## 🟡 Decisiones Pendientes de Abastible

> Estas decisiones son **críticas** para configurar las reglas del sistema. Como dijo Marcos: *"es el fino del flujo real como tiene que ser [...] mientras más claro esté, más rápido terminamos"*

| # | Decisión/Regla de Negocio | Responsable | Prioridad |
|---|---------------------------|-------------|-----------|
| 1 | **¿Evidencia obligatoria?** - ¿Es obligatorio cargar evidencia para cerrar un registro? | Katherine/Karla | 🔴 Alta |
| 2 | **¿Contratista puede editar datos?** - ¿Bloquear edición de datos maestros del contratista? | Katherine | 🔴 Alta |
| 3 | **¿Quién crea usuarios contratista?** - ¿El mismo contratista o el admin de contrato? | Katherine | 🟡 Media |
| 4 | **¿Botón eliminar contratista?** - ¿Debe existir o estar oculto/restringido? | Katherine | 🟡 Media |
| 5 | **Plantillas de programas** - Estructura de elementos y actividades por programa | maria jose / karla | 🔴 Alta |
| 6 | **Contenido del Dashboard** - ¿Qué KPIs quieren ver en el resumen ejecutivo? | Katherine | 🟡 Media |
| 7 | **Contenido de Reportes** - ¿Qué gráficos y estadísticas requieren? | Katherine | 🟡 Media |
| 8 | **Acceso a ACEM** - Estructura de datos y credenciales para conexión | IT Abastible | 🔴 Alta |
| 9 | **Meses de histórico** - ¿Cuántos meses retroactivos cargar del Excel actual? | karla | 🟢 Baja |
| 10 | **Hosting del sistema** - ¿Dónde se alojará el sistema? | IT Abastible | 🔴 Alta |
| 11 | **Plazos en acreditación** - ¿Implementar plazos de subsanación similar a este sistema? | karla | 🟢 Baja |

---

## ✅ Funcionalidades Validadas en la Reunión

Las siguientes funcionalidades fueron demostradas y **aprobadas** durante la presentación:

1. ✅ **Contratista multi-servicio**: Un contratista puede operar Granel y Envasado en múltiples plantas
2. ✅ **Asignación de Admin de Contrato**: Cada servicio/dependencia tiene su propio administrador asignado
3. ✅ **Periodo de inicio diferenciado**: Cada asignación puede tener diferente fecha de inicio
4. ✅ **Usuarios operativos del contratista**: El contratista puede crear sus propios usuarios con servicios/dependencias específicos
5. ✅ **Solicitud de reapertura con fecha límite**: El admin define un plazo para subsanar
6. ✅ **Exportación PDF completa**: Registro con todos los datos y trazabilidad exportable
7. ✅ **Trazabilidad detallada**: Usuario, acción, fecha/hora de cada cambio
8. ✅ **Pausar auditoría**: El admin puede guardar y continuar después

---

## 📅 Próximos Pasos

### Para Oval (Desarrollo)
1. ~~✅ Implementar trazabilidad de auditoría~~
2. ⏳ Diseñar módulo de gestión de brechas/acciones correctivas
3. ⏳ Preparar estructura para conexión con ACEM
4. ⏳ Esperar definiciones de reglas de negocio

### Para Abastible
1. 🔴 **Enviar plantillas de programas** (elementos y actividades)
2. 🔴 **Responder preguntas de reglas de negocio** (ver tabla anterior)
3. 🔴 **Definir hosting** del sistema
4. 🔴 **Entregar acceso/estructura de ACEM**

---

## 📞 Contactos del Proyecto

### Abastible
- **Katherine Dominguez** - Control de Gestión y Servicio de Terceros
- **karla aguirre** - Acreditación (plataforma Oval)
- **maria jose aguilera** - Gestión de Programas

### Oval
- **Francisco García** - Gestión Comercial
- **Nicolas Córdova** - Acreditación y Cumplimiento
- **Marcos Alarcón** - Desarrollo del Sistema
- **Rodrigo Zapata** - (Comunicaciones pendientes)

---

## 💬 Citas Relevantes de la Reunión

> *"Este sistema tiene casi todas las piezas. Ahora después falta ordenar las piezas y generar algunas reglas del juego."* - Marcos Alarcón

> *"Es el fino del flujo real como tiene que ser [...] eso tiene que ser súper al detalle porque ese es el problema de los desarrollos que a veces se tornan interminables y eternos [...] mientras más claro esté, más rápido terminamos."* - Marcos Alarcón

> *"El administrador de contrato que tenemos en ACEM probablemente no es la misma persona que está configurada aquí para administrar el programa."* - Katherine Dominguez

---

> **Nota**: Este documento debe actualizarse después de cada reunión semanal.
