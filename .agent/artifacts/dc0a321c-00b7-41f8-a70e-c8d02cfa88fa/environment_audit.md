# Environment & Project Stability Audit

This report detail inconsistencies found in the local Laragon environment and the project configuration that are affecting stability and UI visibility.

## 1. UI & Sidebar Inconsistency
- **Finding**: The sidebar uses `@if(Auth::user()->hasRole('...'))` to display menu items.
- **Cause**: Initial seeders populated the `users` table but did **not** assign the necessary Spatie roles to them.
- **Solution**: Refactored `Tier7UserSeeder.php` to use the `User` model and the `assignRole()` method.
- **Action Required**: Run `php artisan migrate:fresh --seed` to correctly initialize users with their roles.

## 2. El "Switcher" de Assets (Vite vs Prod)
- **Cómo funciona**: Laravel usa la directiva `@vite` para cargar estilos.
  - **Modo Desarrollo (`npm run dev`)**: Crea un archivo `public/hot`. Laravel detecta este archivo y busca los estilos en el host configurado (ahora `localhost`).
  - **Modo Producción (`artisan serve` solo)**: Si **no** existe `public/hot`, Laravel busca en `public/build/manifest.json`.
- **Configuración Actual**: Hemos sincronizado todo para usar **`localhost`**.
  - **Browser**: `http://localhost:8000`
  - **.env**: `APP_URL=http://localhost:8000`
  - **vite.config.js**: `host: 'localhost'`
- **Recomendación**: Usa siempre `http://localhost:8000`. Si usas `127.0.0.1`, los estilos podrían fallar debido a políticas de seguridad del navegador (CORS/Mixed Content) al intentar conectar con el servidor de Vite en `localhost`.

## 3. Environment Dependencies (PHP)
- **Finding**: Critical extensions for Laravel 12 (like `zip`) were disabled in `C:\php\php.ini`.
- **Cause**: Default PHP installation in Laragon may come with minimal extensions enabled for performance.
- **Action Taken**: Enabled `extension=zip` in the global `php.ini`.
- **Recommendation**: Ensure `openssl`, `mbstring`, `intl`, and `pdo_mysql` are also enabled (verified they are currently active).

## 4. Stability Summary Table

| Component | Status | Issue | Resolution |
|-----------|--------|-------|------------|
| RBAC | ⚠️ Pending Reset | Missing user-role mapping | Updated `Tier7UserSeeder` |
| Assets | ⚠️ Verification | Host/URL mismatch | Updated `vite.config.js` & `.env` |
| Infrastructure | ✅ Fixed | Missing `zip` extension | Modified `php.ini` |

> [!IMPORTANT]
> To stabilize the project, please execute a full reset of the state:
> ```bash
> php artisan migrate:fresh --seed
> npm run dev
> ```
