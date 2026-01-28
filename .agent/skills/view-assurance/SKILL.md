---
name: View Generation Assurance
description: Ensures Blade views are generated with correct routing, dynamic URLs, and framework patterns to prevent common runtime errors.
---

# View Generation Assurance Skill

## Purpose
Prevent common Blade/Laravel view errors by validating and applying best practices during view generation, particularly for dynamic URLs, Alpine.js integration, route helpers, and **dynamic privilege-based access control**.

## Trigger Conditions
- Creating or modifying `.blade.php` files
- Implementing modals with dynamic actions
- Using Alpine.js with Laravel routes
- Forms with dynamic route parameters
- **Any view with role-based conditional rendering**

---

## 🚨 CRITICAL: No Hardcoded Roles & NO SPATIE

> [!CAUTION]
> **NEVER use hardcoded role names in views OR controllers.**
> **SPATIE IS GONE.** Do not use `@role` or `@hasrole` directives from Spatie.

### ❌ FORBIDDEN Patterns
```blade
{{-- NEVER DO THIS --}}
@if(auth()->user()->hasRole('admin'))       {{-- ❌ Hardcoded --}}
@role('admin')                             {{-- ❌ Spatie (Removed) --}}
@hasrole('contratista')                    {{-- ❌ Spatie (Removed) --}}
@permission('edit articles')               {{-- ❌ Spatie (Removed) --}}
```

### ✅ REQUIRED Patterns

#### In Views
```blade
{{-- Use custom privilege-based directives --}}
@canRead('Modulo')
    {{-- Content visible to users with read privilege on Modulo --}}
@endcanRead

@canWrite('Modulo')
    {{-- Content visible to users with write privilege --}}
@endcanWrite

@canExcec('Modulo')
    {{-- Content visible to users with execute privilege --}}
@endcanExcec
```

#### In Controllers (Passing Data)
Filter data based on privileges or relationships, not roles.
```php
// GOOD: Filter users by access to a module
$contratistas = User::whereHas('roles.privilegios', fn($q) => 
    $q->where('ref_modulo', 'Historial')->where('read', true)
)->get();

return view('index', compact('contratistas'));
```

### Role Display (Badges)
```blade
{{-- ✅ CORRECT --}}
@php
    $primerRol = auth()->user()->roles->first();
    $nombreRol = $primerRol?->nombre_display ?? $primerRol?->name ?? 'Usuario';
@endphp
<span>{{ $nombreRol }}</span>
```

---

## Dynamic Navigation Components

### Instead of hardcoded menus, use:
```blade
{{-- Desktop menu --}}
<x-navigation-menu :user="auth()->user()" />

{{-- Mobile menu --}}
<x-mobile-navigation-menu :user="auth()->user()" />
```

These components:
- Read from `config/modulos.php`
- Filter items based on user privileges from DB
- Automatically update when roles/privileges change

---

## Validation Checklist

When generating/reviewing views, verify:

### Privileges (🚨 Critical)
- [ ] **No `@role`, `@hasrole`, `@permission` directives**
- [ ] **No `hasRole()` calls** - Use `@canRead`, `@canWrite`, `@canExcec`
- [ ] **No hardcoded role names** - Read from DB via PrivilegioService
- [ ] **Menus use dynamic components** - `<x-navigation-menu>`, `<x-mobile-navigation-menu>`

---

## Safe Patterns Reference

### Privilege-Based Conditionals
```blade
@canRead('Registros')
    <a href="{{ route('admin.registros.index') }}">Ver Registros</a>
@endCanRead

@canWrite('Usuarios')
    <button>Crear Usuario</button>
@endCanWrite
```

### Dynamic Routes (Alpine.js)
```blade
'{{ url('admin/roles') }}/' + roleId
'{{ url('api/items') }}/' + itemId + '/action'
```

---

## Related Skills
- `privilegios-engine` - Core privilege management
- `maintenance` - Code cleanup
