# Environment and Connectivity Assurance Skill Integration

Integrate the `LACG-ENV-v2.0` protocol as a new skill to ensure environment integrity and connectivity across Laragon (Windows) and Docker (Linux).

## Proposed Changes

### [Component] Skill Definition

#### [NEW] [SKILL.md](file:///c:/laragon/www/abastible/.agent/skills/env-assurance/SKILL.md)
Create the skill document incorporating the modules:
- **E-V2H (Environment Integrity):** Logic for strict parity and environment mapping.
- **T-CAS (Connectivity Assurance):** Specific health checks for DB and Vite HMR.
- **L-SAPE (Autonomous Adaptation):** Patterns for environment drift and visual failures.

### [Component] Automation Scripts (Optional/Proposed)

#### [NEW] [check_connectivity.php](file:///c:/laragon/www/abastible/.agent/skills/env-assurance/scripts/check_connectivity.php)
A script to validate DB and Redis connections as specified in T-CAS.

## Verification Plan

### Manual Verification
- Verify the skill is accessible via the `skills` system.
- Perform a dry run of the connectivity check logic to ensure it detects the environment correctly (Laragon vs. Docker).
