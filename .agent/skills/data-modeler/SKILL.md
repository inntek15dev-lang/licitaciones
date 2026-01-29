---
name: Data Modeler
description: Autonomous skill for database modeling from documentation when no SQL exists, then generates complete Laravel stack (Migrations, Models, Seeders).
---

# ROLE: Senior Database Architect & Laravel Specialist
# OBJECTIVE: Model database from documentation OR transform existing SQL into complete Laravel components.

## 0. PRE-CONDITION CHECK (CRITICAL - EXECUTE FIRST)

```
CHECK: Does `.agent/BD/` contain any `.sql` files?

├── YES (.sql exists) ──► SKIP Phase 1 & 2, go directly to Phase 3 (Laravel Generation)
│
└── NO (.sql NOT found) ──► Execute Phase 1 (Documentation Mining) + Phase 2 (SQL Generation)
```

**Directory Creation**: If `.agent/BD/` doesn't exist, create it before proceeding.

---

## 0.5 MULTI-SOURCE ENTITY DISCOVERY (MANDATORY)

> [!CAUTION]
> **NEVER rely on a single source for entity discovery.** Tables created via Laravel migrations AFTER `schema_base.sql` was generated will be MISSING from documentation.

### Purpose
Ensure **ALL** database entities are captured by scanning multiple sources and merging results.

### Discovery Sources (Priority Order)

| Priority | Source | Command/Path | Detects |
|----------|--------|--------------|---------|
| 1 (Highest) | **Laravel Models** | `app/Models/*.php` | Entities with business logic |
| 2 | **Laravel Migrations** | `database/migrations/*create*table*.php` | Runtime-created tables |
| 3 | **Menu Config** | `config/modulos.php` | Modules visible in UI |
| 4 (Lowest) | **SQL Schema** | `.agent/BD/*.sql` | Base schema tables |

### Discovery Commands

```bash
# 1. Get models from app/Models
ls app/Models/*.php | xargs -I{} basename {} .php

# 2. Extract table names from migrations
grep -l "Schema::create" database/migrations/*.php | xargs grep -oP "Schema::create\('\K[^']+"

# 3. Get tables from actual DB (if connected)
php artisan tinker --execute="collect(DB::select('SHOW TABLES'))->pluck('Tables_in_'.config('database.connections.mysql.database'))->dump()"
```

### Merge Strategy

1. **Collect** entities from all sources
2. **Deduplicate** by converting model names to table names (e.g., `Compromiso` → `compromisos`)
3. **Compare** with `schema_base.sql` tables
4. **Report Missing** entities that exist in Models/Migrations but NOT in SQL
5. **Auto-Add** missing tables to `schema_base.sql` if migration exists

### Validation Report Format

```
╔═══════════════════════════════════════╗
║   ENTITY DISCOVERY REPORT             ║
╠═══════════════════════════════════════╣
║ Models Found:      18                 ║
║ Migrations Found:  22                 ║
║ SQL Tables Found:  15                 ║
╠═══════════════════════════════════════╣
║ ⚠️ MISSING FROM SQL:                  ║
║   - compromisos (Model + Migration)   ║
║   - privilegios (Model + Migration)   ║
╠═══════════════════════════════════════╣
║ ACTION: Add missing tables to SQL     ║
╚═══════════════════════════════════════╝
```

---

## 1. PHASE 1: DOCUMENTATION MINING (Only if NO SQL exists)

### 1.1 Documentation Sources
Scan ALL files in `.agent/docs/` exhaustively:
- `*.md` (Markdown documentation)
- `*.txt` (Text documents)
- `*.csv` (Data matrices)

### 1.2 Entity Extraction
For each document, identify and extract:

| Pattern | Example | Extraction |
|---------|---------|------------|
| Table definitions | `users`, `registros` | Table name |
| Field lists | `├── id`, `- nombre (string)` | Column + type |
| Relationships | `User hasMany Registro` | FK relationships |
| Enums | `enum: mensual, trimestral` | ENUM type |
| Foreign Keys | `user_id (FK)`, `FK a users.id` | FK constraint |
| Indexes | `único`, `unique` | UNIQUE constraint |

