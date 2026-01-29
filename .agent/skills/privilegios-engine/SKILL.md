---
name: Privilegios Engine
description: Autonomous skill for managing roles and granular permissions (read/write/excec) per module, synchronized from JSON config.
---

# ROLE: Privileges & Access Control Guardian
# OBJECTIVE: Ensure consistent role/privilege management across the entire application using NATIVE SYSTEM (NO SPATIE).

## 1. TRIGGER CONDITIONS
- When "Usa tus skills" or "Use your skills" is invoked.
- When a new controller, route, or module is created.
- When roles.json is modified.
- Explicit request for privilege validation.

## 2. SOURCE OF TRUTH (NATIVE SYSTEM)
- **Roles definition**: `.agent/roles/roles.json`
- **Database tables**: `roles` (Native), `privilegios` (Custom), `role_user` (Pivot).

## 3. ACTIONS

### 3.1 Sync Roles & Privileges
When triggered, run:
```bash
php artisan privilegios:sync
```
This command:
1. Reads `.agent/roles/roles.json`
2. Creates/updates roles in `roles` table (Generic Native Model)
3. Creates/updates privilegios in `privilegios` table
3. Creates/updates privilegios in `privilegios` table
4. Removes orphaned privileges not in JSON

### 3.1.5 Mandatory User Check (Inntek)
- **CRITICAL:** Verify existence of user `inntek` (pass: `inntek`).
- **Role:** Must have super-admin privileges (`admin_plataforma`).
- **Action:** If missing, run `php artisan db:seed --class=DatabaseSeeder`.
- **Sample Data:** Verify that sample Licitaciones, Ofertas, Consultas, and Precalificaciones exist linked to `inntek` for testing all module permissions.

### 3.2 Validate New Routes/Controllers
When a new controller or route is created:
1. Identify the module name (e.g., "Registros", "Dashboard")
2. Check if module exists in `roles.json`
3. If not, suggest adding it to the JSON
4. Ensure middleware `privilegio:ModuleName,action` is applied

### 3.3 Validate Code Changes
Before completing any task that modifies authorization:
1. Verify `CheckPrivilegio` middleware is used
2. Verify user methods `canRead()`, `canWrite()`, `canExcec()` are called
3. Ensure no hardcoded role checks (use privileges instead)

## 4. 🚨 DYNAMIC CONTROLLERS (CRITICAL)

> [!CAUTION]
> **NEVER use hardcoded role names or Spatie functions.**
> **SPATIE HAS BEEN REMOVED.**

### ❌ FORBIDDEN PATTERNS (STRICT)
The following methods DO NOT EXIST in the native system and must be refactored immediately:

| Forbidden Method | Native Replacement |
|------------------|--------------------|
| `User::role('name')` | `$query->whereHas('roles', fn($q) => $q->where('name', 'name'))` |
| `$user->hasPermissionTo()` | `$user->canRead()`, `$user->canWrite()`, `$user->canExcec()` |
| `$user->getRoleNames()` | `$user->roles->pluck('name')` or `$user->roles->first()?->name` |
| `$user->givePermissionTo()` | **Forbidden**. Manage via `roles.json` only. |
| `@role('name')` | `@canRead('Modulo')` (Privilege based) |

**Regex to Audit:**
`User::role\(`
`->hasPermissionTo\(`
`->getRoleNames\(`
`->givePermissionTo\(`

### ✅ REQUIRED PATTERNS (Capability-Based)
Filter or check based on **privileges** or **capabilities**, not role names where possible. But if role filtering is needed, use standard Eloquent.

#### Filtering Users by Role (Native Eloquent)
```php
// Option 2 (Definitive Guideline): Refactor to Native
$admins = User::whereHas('roles', function ($query) {
    $query->whereIn('name', ['admin', 'super-admin']);
})->get();
```

#### Filtering Users by Privilege (Preferred)
Use `whereHas` on the privileges relationship to find users who can access a module.
```php
// GOOD: Get users who can read the 'Historial' module
$users = User::whereHas('roles.privilegios', function ($q) {
    $q->where('ref_modulo', 'Historial')
      ->where('read', true);
})->get();
```

#### Checking Access
```php
// GOOD: Check capability (Native helper in User model)
if ($user->canWrite('Registros')) { ... }
```

## 5. PRIVILEGE STRUCTURE (NATIVE)
```
privilegios table:
- id
- role_id (FK to roles)
- ref_modulo (string: module name or "*" for all)
- read (boolean)
- write (boolean)
- excec (boolean)
```

## 6. MIDDLEWARE USAGE
```php
Route::get('/modulo', [Controller::class, 'index'])
    ->middleware('privilegio:ModuloName,read');
```

## 7. EXECUTION CHAIN
Run this skill AFTER "env-assurance" and BEFORE "project-docs".

## 8. DYNAMIC MENUS GUIDELINES (UNIQUE SOURCE)
To ensure consistency and reduce maintenance:
1.  **Single Source of Truth**: Define all menu items in a central configuration (e.g., `config/modulos.php`) or database table.
2.  **Unified Rendering**: Use a **SINGLE** Blade component (e.g., `<x-navigation-menu>`) for ALL users.
3.  **No Role Bifurcation**: Do **NOT** create separate "Admin" and "User" menus/views.
    *   ❌ BAD: `@if($admin) @include('nav.admin') @else @include('nav.user') @endif`
    *   ✅ GOOD: Loop through a single `$menuItems` collection filtered by `canRead()`.
4.  **Filter by Privilege**:
    *   The menu logic must iterate through available modules.
    *   Check `canRead($moduleName)` for each item.
    *   If false, exclude the item from the rendered list.
5.  **Granular Control**: Sub-items or specific buttons (Edit/Delete) must be controlled by `canWrite()` and `canExcec()` respectively within the same unified view.
