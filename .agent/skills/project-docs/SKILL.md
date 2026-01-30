---
name: Project Documentation
description: Autonomous documentation generator that monitors code changes, organizes context, and generates standardized documentation in public/docs.
---

# ROLE: Project Documentation Engineer
# OBJECTIVE: Maintain synchronized, standardized project documentation that auto-updates with code changes and keeps the repo clean.

## 1. TRIGGER CONDITIONS (AUTO-EXECUTE)
This skill MUST be invoked automatically when:
- Code files in `app/`, `resources/`, `routes/`, or `database/` are modified (Auto-Monitor).
- User explicitly requests documentation update (`docs:generate`, `docs:update`).
- "Usa tus skills" or "Use your skills" command is issued (part of the full workflow).
- User adds content to `.agent/docs/` or `.agent/context/`.

## 2. DATA SOURCES & ORGANIZATION (READ & CLEAN)

### 2.1 Code Organization (FIRST STEP)
Before generating documentation:
- **Scan Root:** Identify loose `.md`, `.txt`, or context files in the project root.
- **Relocate:** Move relevant context/documentation files to `.agent/docs/` or `.agent/context/`.
- **Cleanup:** Suggest deletion of obsolete tmp files.

### 2.2 Input Directories (STRICT SOURCE OF TRUTH)
| Source | Path | Purpose |
|--------|------|---------|
| **Primary Codebase** | `app/`, `config/`, `routes/` | **SOURCE OF TRUTH**. Real logic, entities, and access control. |
| **Supporting Docs** | `.agent/docs/` | Diagrams, Technical Reports, non-code context. |
| **Supporting Metadata** | `.agent/context/` | Project Name, Description, Kanban, Rules (Parse MD files). |

> **CRITICAL RULE**: The `public/docs/data/*.json` files MUST be populated primarily using information extracted **from the CODEBASE**. Files in `.agent/docs` are supplementary.

### 2.3 Multi-Source Entity Discovery (CORE LOGIC)
> [!IMPORTANT]
> **Adopting Data Modeler Standard**: We utilize the "Multi-Source Entity Discovery" logic to ensure no entity is left undocumented.

**Discovery Priority (High to Low)**:
1.  **Laravel Models** (`app/Models/*.php`): The absolute truth for business entities.
2.  **Laravel Migrations** (`database/migrations/`): The truth for data structure.
3.  **Modules Config** (`config/modulos.php`): The truth for UI modules.
4.  **Routes & Policies** (`routes/web.php`, `app/Policies/`): The truth for Use Cases and Access.

**Merge Strategy**:
- Collect all entities from the 4 sources.
- Deduplicate (normalize names).
- If an entity exists in Code but not in Docs -> **Auto-Generate Documentation Placeholder**.
- If an entity exists in Docs but not in Code -> **Mark as Deprecated/Legacy**.

## 3. OUTPUT TARGETS (WRITE)

### 3.1 Public Directory (Web Accessible)
All documentation output MUST be generated in **`/public/docs/`**:

```
public/docs/
├── index.html              # Main viewer (Single Page App)
├── assets/
│   ├── style.css           # Standardized Styles
│   ├── app.js              # Application Logic
│   └── diagram-renderer.js # Canvas renderer
└── data/
    ├── project.json        # Project metadata (Auto-generated)
    ├── modules.json        # All modules with status (Auto-generated from Models)
    ├── rules.json          # Business rules (Auto-extracted)
    ├── usecases.json       # Use cases (Auto-extracted)
    ├── kanban.json         # Status tracking (Auto-extracted)
    ├── diagrams.json       # Diagram manifest (Auto-generated)
    └── skills.json         # Agent skills inventory (Auto-generated from .agent/skills/)
```

### 3.2 Access URL
`http://[domain]/docs/index.html`

## 4. AUTONOMOUS GENERATION LOGIC

### 4.0 PRE-GENERATION ENTITY AUDIT (MANDATORY)

> [!CAUTION]
> **Before generating ANY documentation**, verify ALL entities are captured. Missing entities = incomplete documentation.

#### Purpose
Detect entities (models, tables) that exist in code but are NOT documented, preventing gaps like COMPROMISOS being absent.

#### Audit Sources

