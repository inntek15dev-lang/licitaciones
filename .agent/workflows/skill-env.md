---
description: Comando SKILL ENV - Ejecuta la skill env-assurance sobre el proyecto
---

# SKILL ENV - Verificación y Aseguramiento del Ambiente

Cuando el usuario dice **"SKILL ENV"**, se ejecuta la skill `env-assurance` para validar el entorno, permisos y conectividad.

// turbo-all

## Acciones que ejecuta

1.  ### 🔌 Env Assurance
    - Leer: `.agent/skills/env-assurance/SKILL.md`
    - Validar paridad del entorno (Windows/Laragon vs Docker)
    - Verificar archivos críticos (`.env`, `package.json`, etc.)
    - Comprobar conectividad con base de datos
    - Verificar permisos de carpetas (`storage`, `bootstrap/cache`)
    - Validar configuración de Vite y symlinks

## Modos de Corrección

- **Windows:** Aplica permisos ACL y verifica ExecutionPolicy.
- **Linux/Docker:** Verifica permisos 775/644 y owner www-data.
- **Conectividad:** Realiza ping a DB y verifica puerto de Vite (5173).

## Ejemplo de Uso

```
Usuario: SKILL ENV
```

Esto ejecutará automáticamente todas las validaciones definidas en la skill.