### 1.3 Documentation Keywords to Detect

**Table Indicators:**
- "Tabla:", "Tablas principales", "Modelo:", "Entidad:"
- Lines with `├──`, `└──`, `│` (tree structure)
- Markdown tables with columns: Tabla, Campo, Descripción

**Type Mappings:**
| Doc Pattern | MySQL Type |
|-------------|------------|
| `id` | `BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY` |
| `string`, `texto`, `nombre` | `VARCHAR(255)` |
| `text`, `descripcion`, `comentario` | `TEXT` |
| `int`, `integer`, `cantidad` | `INT` |
| `decimal`, `porcentaje`, `%` | `DECIMAL(8,2)` |
| `boolean`, `activo`, `cumple` | `TINYINT(1)` |
| `date`, `fecha` | `DATE` |
| `timestamp`, `timestamps` | `TIMESTAMP` |
| `enum:...` | `ENUM(...)` |
| `*_id`, `FK` | `BIGINT UNSIGNED` (foreign key) |
| `nullable` | `NULL` allowed |

### 1.4 Relationship Detection

**Patterns to identify:**
```
"User hasMany Registro" → registros.user_id → users.id
"Registro belongsTo User" → registros.user_id → users.id
"RegistroActividad hasOne Evidencia" → evidencias.registro_actividad_id
"Elemento hasMany Actividad" → actividades.elemento_id → elementos.id
```

**Column naming conventions:**
- `*_id` suffix indicates foreign key to singular table name
- `parent_id` = self-referencing FK

---

## 2. PHASE 2: SQL SCHEMA GENERATION (Only if NO SQL exists)

### 2.1 Schema Normalization
- Apply 3NF (Third Normal Form)
- Ensure all tables have PRIMARY KEY
- Add `created_at`, `updated_at` timestamps to all transactional tables
- Add `deleted_at` for soft deletes where appropriate

### 2.2 SQL File Generation

Generate file: `.agent/BD/schema_base.sql`

**SQL Template:**
```sql
-- =====================================================
-- DATABASE SCHEMA - Auto-generated from documentation
-- Generated: [TIMESTAMP]
-- Source: .agent/docs/*
-- =====================================================

SET FOREIGN_KEY_CHECKS = 0;

-- -----------------------------------------------------
-- Table: [table_name]
-- Source: [document_file]
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `[table_name]` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    [columns...],
    `created_at` TIMESTAMP NULL DEFAULT NULL,
    `updated_at` TIMESTAMP NULL DEFAULT NULL,
    PRIMARY KEY (`id`),
    [constraints...]
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- [Repeat for all tables...]

SET FOREIGN_KEY_CHECKS = 1;
```

### 2.3 Dependency Ordering
Order CREATE TABLE statements by dependency:
1. Independent tables first (no FKs)
2. Tables with FKs to already-defined tables
3. Use `SET FOREIGN_KEY_CHECKS = 0/1` for circular deps

---

## 3. PHASE 3: LARAVEL GENERATION (Always executed)

> **NOTE**: This phase absorbs and extends the `sql-to-laravel` skill functionality.

### 3.1 Pre-Validation
- **Environment Discovery:** Identify Laravel version from `composer.json`. If not found, assume Laravel 11+.
- **SQL Source:** Use SQL files from `.agent/BD/`.
- **Linter SQL:** Check for:
    - Missing Primary Keys (Stop and warn)
    - Obsolete types (e.g., INT(11) → integer())
    - Hidden relationships (columns ending in `_id` without FK constraints)

### 3.2 Database Hierarchy & Ordering (CRITICAL)
- **Dependency Mapping:** Analyze table relationships BEFORE generation.
- **Migration Sequencing:** Assign incremental timestamps ensuring parent tables created before children.
- **Seeder Ordering:** In `DatabaseSeeder.php`, arrange `$this->call()` in hierarchical order.
- **Circular References:** If detected, use `Schema::disableForeignKeyConstraints()` and warn.

### 3.3 Component Generation Rules

#### Migrations
- Use Anonymous Classes (Laravel 8.25+)
- Map SQL types exactly to Blueprint methods:

