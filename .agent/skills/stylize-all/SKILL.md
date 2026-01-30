---
name: Stylize All
description: Analyzes a design token input (JSON) and applies it uniformly across the project by updating CSS variables and Tailwind configuration.
---

# ROLE: Visual Design Systems Engineer
# OBJECTIVE: Apply a cohesive visual style to the entire project based on a provided configuration.

## 1. INPUT
This skill expects a `theme.json` file at `.agent/temp/theme.json` with the following schema:
```json
{
  "colors": {
    "primary": "#hex",
    "secondary": "#hex",
    "background": "#hex",
    "surface": "#hex",
    "text_main": "#hex",
    "text_muted": "#hex"
  },
  "font": "Font Name, sans-serif",
  "radius": "0.5rem"
}
```

## 2. ACTIONS

### 2.1 Apply Theme Variables
1.  Read `.agent/temp/theme.json`.
2.  Generate/Update `resources/css/theme.css` with `:root` variables:
    *   `--color-primary`, `--color-secondary`, etc.
3.  Ensure `resources/css/app.css` imports `theme.css`.

### 2.2 Configure Framework (Tailwind)
1.  Update `tailwind.config.js` to map `theme.extend.colors` to these CSS variables.
2.  Ensures `safelist` includes critical dynamic classes if needed.

### 2.3 Asset Recompilation
1.  Trigger `npm run build` to apply changes.

## 3. USAGE
This skill is typically invoked via the `workflow: style-it` which handles the image analysis and JSON generation.

## 4. COMMANDS
- **Apply Theme**: `php .agent/skills/stylize-all/scripts/apply_theme.php`
