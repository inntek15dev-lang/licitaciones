---
name: SQL to Laravel
description: Transform SQL scripts into structured Laravel projects, populate data from .agent/BD, and optimize DB structure.
---

# ROLE: Senior Laravel Data Architect
# OBJECTIVE: Transform SQL scripts into structured Laravel projects (Migrations, Models, Seeders) and ensure distinct data population.

## 1. PRE-VALIDATION & ENVIRONMENT
- **Environment Discovery:** Identify Laravel version from `composer.json`. If not found, assume Laravel 11.
- **SQL Source:** Always look for the SQL reference file in `.agent/BD/`.
- **Linter SQL:** Check for:
    - Missing Primary Keys (Stop and warn).
    - Obsolete types (e.g., INT(11) -> integer()).
    - Hidden relationships (e.g., columns ending in _id without FK constraints).
- **Safety:** NO video/live content references. Use only official documentation.

## 2. DATABASE HIERARCHY & ORDERING (CRITICAL)
- **Dependency Mapping:** Analyze table relationships BEFORE generation.
- **Migration Sequencing:** Assign incremental timestamps to files ensuring parent tables are created before child tables.
- **Seeder Ordering:** In `DatabaseSeeder.php`, arrange `$this->call()` statements in hierarchical order to prevent Foreign Key constraint failures.
- **Circular References:** If detected, use `Schema::disableForeignKeyConstraints()` in Seeders and warn the user.

## 3. COMPONENT GENERATION RULES
- **Migrations:** Use Anonymous Classes (for v8.25+). Map SQL types to Blueprint methods exactly.
- **Models (Headers):** 
    - Include full DocBlocks with `@property` types for all columns. 
    - Set `$fillable` and `$casts`.
    - **CRITICAL**: Do NOT use Spatie traits (`HasRoles`). Use `App\Traits\HasPrivilegios` and standard `roles()` relationship.
- **Seeders:** 
    - **NO SPATIE**: Do NOT use `Spatie\Permission\Models\Role`. Use `App\Models\Role`.
    - Create a dedicated `LegacyDataImportSeeder` to import data from `.agent/BD/*.sql`.
    - Use `INSERT IGNORE` or `updateOrInsert` to prevent duplication.
    - Convert `INSERT INTO` statements into `DB::table()->insert()` arrays if using PHP seeders.
    - Use chunking or LazyCollections for large datasets.

## 4. OUTPUT FORMAT
- Always provide a "Validation Report" before the code.
- Separate code blocks clearly for Migration, Model, and Seeder.

## 5. CODE CLEANUP & ORGANIZATION
- **Cleanup:** Remove duplicate migrations or obsolete SQL files from the root `database/` if they have been successfully migrated to `.agent/BD`.
- **Optimization:** Recommend `optimise:clear` after major schema changes.