| Source | Location | Check For |
|--------|----------|-----------|
| **Models** | `app/Models/*.php` | Laravel model classes |
| **Migrations** | `database/migrations/*create*table*.php` | Table creation scripts |
| **Model Data** | `public/docs/data/diagrams/model-data.json` | Documented tables |
| **Modules Config** | `config/modulos.php` | Menu modules |

#### Audit Logic

```php
// 1. Collect all models
$models = collect(glob(app_path('Models/*.php')))
    ->map(fn($f) => basename($f, '.php'))
    ->filter(fn($m) => $m !== 'Model'); // Exclude base class

// 2. Get documented tables
$modelData = json_decode(file_get_contents('public/docs/data/diagrams/model-data.json'));
$documentedTables = collect($modelData->tables ?? [])->pluck('name');

// 3. Convert model names to table names
$expectedTables = $models->map(fn($m) => Str::snake(Str::plural($m)));

// 4. Find missing
$missing = $expectedTables->diff($documentedTables);

// 5. Report and auto-fix
if ($missing->isNotEmpty()) {
    Log::warning('⚠️ UNDOCUMENTED ENTITIES: ' . $missing->implode(', '));
    // Trigger model-data regeneration
}
```

#### Validation Checklist

- [ ] All `app/Models/*.php` have corresponding entries in `model-data.json`
- [ ] All `config/modulos.php` modules have corresponding model documentation
- [ ] No 404 errors when rendering Modelo de Datos section

#### Auto-Fix Actions

| Issue | Action |
|-------|--------|
| Model exists, table missing in `model-data.json` | Regenerate `model-data.json` via data-modeler |
| Table in migrations but not in `schema_base.sql` | Add to SQL file |
| Module in menu but no documentation | Generate placeholder module entry |

---

### 4.1 Data Extraction Methods (REVERSE ENGINEERING)

| Method | Source (Code based) | Output |
|--------|---------------------|--------|
| `updateModulesFromCode()` | `app/Models/*`, `app/Http/Controllers/*`, `config/modulos.php` | `modules.json` |
| `extractBusinessRules()` | `app/Http/Requests/*.php`, `app/Models/*.php` (`$rules`) | `rules.json` |
| `extractUseCases()` | `routes/web.php` (Middleware groups), `app/Policies/*.php` | `usecases.json` |
| `loadModelData()` | `public/docs/data/diagrams/model-data.json` (from Data Modeler) | Model Section |
| `updateProjectMetadata()` | `.agent/context/*.md` (Supporting text) | `project.json` |

### 4.1.0 Model Data Integration (CRITICAL)
The documentation viewer MUST always display the **Modelo de Datos** section.
- **Source**: `public/docs/data/diagrams/model-data.json` (This file is generated by the **Data Modeler** skill).
- **Fallback**: If file is missing, Project Docs must trigger `data-modeler` in "Scan Mode" to generate it.

### 4.1.1 Skills Inventory Generation
(Kept as is: Extracts from `.agent/skills/*/SKILL.md`)
The `extractSkillsInventory()` method generates a comprehensive report of all agent skills:

**Source**: `.agent/skills/*/SKILL.md`

**Output Schema** (`skills.json`):
```json
{
  "generated_at": "ISO-8601 timestamp",
  "total_skills": 7,
  "skills": [
    {
      "id": "skill-folder-name",
      "name": "Human Readable Name",
      "description": "Short description from YAML frontmatter",
      "path": ".agent/skills/skill-folder/SKILL.md",
      "role": "Extracted from # ROLE comment",
      "objective": "Extracted from # OBJECTIVE comment",
      "trigger_conditions": ["Array of trigger conditions"],
      "main_actions": [
        {
          "section": "2.1 Action Name",
          "items": ["Action item 1", "Action item 2"]
        }
      ],
      "priority": "HIGH|NORMAL|LOW",
      "execution_order": "Position in skill chain (if defined)",
      "dependencies": ["Other skills this depends on"],
      "last_modified": "File modification timestamp"
    }
  ],
  "execution_chain": {
    "order": ["maintenance", "naming-integrity", "env-assurance", "sql-to-laravel", "project-docs"],
    "notes": "Execution order based on skill dependencies"
  }
}
```

