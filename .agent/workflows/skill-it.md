---
description: Comando SKILL IT - Ejecuta TODAS las skills de .agent para cualquier instrucción
---

# SKILL IT - Ejecución Completa de Skills

Cuando el usuario dice **"SKILL IT"** seguido de una instrucción, se deben ejecutar **TODAS** las skills de `.agent` en el siguiente orden:

// turbo-all

## Orden de Ejecución Obligatorio

### 1. 🧹 Maintenance (Limpieza)
- Leer: `.agent/skills/maintenance/SKILL.md`
- Eliminar `.gemini` si existe
- Organizar archivos sueltos a `.agent/context/` o `.agent/docs/`
- Verificar estructura de `.agent`

### 2. 📛 Naming Integrity (Integridad de Nombres)
- Leer: `.agent/skills/naming-integrity/SKILL.md`
- Validar que todos los archivos creados/modificados usen nombres seguros
- Sin espacios, sin caracteres especiales, sin HTML/código en nombres
- Máximo 80 caracteres por nombre

### 3. 🔌 Env Assurance (Ambiente)
- Leer: `.agent/skills/env-assurance/SKILL.md`
- Verificar conectividad DB si aplica
- Verificar Vite/servidor dev si aplica

### 4. 📐 Data Modeler (Modelado BD)
- Leer: `.agent/skills/data-modeler/SKILL.md`
- Si NO hay SQL en `.agent/BD/`, minar documentación `.agent/docs/`
- Generar `schema_base.sql` y reporte de descubrimiento de entidades
- Preparar terreno para generación de Laravel

### 5. 🗄️ SQL to Laravel (Base de Datos)
- Leer: `.agent/skills/sql-to-laravel/SKILL.md`
- Si hay scripts SQL en `.agent/BD/`, procesarlos
- Generar migraciones, modelos, factories según corresponda

### 6. 🔄 Add Generic Sync (Sincronización)
- Leer: `.agent/skills/add-generic-sync/SKILL.md`
- Verificar / Generar módulo de sincronización genérica si es necesario
- Asegurar existencia de `ApiSyncConfig` y controladores asociados

### 7. 🔐 Privilegios Engine (Permisos)
- Leer: `.agent/skills/privilegios-engine/SKILL.md`
- Sincronizar roles desde `.agent/roles/roles.json`
- Verificar directivas @canRead, @canWrite, @canExcec

### 8. 👁️ View Assurance (Vistas)
- Leer: `.agent/skills/view-assurance/SKILL.md`
- Validar rutas dinámicas en Blade
- Verificar uso correcto de `route()` y `url()`

### 9. 📚 Project Docs (Documentación) - SIEMPRE AL FINAL
- Leer: `.agent/skills/project-docs/SKILL.md`
- Generar/actualizar `skills.json` con inventario de skills
- Actualizar toda la documentación en `public/docs/data/`
- Generar informe de skills con funcionalidades

## Reglas Críticas

> [!CAUTION]
> TODAS las skills deben ejecutarse, sin excepción.

> [!IMPORTANT]
> La skill `project-docs` SIEMPRE se ejecuta al final para capturar el estado completo.

> [!NOTE]
> Si una skill no aplica al contexto actual, debe verificarse pero puede omitirse con justificación.

## Ejemplo de Uso

```
Usuario: SKILL IT - Agrega un nuevo campo "telefono" al modelo User
```

Esto significa:
1. Aplicar TODAS las skills
2. Agregar el campo telefono según las mejores prácticas
3. Documentar el cambio al final
