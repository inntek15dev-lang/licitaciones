---
description: Comando SKILL DOC - Actualiza la documentación completa del proyecto usando project-docs
---

# SKILL DOC - Actualización Directa de Documentación

Cuando el usuario dice **"SKILL DOC"**, se ejecuta directamente la skill `project-docs` para actualizar toda la documentación del proyecto.

// turbo-all

## Ejecución Automática

### Paso 1: Actualizar project.json
- Actualizar `lastUpdated` con la fecha/hora actual
- Verificar que `version`, `name` y `description` sean correctos

### Paso 2: Actualizar modules.json
- Escanear `app/Models/*.php` para detectar modelos
- Actualizar progreso y estado de cada módulo

### Paso 3: Actualizar skills.json
- Escanear `.agent/skills/*/SKILL.md`
- Extraer metadatos YAML, roles, objetivos y acciones
- Actualizar cadena de ejecución

### Paso 4: Verificar diagramas
- Asegurar que todos los diagramas en `public/docs/data/diagrams/` tengan roles definidos
- Validar formato XML

### Paso 5: Notificar
- Reportar al usuario qué archivos fueron actualizados
- Mostrar estadísticas de la documentación

## Notas
- Este comando es la forma más rápida de regenerar la documentación
- No ejecuta otras skills, solo `project-docs`
- Ideal para refrescar la documentación después de cambios manuales
