---
description: Comando SKILL ALL - Ejecuta SKILL IT sobre TODO el proyecto abierto de punta a punta
---

# SKILL ALL - Ejecución Completa de Skills sobre Todo el Proyecto

Cuando el usuario dice **"SKILL ALL"**, se ejecuta **SKILL IT** sobre todo el proyecto abierto de forma autónoma y completa, sin necesidad de instrucción adicional.

// turbo-all

## ¿Qué hace SKILL ALL?

A diferencia de `SKILL IT` (que requiere una instrucción específica), `SKILL ALL` realiza un **análisis y mantenimiento completo del proyecto** ejecutando todas las skills en modo auditoría/scan completo.

## Orden de Ejecución Obligatorio

### 1. 🧹 Maintenance (Limpieza Completa)
- Leer: `.agent/skills/maintenance/SKILL.md`
- Eliminar `.gemini` si existe
- Organizar TODOS los archivos sueltos a `.agent/context/` o `.agent/docs/`
- Verificar y corregir estructura completa de `.agent`
- Limpiar archivos temporales, logs innecesarios, caché

### 2. 📛 Naming Integrity (Validación de Nombres)
- Leer: `.agent/skills/naming-integrity/SKILL.md`
- Escanear TODOS los archivos del proyecto
- Validar nombres de archivos, directorios, clases, tablas
- Reportar y/o corregir nombres no seguros
- Sin espacios, sin caracteres especiales, máximo 80 caracteres

### 3. 🔌 Env Assurance (Verificación de Ambiente)
- Leer: `.agent/skills/env-assurance/SKILL.md`
- Verificar conectividad completa a base de datos
- Verificar Vite/servidor dev
- Validar `.env` y configuraciones críticas
- Detectar drift o inconsistencias de ambiente

### 4. 📐 Data Modeler (Modelado y Descubrimiento)
- Leer: `.agent/skills/data-modeler/SKILL.md`
- Ejecutar "Entity Discovery" multicanal (Modelos + Migraciones + SQL)
- Si faltan tablas en SQL, completar `schema_base.sql`
- Garantizar que la base de datos refleje la realidad del código

### 5. 🗄️ SQL to Laravel (Sincronización BD)
- Leer: `.agent/skills/sql-to-laravel/SKILL.md`
- Procesar TODOS los scripts SQL pendientes en `.agent/BD/`
- Verificar coherencia entre migraciones y modelos
- Validar factories y seeders existentes
- Generar migraciones faltantes si se detectan

### 6. 🔄 Add Generic Sync (Módulo Sincronización)
- Leer: `.agent/skills/add-generic-sync/SKILL.md`
- Validar existencia e integridad del módulo de sincronización
- Generar componentes `ApiSync` faltantes si no existen
- Asegurar configuración base de sincronización

### 7. 🔐 Privilegios Engine (Auditoría de Permisos)
- Leer: `.agent/skills/privilegios-engine/SKILL.md`
- Sincronizar roles desde `.agent/roles/roles.json`
- Auditar uso de directivas @canRead, @canWrite, @canExcec en TODAS las vistas
- Verificar que botones de edición tengan @canWrite
- Verificar que botones de eliminación tengan @canExcec
- Reportar vistas sin protección adecuada

### 8. 👁️ View Assurance (Validación de Vistas)
- Leer: `.agent/skills/view-assurance/SKILL.md`
- Escanear TODAS las vistas Blade del proyecto
- Validar rutas dinámicas (uso correcto de `route()` y `url()`)
- Verificar patrones de framework correctos
- Detectar URLs hardcodeadas o incorrectas

### 9. 📚 Project Docs (Documentación Completa) - SIEMPRE AL FINAL
- Leer: `.agent/skills/project-docs/SKILL.md`
- Generar/actualizar `skills.json` con inventario completo de skills
- Actualizar TODA la documentación en `public/docs/data/`
- Generar informe completo de skills con funcionalidades
- Documentar estado actual del proyecto

## Reglas Críticas

> [!CAUTION]
> SKILL ALL ejecuta TODAS las skills en modo completo. No requiere instrucción del usuario.

> [!IMPORTANT]
> Cada skill debe ejecutarse en modo "scan completo" analizando TODO el proyecto, no solo archivos nuevos o modificados.

> [!WARNING]
> Si hay errores críticos en alguna skill, documentarlos pero continuar con las siguientes.

> [!NOTE]
> Al finalizar, generar un resumen ejecutivo de lo encontrado y corregido.

## Diferencia con SKILL IT

| Aspecto | SKILL IT | SKILL ALL |
|---------|----------|-----------|
| Requiere instrucción | ✅ Sí | ❌ No |
| Alcance | Relacionado a la instrucción | Todo el proyecto |
| Modo de ejecución | Enfocado | Auditoría completa |
| Uso típico | Implementar algo nuevo | Mantenimiento general |

## Ejemplo de Uso

```
Usuario: SKILL ALL
```

Esto significa:
1. Ejecutar TODAS las skills sobre TODO el proyecto
2. Modo auditoría/scan completo
3. Corregir lo que se pueda automáticamente
4. Reportar hallazgos y estado final
5. Documentar todo al final

## Salida Esperada

Al finalizar SKILL ALL, se debe generar un resumen que incluya:

- ✅ Skills ejecutadas exitosamente
- ⚠️ Warnings encontrados
- ❌ Errores que requieren atención manual
- 📊 Estadísticas del proyecto (archivos, vistas, modelos, etc.)
- 📚 Documentación actualizada