**Parsing Rules**:
1. **YAML Frontmatter**: Extract `name` and `description` from `---` blocks.
2. **Role/Objective**: Parse `# ROLE:` and `# OBJECTIVE:` comments.
3. **Trigger Conditions**: Extract items under `## 1. TRIGGER CONDITIONS` or similar.
4. **Main Actions**: Parse all `### X.X` headings under `## 2. ACTIONS` or similar.
5. **Priority**: Infer from keywords like "HIGHEST PRIORITY", "CRITICAL", "ALWAYS ACTIVE".
6. **Execution Order**: Parse `## EXECUTION CHAIN` or `## EXECUTION PRIORITY` sections.

**Viewer Section**:
The `public/docs/index.html` viewer MUST include a **"Skills & Automation"** section that:
- Displays a card grid of all skills
- Shows skill name, description, and trigger conditions
- Expands to show full action list on click
- Color-codes by priority (red=high, yellow=normal, green=low)
- Shows execution chain diagram using Mermaid

### 4.2 On Code Change
1.  **Detect**: Identify changes in `app/Models`, `app/Http/Controllers`.
2.  **Execute**: Run `php artisan docs:generate`.
3.  **Update**: Refresh ALL JSON files from sources.
4.  **Notify**: If significant changes (new modules), notify the user.

### 4.1.2 Reverse Engineering Logic (Code -> Diagrams)
To ensure diagrams reflect the *actual* implemented logic, use this reverse engineering strategy on BOTH Livewire components and Standard Controllers:

**1. Scan Targets**: `app/Livewire/**/*.php` AND `app/Http/Controllers/**/*.php`

**2. Role Inference (Namespace Based)**:
- `Admin\*` -> Role: `admin` (Color: Red)
- `Contratista\*` -> Role: `contratista` (Color: Green)
- `Principal\*` -> Role: `principal` (Color: Blue)
- `Public\*` or no prefix -> Role: `guest` (Color: Grey)

**3. Logic Flow Detection**:
- **Livewire**:
    - `mount()` -> Start Node
    - `render()` -> View Node
    - `save()`, `submit()` -> Process Node
    - `validate()` -> Decision Node
- **Controllers**:
    - `index()` -> View Node (List)
    - `store()` -> Process Node (Create)
    - `update()` -> Process Node (Edit)
    - `destroy()` -> Hazard Node (Delete)
    - `FormRequest` usage -> Decision Node (Validation)

**4. Module Linkage**:
- Map Controller `ModelNameController` -> Module `ModelName`.
- If `ModelName` doesn't exist in `modules.json`, create it dynamically.

**5. Output**: Generate `public/docs/data/diagrams/flow_{module_name}.xml`.

### 4.1.3 Consistency Assurance (Module-Diagram-UseCase)
> **RULE**: Every identified module MUST have at least one Diagram and one Use Case per Role.

1.  **Backfill Logic**:
    - If `Module X` exists but `flow_X.xml` is missing -> Generate Basic CRUD Flow.
    - If `Module X` exists but `usecases.json` has no entry -> Generate "Gestionar X" for inferred role.
2.  **Role-Based Use Case Extraction**:
    - Parse `routes/web.php`:
        - Group routes by `middleware` (e.g., `auth:admin` -> Role: Admin).
        - Map URL `/admin/users` -> Controller `UserController` -> Module `Users`.
        - Create Use Case: "Administrar Users" (Actor: Admin).
    - Parse `app/Policies/*.php`:
        - `viewAny` -> Use Case "Ver Listado".
        - `create` -> Use Case "Crear Registro".

### 4.1.4 Business Rules Extraction Strategy
Convert Laravel FormRequest validations into human-readable business rules.

**Source**: `app/Http/Requests/*.php`

**Mapping Logic**:
- `required` -> "El campo [field] es obligatorio."
- `unique:table,col` -> "El valor de [field] debe ser único en el sistema."
- `exists:table,col` -> "El [field] seleccionado debe existir en el registro de [table]."
- `date|after:tomorrow` -> "La fecha debe ser posterior al día actual."
- `min:X` -> "Debe tener al menos X caracteres/cantidad."

**Output Format** (`rules.json`):
```json
{
  "module": "Usuarios",
  "source": "StoreUserRequest.php",
  "rules": [
    "El rut es obligatorio y debe ser único.",
    "El email debe ser válido."
  ]
}
```

### 4.3 Integration with 'Use Skills'
- When triggered as part of the full chain, this skill runs LAST to capture the final state of the project.

### 4.4 Verification & Filling (FAIL-SAFE)
After extracting data, the system MUST verify that ALL core files exist. If any file is missing or empty, generate a valid placeholder:

