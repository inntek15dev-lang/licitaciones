---
description: Comando SKILL DATAMODEL - Ejecuta la skill data-modeler para modelar BD y generar Laravel stack
---

# SKILL DATAMODEL - Modelado de Base de Datos

Cuando el usuario dice **"SKILL DATAMODEL"**, se ejecuta directamente la skill `data-modeler` para modelar la base de datos desde documentación o SQL existente.

// turbo-all

## Ejecución Automática

### Paso 1: Verificar Pre-condición
- Revisar si existe algún archivo `.sql` en `.agent/BD/`
- Si **NO existe** → Proceder con Fase 1 y 2
- Si **existe** → Saltar a Paso 4

### Paso 2: Minar Documentación (Solo si no hay SQL)
- Escanear todos los archivos en `.agent/docs/`
- Extraer tablas, columnas, tipos y relaciones
- Construir modelo de entidades

### Paso 3: Generar SQL Base (Solo si no hay SQL)
- Crear archivo `schema_base.sql` en `.agent/BD/`
- Ordenar tablas por dependencias
- Aplicar normalización 3NF

### Paso 4: Generar Laravel Stack
- Leer SQL de `.agent/BD/*.sql`
- Generar Migrations (ordenadas por dependencia)
- Generar Models (con relaciones y DocBlocks)
- Generar Seeders (con DatabaseSeeder ordenado)

### Paso 5: Generar Datos para Documentación
- Crear/actualizar `public/docs/data/diagrams/model-data.json`
- Incluir tablas, columnas y relaciones para diagramas ER

### Paso 6: Notificar
- Reportar qué archivos fueron generados
- Mostrar advertencias si las hay (PKs faltantes, dependencias circulares)

## Notas
- Este comando absorbe la funcionalidad de `sql-to-laravel`
- Si no hay SQL, lo genera automáticamente desde la documentación
- Compatible con `project-docs` para generar diagramas de modelo relacional
