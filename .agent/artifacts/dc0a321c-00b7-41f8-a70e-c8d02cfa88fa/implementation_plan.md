# Transform SQL Database to Laravel Components

This project involves migrating a legacy SQL schema into a modern Laravel application. The goal is to generate Migrations, Models, Controllers, FormRequests, and API Resources for over 80 tables, maintaining all relationships and constraints.

## User Review Required

> [!IMPORTANT]
> The database schema is highly interconnected. We will follow a **Tiered Migration Strategy** to ensure foreign key dependencies are handled in the correct order.

> [!WARNING]
> Due to the large number of tables, we will generate components in logical groups (Tiers) to maintain stability and facilitate verification.

## Tiered Migration Strategy

| Tier | Component Type | Key Tables |
| :--- | :--- | :--- |
| **Tier 1** | Base/Generic | Roles, Permissions, Mutualidades, Nacionalidades, Tipos_* |
| **Tier 2** | Core Mandantes | Mandantes, Comunas, Regiones |
| **Tier 3** | Org Structures | Unidades Organizacionales, Dependencias |
| **Tier 4** | Relations & Rules | Contratistas, Reglas Documentales |
| **Tier 5** | Primary Entities | Trabajadores, Vehiculos, Maquinarias, Embarcaciones |
| **Tier 6** | Assignments | Vinculaciones, Asignaciones, Documentos Cargados |
| **Tier 7** | System & Users | Users, Activity Log, Notifications |

## Proposed Changes

### Database Layer
- **[NEW]** Migrations for 80+ tables, organized by the tiers above.
- **[NEW]** Seeders for static data (Nacionalidades, Sexos, etc.) extracted from SQL `INSERT` statements.

### Application Layer
- **[NEW]** Laravel Models for each table with defined `$fillable` and relationship methods (`belongsTo`, `hasMany`).
- **[NEW]** API Controllers for CRUD operations using standard Laravel patterns.
- **[NEW]** FormRequests for robust validation based on SQL column types and constraints.
- **[NEW]** API Resources for consistent JSON output formatting.

## Verification Plan

### Automated Tests
1. `php artisan migrate:fresh` to verify migration order and constraints.
2. `php artisan db:seed` to verify static data ingestion.
3. Feature tests for primary CRUD operations (Mandantes, Trabajadores).

### Manual Verification
1. Inspecting generated code for correct relationship definitions.
2. Verifying Model `$casts` for JSON and Date columns.