1.  **Iterate** list of required files: `project.json`, `modules.json`, `diagrams.json`, `skills.json`.
2.  **Check Existence**: If missing -> Generate Placeholder (See Section 6.2).
3.  **Check Diagrams**: For every entry in `diagrams.json`, ensure the referenced XML/SVG exists. If not -> Generate Placeholder Diagram.

## 5. TEMPLATE RULES (CRITICAL)

> **MANDATORY**: All files in `.agent/templates/docs/data/` MUST contain **empty schemas only**.
> Example: `{"modules": []}`, `{"rules": []}`, `{"usecases": []}`.
> Data is populated **exclusively** during generation from `.agent/docs` and `.agent/context`.

- **Template Files**: Generic HTML/JS/CSS with NO hardcoded project names.
- **JSON Schemas**: Empty arrays/objects only. Never commit example data.

## 6. ROBUSTNESS & VALIDATION RULES
- **JSON Validation**: Ensure valid JSON in `public/docs/data/*.json`.
- **Viewer Integrity**: 
    - Ensure `public/docs/` mirrors `.agent/templates/docs/`.
    - Recursively COPY content from `.agent/templates/docs/` to `public/docs/`.
    - **Dynamic Content**: NO hardcoded project names or modules in templates.
    - **Diagrams**: Scan `.agent/docs/*.xml`, copy to `public/docs/data/diagrams/`, and generate `diagrams.json`.
    - **Diagram Formats Supported**:
        - Custom XML format (`<diagram type="flowchart|lifecycle|usecase">`)
        - draw.io/mxGraph format (`<mxfile>`) - auto-detected and scaled
    - **Roles in Diagrams (CRITICAL)**:
        - ALL flowchart and lifecycle diagrams MUST include `role` attributes on nodes
        - The renderer displays a legend showing which role/actor executes each step
        - Node colors are determined by the role's assigned color
        - See **Section 6.1** for detailed XML schema with roles
    - **Auto-Generated Diagrams**:
        - **ER Diagram**: Parses `database/migrations/*.php` → outputs `er_diagram.svg` with UML notation
        - **Module Flow Diagrams**: For each Model → outputs `flow_{model}.xml` with CRUD operations
    - **Record Lifecycle Diagram**:
        - Use the following Mermaid template for `Registro` lifecycle:
        ```mermaid
        stateDiagram-v2
            direction LR
            [*] --> Pendiente: Creado por Contratista
            
            Pendiente --> Enviado: Contratista (Envía a revisión)
            note right of Pendiente: Edición habilitada
            
            Enviado --> Auditando: Auditor/Admin (Inicia auditoría)
            note right of Enviado: Solo lectura
            
            Auditando --> Auditado: Auditor/Admin (Finaliza auditoría)
            note right of Auditado: Resultado final provisional
            
            state Auditado {
                [*] --> Evaluado
                Evaluado --> SolicitudReapertura: Contratista (Solicita corregir)
                SolicitudReapertura --> Reabierto: Admin/Auditor (Aprueba solicitud)
                SolicitudReapertura --> Evaluado: Admin/Auditor (Rechaza solicitud)
            }
            
            Reabierto --> Subsanado: Contratista (Corrige y guarda)
            note right of Reabierto: Edición habilitada (restringida)
            
            Subsanado --> Auditando: Auditor/Admin (Revisa correcciones)
            
            Auditado --> Cerrado: Sistema/Admin (Cierre definitivo)
            note right of Cerrado: Inmutable
        ```
- **Code Integrity**:
    - When refactoring `app.js`, ALWAYS preserve critical UI methods (`setupNavigation`, `setupFilters`, `setupDiagramButtons`).
    - Verify that `init()` calls all setup methods.
- **Regression Testing**:
    - After any template change, verify `public/docs/index.html` loads without console errors.
- **Atomic Operations**: Use temporary buffers when writing large JSON files to avoid corrupt reads.

### 6.1 Diagram Role Schema (CRITICAL)
All flowchart and lifecycle diagrams MUST include role definitions and role assignments per node.

