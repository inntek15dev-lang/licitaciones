---
description: Analyzes an attached image to extract a style and applies it to the project.
---

# STYLE IT Workflow

This workflow is triggered when you want to apply a visual style from an image to the entire project.

## Steps

1.  **Analyze the Image**:
    - Look at the attached image (or the one most recently uploaded).
    - Extract the following design tokens:
        - **Primary Color**: The dominant brand color.
        - **Secondary Color**: Accent or complementary color.
        - **Background Color**: The main canvas color (light/dark).
        - **Surface Color**: Color for cards/containers.
        - **Text Main**: High contrast text color.
        - **Text Muted**: Low contrast text color.
        - **Font Style**: Infer the font family (e.g., 'Inter', 'Roboto', 'Playfair Display').
        - **Border Radius**: Infer if the design is rounded (`0.5rem`), sharp (`0px`), or pill (`9999px`).

2.  **Generate Theme JSON**:
    - Construct a JSON object exactly like this:
      ```json
      {
        "colors": {
          "primary": "#...",
          "secondary": "#...",
          "background": "#...",
          "surface": "#...",
          "text_main": "#...",
          "text_muted": "#..."
        },
        "font": "Inter",
        "radius": "0.5rem"
      }
      ```

3.  **Save Theme File**:
    - Use `write_to_file` to save this JSON to `.agent/temp/theme.json`.

4.  **Execute Skill**:
    - Run the command: `php .agent/skills/stylize-all/scripts/apply_theme.php`

5.  **Build Assets**:
    - Run: `npm run build`

6.  **Verify**:
    - Check the site provided by the user (or `php artisan serve`) to see if colors successfully applied.