| SQL Type | Blueprint Method |
|----------|------------------|
| `BIGINT UNSIGNED` | `unsignedBigInteger()` or `id()` |
| `VARCHAR(N)` | `string('col', N)` |
| `TEXT` | `text()` |
| `INT` | `integer()` |
| `DECIMAL(P,S)` | `decimal('col', P, S)` |
| `TINYINT(1)` | `boolean()` |
| `DATE` | `date()` |
| `TIMESTAMP` | `timestamp()` or `timestamps()` |
| `ENUM(...)` | `enum('col', [...])` |

#### Models
- Include full DocBlocks with `@property` types for ALL columns
- Set `$fillable` array with all fillable columns
- Set `$casts` for type casting (booleans, decimals, dates, enums)
- **CRITICAL**: Do NOT use Spatie traits. Use `App\Traits\HasPrivilegios` and standard `roles()` relationship.
- Define all relationships (`hasMany`, `belongsTo`, `hasOne`, etc.)

#### Seeders
- **NO SPATIE**: Do NOT use `Spatie\Permission\Models\Role`. Use `App\Models\Role`.
- Create `LegacyDataImportSeeder` to import data from `.agent/BD/*.sql` if data INSERTs exist.
- Use `INSERT IGNORE` or `updateOrInsert` to prevent duplication.
- Use chunking or LazyCollections for large datasets.

### 3.4 Output Format
- Provide "Validation Report" before code
- Separate code blocks for: Migration, Model, Seeder

---

## 4. PHASE 4: DOCUMENTATION OUTPUT (For project-docs skill)

### 4.1 Generate Diagram Data
Create/update: `public/docs/data/diagrams/model-data.json`

**JSON Structure:**
```json
{
  "generated_at": "TIMESTAMP",
  "source": ".agent/BD/*.sql",
  "tables": [
    {
      "name": "table_name",
      "columns": [
        {"name": "id", "type": "BIGINT UNSIGNED", "primary": true},
        {"name": "column", "type": "VARCHAR(255)", "nullable": false}
      ],
      "relationships": [
        {"type": "belongsTo", "target": "other_table", "fk": "other_id"}
      ]
    }
  ],
  "relationships": [
    {"from": "table_a", "to": "table_b", "type": "1:N", "fk": "table_a_id"}
  ]
}
```

### 4.2 Mermaid ER Diagram
Generate Mermaid syntax for ERD:
```mermaid
erDiagram
    USERS ||--o{ REGISTROS : "has many"
    REGISTROS ||--o{ REGISTRO_ACTIVIDADES : "has many"
    ...
```

---

## 5. EXECUTION CHECKLIST

```
[ ] 1. Check for .sql files in .agent/BD/
[ ] 2. If NO SQL: Mine documentation in .agent/docs/
[ ] 3. If NO SQL: Generate schema_base.sql in .agent/BD/
[ ] 4. Parse SQL and build dependency graph
[ ] 5. Generate Migrations (ordered by dependency)
[ ] 6. Generate Models (with relationships)
[ ] 7. Generate Seeders (ensure 'inntek' user creation is included)
[ ] 8. Generate model-data.json for project-docs
[ ] 9. Provide validation report
```

### 5.1 Sample Data Generation Rule (CRITICAL)
> [!IMPORTANT]
> When generating or modifying Seeders, ALWAYS include logic to:
> 1. Create the `inntek` user (user: inntek / pass: inntek) with `admin_plataforma` role.
> 2. Create a test contractor company "Inntek Test SpA" linked to `inntek`.
> 3. Generate **at least 3 sample records per entity** (Licitaciones, Ofertas, Consultas, Precalificaciones, etc.).
> 4. Ensure records form **coherent business flows** (e.g., a Licitacion with Ofertas and Precalificaciones).
> 5. Link sample records to `inntek` to enable full system exploration during testing.

---

## 6. SAFETY & QUALITY

- **NO video/live content references**. Use only official documentation.
- **Backup existing files** before overwriting.
- Run `php artisan migrate:fresh --seed` only with user confirmation.
- Recommend `php artisan optimize:clear` after major schema changes.
- **Cleanup**: Remove duplicate migrations or obsolete files if successfully generated.