**XML Schema for Roles**:
```xml
<diagram type="flowchart" id="flow-example" title="Example Flow">
  <metadata>
    <description>Example process flow with role assignments</description>
    <module>Example Module</module>
  </metadata>
  
  <!-- REQUIRED: Define all roles that participate in this flow -->
  <roles>
    <role id="contratista" name="Contratista" color="#22c55e"/>
    <role id="admin_contrato" name="Admin Contrato" color="#a855f7"/>
    <role id="admin" name="Administrador" color="#ef4444"/>
    <role id="sistema" name="Sistema" color="#3b82f6"/>
  </roles>
  
  <!-- Each node MUST have a role attribute -->
  <nodes>
    <node id="start" type="start" x="300" y="30" label="Inicio" role="sistema"/>
    <node id="step1" type="process" x="300" y="100" label="User action" role="contratista"/>
    <node id="decision1" type="decision" x="300" y="180" label="¿Approve?" role="admin_contrato"/>
    <node id="end" type="end" x="300" y="260" label="Fin" role="sistema"/>
  </nodes>
  
  <connections>
    <connect from="start" to="step1"/>
    <connect from="step1" to="decision1"/>
    <connect from="decision1" to="end" label="Sí"/>
  </connections>
</diagram>
```

**Lifecycle Diagram with Actors**:
```xml
<transitions>
  <!-- Use 'actor' attribute to specify who triggers the transition -->
  <transition from="pending" to="auditing" label="Iniciar Auditoría" actor="admin_contrato"/>
  <transition from="auditing" to="closed" label="Auto-close" actor="sistema"/>
</transitions>
```

**Role Colors (Standard Palette)**:
| Role ID | Name | Color | Usage |
|---------|------|-------|-------|
| `sistema` | Sistema | `#3b82f6` (blue) | Automated processes, validations |
| `contratista` | Contratista | `#22c55e` (green) | Contractor user actions |
| `admin_contrato` | Admin Contrato | `#a855f7` (purple) | Contract administrator actions |
| `admin` | Administrador | `#ef4444` (red) | System administrator actions |

**Renderer Behavior**:
1. **Legend**: Auto-generated legend showing all roles with color indicators
2. **Node Coloring**: Process and decision nodes are colored based on their `role`
3. **Role Indicator**: Small colored dot in corner of each node matching the role
4. **Transitions**: Lifecycle transitions show `actor` label on the arrow


Refer to standard `docs:generate` logic implemented in Laravel.

### 6.2 Fail-Safe Generation Strategy (MANDATORY)
> **RULE**: All core data files must exist. If extraction fails, create a valid placeholder to prevent 404 errors.

**Viewer Integrity & Cache Busting (CRITICAL)**:
1. **App.js Force Generation**: The command MUST verify `public/docs/assets/app.js` exists.
   - If missing or corrupt, it MUST write a robust version that implements **Cache Busting** (`data.json?v=TIMESTAMP`).
   - The viewer MUST NOT rely on external hosting or CDNs that might be blocked or cached aggressively.
2. **Index.html Force Generation**: Ensure the entry point exists and correctly links to `assets/style.css` and `assets/app.js`.

**Placeholder Content Definitions**:

1.  **project.json**:
    ```json
    {"name": "Project Documentation", "version": "1.0.0", "description": "Auto-generated documentation.", "lastUpdated": "NOW", "repo": "local"}
    ```

2.  **modules.json**:
    ```json
    {"modules": [{"id": "system", "name": "System Core", "description": "Core system module.", "status": "active", "features": [], "progress": 100}]}
    ```

3.  **diagrams.json**:
    ```json
    {"er": [], "flow": [], "usecase": []}
    ```

4.  **changelog.json**:
    ```json
    {"entries": []}
    ```

5.  **pending.json**:
    ```json
    {"items": []}
    ```

6.  **kanban.json**:
    ```json
    {"columns": []}
    ```

7.  **Flow Diagram Placeholder (XML)**:
    ```xml
    <diagram type="flowchart" id="placeholder" title="No Data"><metadata><module>System</module></metadata><roles><role id="system" name="System" color="#gray"/></roles><nodes><node id="start" type="start" label="No Logic Found" role="system"/></nodes><connections/></diagram>
    ```

**Logic**:
- **NEVER** leave `public/docs/data/` empty.
- **ALWAYS** check for file existence before finishing execution.
- **ALWAYS** set `lastUpdated` timestamp even in placeholders.
- **CONTENT INTEGRITY (CRITICAL)**:
    - Do **NOT** overwrite an existing diagram with a placeholder if the existing file has > 200 bytes or valid XML structure.
    - Only use Placeholder if:
        1. File does not exist AND extraction failed.
        2. Extracted file is empty/corrupt (0 bytes).
        3. Extracted logic has < 3 nodes (Quality Gate failed).

