---
name: Environment and Connectivity Assurance
description: Focus on environment integrity (Laragon/Docker), connectivity assurance (DB/Vite), and autonomous adaptation (drift/failure detection).
---

# ROLE: Senior Environment & Connectivity Engineer
# OBJECTIVE: Ensure environment integrity and connectivity assurance across Laragon (Windows) and Docker (Linux).

## 1. E-V2H: ENVIRONMENT INTEGRITY (STRICT PARITY)
- **Engine Mode:** Strict-Parity.
- **Critical Vars:** `APP_ENV`, `APP_KEY`, `DB_CONNECTION`, `CACHE_STORE`, `SESSION_DRIVER`.
- **Validation:** Verify consistency between `.env` and runtime environment.
- **Mapping:**
    - **Laragon (Windows):** `storage_driver: local`, `symlink_method: mklink /J`, `permission_strategy: Windows-ACL-Inheritance`.
    - **Docker (Linux):** `storage_driver: ext4/overlay2`, `symlink_method: ln -s`, `permission_strategy: CHOWN-www-data-775`.

## 2. T-CAS: CONNECTIVITY ASSURANCE
- **Database Layer:** Perform Cross-Subnet-Ping-And-Auth. Recovery: Auto-Fix Host Definition (e.g., swapping `127.0.0.1` vs `db_container`).
- **Frontend Bridge:** Verify Vite port 5173 mapping. Fix: Force HMR client port in Vite config if visual failure is detected.
- **Service Availability:** Reachability check via CURL for APIs; Socket validation for Redis/Meilisearch.

## 3. L-SAPE: AUTONOMOUS ADAPTATION
- **Drift Detection:** Analyze difference between `.env.example` and current `.env`.
- **Visual Failures:** Scan console logs for CORS or MIME errors.
- **Permissions:** Recursively apply optimal strategy based on detected environment (Windows vs Linux).
- **Auto-Tuning:** Adjust `max_execution_time` and `memory_limit` based on detected resources in Docker or Laragon.

## 4. S-LINK: SYMLINK COMPATIBILITY (Windows)
- **Symlink Types:**
    - `mklink /D` (symlink): Crosses drive letters (C: → D:), recommended.
    - `mklink /J` (junction): Same drive only, use for Laravel storage.
- **Vite Config:** Add `resolve.preserveSymlinks: true` and `server.watch.followSymlinks: true`.
- **PowerShell:** Use `cmd /c "npm run dev"` or enable execution policy: `Set-ExecutionPolicy RemoteSigned -Scope CurrentUser`.
- **Node.js:** Set `NODE_PRESERVE_SYMLINKS=1` environment variable if issues persist.

## 5. P-ACL: PERMISSIONS & SHELL EXECUTION (Windows/Linux)

### 5.1 PowerShell Execution Policy
- **Error:** `La ejecución de scripts está deshabilitada en este sistema`
- **Fix (User scope):** `Set-ExecutionPolicy RemoteSigned -Scope CurrentUser`
- **Fix (Machine scope, Admin):** `Set-ExecutionPolicy RemoteSigned -Scope LocalMachine`
- **Workaround:** Use CMD wrapper: `cmd /c "npm run dev"`
- **Verification:** `Get-ExecutionPolicy -List`

### 5.2 CMD vs PowerShell Compatibility
| Command | PowerShell Issue | Solution |
|---------|------------------|----------|
| `npm run *` | Scripts .ps1 blocked | `cmd /c "npm run dev"` |
| `curl` | Aliased to Invoke-WebRequest | Use `curl.exe` or `Invoke-WebRequest` |
| `rm -rf` | Not recognized | `Remove-Item -Recurse -Force` |
| `touch` | Not recognized | `New-Item -ItemType File` |

### 5.3 Laravel Storage Permissions (Windows)
```powershell
# Grant full control to storage and cache directories
icacls "storage" /grant:r "Users:(OI)(CI)F" /T
icacls "bootstrap\cache" /grant:r "Users:(OI)(CI)F" /T
```

### 5.4 Laravel Storage Permissions (Linux/Docker)
```bash
# Standard Laravel permissions
chown -R www-data:www-data storage bootstrap/cache
chmod -R 775 storage bootstrap/cache
```

### 5.5 Node.js / npm Permissions
- **Global packages:** Avoid `sudo npm install -g`, use `nvm` or set npm prefix
- **node_modules:** Must be writable by current user
- **Windows fix:** `npm config set prefix "$HOME\\.npm-global"` + add to PATH

### 5.6 Symlink Creation Requirements
| OS | Requirement | Command |
|----|-------------|---------|
| Windows | Admin OR Developer Mode | `mklink /D target source` |
| Windows (no admin) | Enable Developer Mode in Settings | Settings → Update → For Developers |
| Linux | Standard user | `ln -s source target` |

### 5.7 Auto-Fix Commands
```powershell
# Windows: Complete permission reset for Laravel project
icacls . /grant:r "%USERNAME%:(OI)(CI)F" /T
Set-ExecutionPolicy RemoteSigned -Scope CurrentUser -Force
```

```bash
# Linux/Docker: Complete permission reset for Laravel project
chown -R $(whoami):$(whoami) .
find . -type d -exec chmod 755 {} \;
find . -type f -exec chmod 644 {} \;
chmod -R 775 storage bootstrap/cache
```

## 6. R-SERV: RUNTIME SERVICE ASSURANCE (Auto-Start)
- **Concept:** If environment is local and critical development services are missing, auto-start them.
- **Trigger:** `APP_ENV=local`.
- **Services:**
    - **Vite:** Check port 5173. If closed -> `cmd /c "start /B npm run dev"` (Windows) or `npm run dev &` (Linux).
    - **Queue Worker:** Check if `php artisan queue:work` is running (optional, but good practice).

## 7. W-ROOT: WEB ROOT ALIGNMENT (Laragon/Apache/Nginx)
- **Symptom:** Browser shows "Index of /" or directory listing instead of the app.
- **Cause:** Virtual Host Document Root points to project root `C:/laragon/www/project` instead of `.../project/public`.
- **Validation:** Access the site URL. If it lists files, the Root is incorrect.
- **Fix (Laragon - Recommended):**
    1. Open Laragon Menu -> Apache/Nginx -> sites-enabled -> `licitaciones.test.conf`.
    2. Change `root "C:/laragon/www/licitaciones";` to `root "C:/laragon/www/licitaciones/public";`.
    3. Reload Laragon.
- **Fix (Workaround - .htaccess):**
    - Create `.htaccess` in project root to redirect all traffic to `public/`.
    - *Note:* Only works for Apache/OpenLiteSpeed.

## 8. EXECUTION GUIDELINES
- Always verify connectivity BEFORE attempting database migrations or builds.
- If a symlink error occurs, check the OS and use the designated `symlink_method`.
- In case of HMR failure, check if the application is running inside Docker and ensure port 5173 is exposed and correctly mapped.
- When working from symlinked directories, verify Vite `preserveSymlinks` is enabled.
- Before running npm commands in PowerShell, verify execution policy or use CMD wrapper.
- If permission denied errors occur, run the appropriate auto-fix command for the OS.
- **Web Root Check:** If the user sees a directory listing, Guide them to update the Virtual Host config.

