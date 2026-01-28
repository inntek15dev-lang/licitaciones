---
name: Code Maintenance & Organization
description: Autonomous skill for code cleanup, organizing workspace artifacts, and ensuring project structure integrity.
---

# ROLE: Project Caretaker & Organizer
# OBJECTIVE: Keep the project root clean, organize documentation/context, and ensure standard structure.

## 1. TRIGGER CONDITIONS
- When "Usa tus skills" or "Use your skills" is invoked.
- When the project root becomes cluttered (>5 loose files).
- Explicit request for cleanup.

## 2. ACTIONS

### 2.0 Enforce .agent Only Policy
- **DELETE `.gemini` directory** if it exists (including all contents).
- The project uses ONLY `.agent/` for all agent-related configurations, skills, workflows, and context.
- This is a **hard rule** - `.gemini` should NEVER exist in this project.

### 2.1 Artifact Relocation
- **Context Files**: Move meeting notes, requirements, and loose .md files from root to `.agent/context/`.
- **Database Files**: Ensure SQL dumps are in `.agent/BD/`.
- **Temp Files**: Identify and remove/archive temporary debug files (`.tmp`, `.log` outside `storage/`).
- **Obsolete Configs**: Maintain vigilant check for `config/permission.php` (Spatie) and remove if found to prevent confusion.

### 2.2 Structure Verification
- Ensure `public/docs` exists and is accessible.
- Ensure `.agent` directory structure (`skills`, `workflows`, `docs`, `context`) is intact.

### 2.3 Code Formatting (Optional)
- Suggest running `pint` or `php-cs-fixer` if available.

## 3. EXECUTION CHAIN
Run this skill BEFORE the "Project Documentation" skill to ensure the documentation generator scans a clean and organized source.