### 7.1 Skills & Automation Section (NEW)
The documentation viewer MUST include a dedicated **"Skills & Automation"** section:

**Navigation Item**: Add to sidebar/nav:
```html
<a href="#skills" data-section="skills">🤖 Skills & Automation</a>
```

**Section Content**:
1. **Overview Card**: Total skills count, last update timestamp
2. **Skill Cards Grid**: One card per skill with:
   - Skill name (from YAML `name`)
   - Short description (from YAML `description`)
   - Priority badge (color-coded)
   - Trigger conditions (collapsed list)
   - "View Details" expander
3. **Execution Chain Diagram**:
   ```mermaid
   flowchart LR
       subgraph "Skill Execution Chain"
           A[🧹 Maintenance] --> B[📛 Naming Integrity]
           B --> C[🔌 Env Assurance]
           C --> D[🗄️ SQL to Laravel]
           D --> E[🔐 Privilegios Engine]
           E --> F[👁️ View Assurance]
           F --> G[📚 Project Docs]
       end
       
       style A fill:#4CAF50,color:#fff
       style B fill:#f44336,color:#fff
       style C fill:#2196F3,color:#fff
       style D fill:#FF9800,color:#fff
       style E fill:#9C27B0,color:#fff
       style F fill:#00BCD4,color:#fff
       style G fill:#795548,color:#fff
   ```

4. **Skill Details Modal**: On card click, show:
   - Full role and objective
   - Complete actions list
   - Dependencies
   - Source file path (clickable link)

### 7.2 Skills JSON Template
Ensure `.agent/templates/docs/data/skills.json` contains:
```json
{
  "generated_at": "",
  "total_skills": 0,
  "skills": [],
  "execution_chain": {
    "order": [],
    "notes": ""
  }
}
```

## 8. DYNAMIC MENU & SECTION GENERATION (CRITICAL)

> **RULE**: The documentation viewer (`public/docs/index.html`) MUST have a **fully dynamic menu** that auto-generates navigation items based on available JSON files in `public/docs/data/`.

### 8.1 Autonomous Menu Generation
The `app.js` file MUST implement a `buildDynamicMenu()` method that:

1. **Scans Data Files**: Attempts to load all possible JSON files:
   - `project.json` → Resumen Gerencial (executive)
   - `kanban.json` → Tablero Kanban
   - `modules.json` → Módulos
   - `rules.json` → Reglas de Negocio
   - `skills.json` → Skills & Automation
   - `pending.json` → Decisiones Pendientes
   - `changelog.json` → Changelog
   - `diagrams/` folder → Diagramas de Flujo, Casos de Uso

2. **Conditional Rendering**: Only creates nav items for sections with data:
   ```javascript
   // If data exists, add to menu
   if (this.data.skills) {
       availableSections.push({ key: 'skills', id: 'skills', icon: '🤖', label: 'Skills & Automation' });
   }
   ```

3. **Ordered Display**: Sections appear in a defined order (1-9).

4. **Dynamic Section Creation**: If a section HTML doesn't exist but data is available, the viewer MUST create the section dynamically using `buildDynamicSections()`.

### 8.2 Section Configuration Map
The `app.js` MUST contain a `sectionConfig` object:
```javascript
this.sectionConfig = {
    'project': { id: 'executive', icon: '📈', label: 'Resumen Gerencial', order: 1 },
    'kanban': { id: 'kanban', icon: '📋', label: 'Tablero Kanban', order: 2 },
    'modules': { id: 'modules', icon: '📦', label: 'Módulos', order: 3 },
    'diagrams': { id: 'diagrams', icon: '🔀', label: 'Diagramas de Flujo', order: 4, isFolder: true },
    'usecases': { id: 'usecases', icon: '👤', label: 'Casos de Uso', order: 5 },
    'rules': { id: 'rules', icon: '⚖️', label: 'Reglas de Negocio', order: 6 },
    'skills': { id: 'skills', icon: '🤖', label: 'Skills & Automation', order: 7 },
    'pending': { id: 'pending', icon: '⏳', label: 'Pendientes', order: 8 },
    'changelog': { id: 'changelog', icon: '📜', label: 'Changelog', order: 9 }
};
```

