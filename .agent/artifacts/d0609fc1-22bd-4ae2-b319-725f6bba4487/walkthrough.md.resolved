# Symlink Compatibility - Guía y Diagnóstico

**Fecha:** 2026-01-26  
**Entorno:** Windows (Laragon) con Symlink

---

## Estado Actual ✅

```
C:\laragon\www\master  →  D:\OVAL -ROOT\git-master\master\master
                       (symlink válido)
```

---

## ¿Por qué falló `npm run dev`?

El error "El sistema no puede encontrar la ruta especificada" tiene dos causas:

| Causa | Impacto |
|-------|---------|
| **PowerShell Execution Policy** | Bloquea scripts `.ps1` de npm |
| **Resolución de rutas** | Node resuelve el symlink y puede confundirse |

---

## Consideraciones para Symlinks en Windows

### 1. Tipos de Enlaces en Windows

| Tipo | Comando | Atraviesa unidades | Recomendado |
|------|---------|-------------------|-------------|
| **Symlink** | `mklink /D` | ✅ Sí | ✅ |
| **Junction** | `mklink /J` | ❌ Solo misma unidad | ⚠️ |
| **Hard Link** | `mklink /H` | ❌ Solo archivos | ❌ |

> [!TIP]
> Tu enlace es un **symlink** (`mklink /D`), que es el correcto para cruzar de `C:` a `D:`.

### 2. Node.js y Symlinks

Node.js **resuelve symlinks al path real** por defecto. Esto puede causar:

| Problema | Síntoma |
|----------|---------|
| `__dirname` diferente | Scripts esperan `C:\laragon\www\master` pero obtienen `D:\OVAL...` |
| Hot Module Replacement (HMR) | Vite no detecta cambios correctamente |
| `node_modules` duplicados | Rutas inconsistentes entre dependencias |

### 3. Vite - Configuración Recomendada

```javascript
// vite.config.js
export default defineConfig({
    server: {
        host: 'localhost',
        hmr: { host: 'localhost' },
        watch: {
            // CRÍTICO: Permite seguir symlinks
            followSymlinks: true,
        },
    },
    resolve: {
        // Preservar symlinks en lugar de resolverlos
        preserveSymlinks: true,
    },
});
```

### 4. npm - Configuración Recomendada

```bash
# Evitar que npm resuelva symlinks
npm config set preserve-symlinks true
```

---

## Soluciones Específicas

### PowerShell Execution Policy

```powershell
# Opción 1: Usar CMD en lugar de PowerShell
cmd /c "npm run dev"

# Opción 2: Habilitar scripts (como Admin)
Set-ExecutionPolicy -ExecutionPolicy RemoteSigned -Scope CurrentUser
```

### Variables de Entorno Node.js

```bash
# En package.json scripts
"dev": "cross-env NODE_PRESERVE_SYMLINKS=1 vite"

# O directamente
set NODE_PRESERVE_SYMLINKS=1 && npm run dev
```

---

## Checklist de Compatibilidad

| Item | Estado | Acción |
|------|--------|--------|
| Symlink tipo correcto | ✅ symlink | - |
| Node.js instalado | ✅ v22.18.0 | - |
| `node_modules` presente | ✅ | - |
| Vite `preserveSymlinks` | ⚠️ Falta | Agregar a config |
| PowerShell policy | ⚠️ Bloqueado | Usar CMD o habilitar |

---

## Recomendación Final

> [!IMPORTANT]
> Para evitar problemas, **siempre ejecuta comandos npm desde el directorio real** (`D:\OVAL -ROOT\git-master\master\master`) o usa CMD en lugar de PowerShell.

Alternativamente, actualiza `vite.config.js` con `preserveSymlinks: true` para soporte completo.
