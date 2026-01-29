---
name: add-generic-sync
description: Crea un módulo de sincronización genérica parametrizable
---

# Generación de Módulo de Sincronización Genérica (Nativo)

Este skill genera los componentes necesarios para habilitar la sincronización de datos desde APIs externas de manera genérica y configurable.

## Componentes a Generar

1.  **Migración de Base de Datos**: Crear tabla `api_sync_configs` para guardar configuraciones de APIs (URL, Auth, Mapeos).
2.  **Modelo Eloquent**: `ApiSyncConfig` para gestionar la tabla.
3.  **Controlador**: `ApiSyncController` en `App\Http\Controllers\generic`. Debe manejar:
    *   Listado de configs.
    *   Prueba de conexión API (Test).
    *   Guardado de mapeos JSON.
    *   Ejecución de sync (Preview + Actions).
4.  **Rutas**: Rutas en `routes/web.php` bajo `admin/api-sync` protegidas `auth` + `privilegio.dinamico`.
5.  **Vistas Blade**:
    *   `index.blade.php`: Tabla de configuraciones.
    *   `config.blade.php`: Wizard de 3 pasos (Info, API Test, Mapeo Visual).
    *   `execute.blade.php`: Vista de ejecución con Modales (Ficha Local, Crear Nuevo).

## Consideraciones de Integración

*   **Privilegios Nativos**: No se deben generar seeders de Roles. El rol `admin` ya tiene acceso `*`.
*   **Seguridad**: API Key debe guardarse encriptada.
*   **UI/UX**: Usar Tailwind CSS + Alpine.js para la interactividad de los modales y el mapeador visual.