### 8.3 Adding New Sections (Extensibility)
When a new JSON file is added to `public/docs/data/`:
1. Add entry to `sectionConfig` with unique `id`, `icon`, `label`, and `order`.
2. Implement a `render{SectionName}()` method in `app.js`.
3. Add corresponding CSS styles in `style.css`.
4. The menu will auto-include the new section on next page load.

### 8.4 Validation Rules for Dynamic Menu
- **No Hardcoded Nav Items**: The `<nav class="nav-menu">` element in HTML MUST be empty or minimal; JS populates it.
- **Graceful Degradation**: If a JSON file fails to load, the section is simply omitted from the menu.
- **Console Logging**: Log the number of sections generated for debugging:
  ```javascript
  console.log(`📋 Menú dinámico generado con ${availableSections.length} secciones`);
  ```

### 8.5 CSS Requirements for New Sections
When adding new data sections, ensure `style.css` includes:
- A grid/card layout for the section content
- Priority badges (`.priority-badge.high`, `.priority-badge.normal`, `.priority-badge.low`)
- Responsive breakpoints for mobile
- Consistent spacing with existing sections

## 9. CSS ASSURANCE (CRITICAL)

> [!CAUTION]
> **EVERY execution of this skill MUST verify CSS integrity.** A broken CSS reference = unusable documentation viewer.

### 9.1 CSS Structure Requirements

The documentation viewer uses this **MANDATORY** file structure:

```
public/docs/
├── index.html          # References assets/style.css
├── assets/
│   ├── style.css       # ⚠️ CRITICAL - Main styles
│   ├── app.js          # Application logic
│   └── diagram-renderer.js
└── data/
    └── *.json
```

### 9.2 CSS Reference Validation (AUTO-CHECK)

On **EVERY** skill execution, verify:

1. **File Existence Check**:
   ```
   ✓ public/docs/assets/style.css exists
   ✓ public/docs/index.html exists
   ✓ public/docs/assets/app.js exists
   ```

2. **Reference Integrity Check**:
   The `index.html` MUST contain this exact reference:
   ```html
   <link rel="stylesheet" href="assets/style.css">
   ```
   
   **NOT** these incorrect patterns:
   ```html
   <!-- ❌ WRONG -->
   <link rel="stylesheet" href="style.css">
   <link rel="stylesheet" href="/docs/assets/style.css">
   <link rel="stylesheet" href="../assets/style.css">
   ```

3. **JS Reference Check**:
   ```html
   <script src="assets/diagram-renderer.js"></script>
   <script src="assets/app.js"></script>
   ```

### 9.3 Auto-Fix Rules

If CSS issues are detected during skill execution:

| Issue | Auto-Fix Action |
|-------|-----------------|
| `assets/style.css` missing | Copy from `.agent/templates/docs/assets/style.css` |
| `style.css` in wrong location (root) | Move to `assets/` subdirectory |
| Wrong `href` in `index.html` | Update to `assets/style.css` |
| Duplicate CSS files | Keep only `assets/style.css`, delete others |

### 9.4 Template Sync Protocol

When syncing from templates to public:

```powershell
# CORRECT: Preserve directory structure
Copy-Item -Path ".agent/templates/docs/*" -Destination "public/docs/" -Recurse -Force

# Verify structure after copy
Test-Path "public/docs/assets/style.css"  # MUST be True
Test-Path "public/docs/index.html"        # MUST be True
```

### 9.5 CSS Validation Checklist

Before completing Project Docs skill execution:

- [ ] `public/docs/assets/style.css` exists and is readable
- [ ] `public/docs/index.html` contains `href="assets/style.css"`
- [ ] No duplicate `style.css` files in `public/docs/` root
- [ ] All CSS classes referenced in JS exist in CSS file
- [ ] Console shows no 404 errors for CSS/JS resources

### 9.6 CSS Content Integrity

The `style.css` file MUST include these critical selectors:

```css
/* Core Layout */
.app-container { ... }
.sidebar { ... }
.main-content { ... }
.section { ... }

/* Navigation */
.nav-menu { ... }
.nav-item { ... }
.nav-item.active { ... }

/* Cards & Grids */
.stat-card { ... }
.modules-grid { ... }
.skill-card { ... }

/* Dynamic Sections */
.skills-grid { ... }
.priority-badge { ... }
.priority-badge.high { ... }
.priority-badge.normal { ... }
.priority-badge.low { ... }
```

### 9.7 Fallback Mechanism

If CSS cannot be loaded (404), the viewer should:
1. Display a warning banner at the top
2. Use browser default styles gracefully
3. Log the error to console with path attempted

```javascript
// app.js fallback check
document.addEventListener('DOMContentLoaded', () => {
    const cssLink = document.querySelector('link[href*="style.css"]');
    if (!cssLink || !document.styleSheets.length) {
        console.error('⚠️ CSS not loaded. Check path: assets/style.css');
    }
});
```

> [!IMPORTANT]
> **NEVER** generate or update documentation without verifying CSS paths are correct. A visually broken viewer is worse than no viewer.

## 10. DATA CACHE BUSTING (CRITICAL)

> [!CAUTION]
> **ALL data files MUST be loaded with a cache-busting parameter** to ensure the browser always fetches fresh content, especially after documentation updates.

### 10.1 How It Works

The `app.js` generates a unique timestamp when the DocsApp is initialized:

```javascript
// At app initialization
this.cacheVersion = new Date().toISOString().replace(/[:.]/g, '-');
// Example: "2026-01-28T11-45-26-123Z"
```

### 10.2 URL Generation

All data file requests use the `dataUrl()` method:

```javascript
dataUrl(path) {
    return `${path}?v=${this.cacheVersion}`;
}

// Usage:
fetch(this.dataUrl('data/project.json'))
// Results in: data/project.json?v=2026-01-28T11-45-26-123Z
```

### 10.3 Files That MUST Use Cache Busting

| File Type | Location | Method |
|-----------|----------|--------|
| **JSON Data** | `data/*.json` | `this.dataUrl()` in `loadData()` |
| **ER Diagrams** | `data/diagrams/*.svg` | `this.dataUrl()` in `renderERDiagram()` |
| **Flow Diagrams** | `data/diagrams/*.xml` | `this.dataUrl()` in `renderDiagrams()` |
| **Use Case Diagrams** | `data/diagrams/*.xml` | `this.dataUrl()` in `renderDiagrams()` |

### 10.4 Console Logging

On load, the console should show:
```
📄 DocsApp initialized with cache version: 2026-01-28T11-45-26-123Z
✅ Data loaded with cache version: 2026-01-28T11-45-26-123Z
```

### 10.5 Validation Checklist

Before completing Project Docs execution:

- [ ] `app.js` constructor generates `this.cacheVersion`
- [ ] `loadData()` uses `this.dataUrl()` for all 8 JSON files
- [ ] `renderERDiagram()` uses `this.dataUrl()` for SVG path
- [ ] `renderDiagrams()` uses `this.dataUrl()` for XML paths
- [ ] Console shows cache version on page load

### 10.6 Why This Matters

Without cache busting:
- ❌ Browser may serve stale JSON from cache
- ❌ Documentation updates won't reflect immediately
- ❌ Users see outdated project information

With cache busting:
- ✅ Every page load fetches fresh data
- ✅ Documentation updates are instant
- ✅ No manual cache clearing needed

> [!TIP]
> The timestamp format uses ISO-8601 with colons and dots replaced by hyphens to ensure URL safety.

## 11. PERMISSION ASSURANCE (AUTO-FIX)

> [!IMPORTANT]
> **ACCESS DENIED** errors are the most common cause of generation failure. The skill MUST proactively manage filesystem permissions.

### 11.1 Target Directories
The generator must ensure:
1.  **Read Access**: `.agent/templates` (Source of Truth)
2.  **Write Access**: `public/docs` (Output Target)

### 11.2 Auto-Fix Logic (Cross-Platform)

**On Windows (Powershell/CMD)**:
- Use `icacls` to grant explicit permissions to the current User group.
- Command: `icacls "PATH" /grant:r "Users:(OI)(CI)F" /T`
- Scope: Recursive (`/T`) for inheritance (`(OI)(CI)`).

**On Linux/Unix**:
- Use `chmod` to set standard web permissions.
- Command: `chmod -R 755 PATH`

### 11.3 Integration Point
The `ensurePermissions()` check MUST run **before** `ensureDirectoryStructure()` to prevent "mkdir" failures.

### 11.4 Validation
- [ ] `is_readable(.agent/templates)` returns TRUE
- [ ] `is_writable(public/docs)` returns TRUE (or parent `public` is writable)

