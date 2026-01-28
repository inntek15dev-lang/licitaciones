---
name: Naming Integrity & Safety
description: Ensures all file names, directory names, conversation titles, and record identifiers follow safe naming conventions to prevent IDE crashes, filesystem errors, and project corruption.
---

# ROLE: Naming Guardian & Integrity Enforcer
# OBJECTIVE: Guarantee that ALL created/renamed files, directories, and records use safe, clean naming conventions.

## 1. TRIGGER CONDITIONS
- **ALWAYS ACTIVE** - This skill applies to EVERY action that creates or renames files/directories.
- When "Usa tus skills" or "Use your skills" is invoked.
- Before any file creation, rename, or save operation.
- When auditing existing files for naming violations.

## 2. FORBIDDEN PATTERNS (NEVER USE)

### 2.1 Characters to NEVER Include in Names
| Character | Reason |
|-----------|--------|
| `<` `>` | Breaks HTML/XML parsing, filesystem errors |
| `"` `'` | Breaks string parsing, shell commands |
| `:` | Invalid in Windows paths |
| `/` `\` | Path separators, causes directory confusion |
| `|` | Pipe character, shell interpretation |
| `?` `*` | Wildcards, unpredictable behavior |
| `&` | Shell command chaining |
| `$` | Variable expansion |
| `#` | Comment interpretation |
| `%` | URL encoding issues |
| `` ` `` | Command substitution |
| `@` | Email parsing confusion |
| `!` | History expansion in shells |
| `=` | Assignment confusion |
| `+` | URL encoding issues |
| `{` `}` | Brace expansion |
| `[` `]` | Glob patterns |
| `(` `)` | Subshell, grouping |
| `;` | Command separator |
| `,` | List separator |
| Newlines/Tabs | Invisible characters break parsing |

### 2.2 Content to NEVER Use in Names
- ❌ **HTML/XML tags**: `<div>`, `<script>`, `</head>`, etc.
- ❌ **Code snippets**: Any actual code as a name
- ❌ **SQL queries**: SELECT, INSERT, DROP, etc.
- ❌ **File paths**: `/var/www/`, `C:\Users\`, etc.
- ❌ **URLs**: http://, https://, ftp://
- ❌ **Error messages**: Exception texts, stack traces
- ❌ **Unicode special characters**: Emojis, diacritics in filenames
- ❌ **Invisible characters**: Zero-width spaces, BOM markers

### 2.3 Naming Anti-Patterns
- ❌ **Spaces in filenames** → Use `_` or `-` instead
- ❌ **Leading/trailing spaces**
- ❌ **Multiple consecutive special chars**: `file--name__test`
- ❌ **Names starting with `.` or `-`** (except intentional dotfiles)
- ❌ **Excessively long names** → Max 80 characters
- ❌ **ALL CAPS for regular files**
- ❌ **Mixed casing inconsistently**: `myFile_Name-Test`

## 3. SAFE NAMING CONVENTIONS (ALWAYS USE)

### 3.1 Allowed Characters
```
a-z          → Lowercase letters (preferred)
A-Z          → Uppercase letters (use sparingly)
0-9          → Numbers
_            → Underscore (word separator)
-            → Hyphen (word separator)
.            → Dot (only for extensions)
```

### 3.2 Naming Patterns by Type

| Type | Pattern | Example |
|------|---------|---------|
| **Files** | `snake_case.ext` | `user_service.php` |
| **Directories** | `kebab-case` or `snake_case` | `user-management/` |
| **Laravel Models** | `PascalCase` | `UserProfile.php` |
| **Laravel Controllers** | `PascalCase` + Controller | `UserController.php` |
| **Migrations** | `timestamp_snake_case` | `2024_01_26_create_users.php` |
| **Views** | `kebab-case.blade.php` | `user-profile.blade.php` |
| **Conversation titles** | `Short descriptive phrase` | `setup-auth-module` |
| **Artifacts** | `snake_case.md` | `implementation_plan.md` |

### 3.3 Maximum Lengths
| Element | Max Length |
|---------|------------|
| Filename (without path) | 80 characters |
| Directory name | 40 characters |
| Full path | 200 characters |
| Conversation/session title | 50 characters |

## 4. ENFORCEMENT ACTIONS

### 4.1 Before Creating Any File
1. **Sanitize the name** using these rules:
   - Replace spaces with `_`
   - Remove all forbidden characters
   - Truncate to max length
   - Ensure valid extension

### 4.2 Name Sanitization Function (Pseudocode)
```
function sanitize_name(raw_name):
    # Remove HTML/XML tags
    name = strip_tags(raw_name)
    
    # Remove forbidden characters
    name = regex_replace(name, /[<>:"\/\\|?*&$#%`@!=+{}\[\]();,\n\t]/g, '')
    
    # Replace spaces with underscores
    name = replace(name, ' ', '_')
    
    # Remove multiple consecutive underscores/hyphens
    name = regex_replace(name, /[_-]{2,}/g, '_')
    
    # Remove leading/trailing special chars
    name = trim(name, '_-.')
    
    # Truncate to max length
    name = substring(name, 0, 80)
    
    # Ensure not empty
    if empty(name):
        name = 'unnamed_' + timestamp()
    
    return lowercase(name)
```

### 4.3 Audit Existing Files
When running a full skill check:
1. Scan project root and `.agent/` for naming violations
2. Report files with forbidden characters
3. Suggest safe renames
4. **DO NOT auto-rename without user approval** (could break references)

## 5. CRITICAL REMINDERS

> [!CAUTION]
> **NEVER** use content from conversations, code snippets, or error messages as file/directory names. This WILL break the IDE and cause data loss.

> [!IMPORTANT]
> When the agent creates conversation titles, session names, or any identifier:
> - Use a SHORT, DESCRIPTIVE phrase (3-5 words max)
> - Use only `a-z`, `0-9`, `_`, `-`
> - Example: `setup-user-auth` NOT `<div class="user">Setup authentication module</div>`

> [!WARNING]
> If uncertain about a name, default to `untitled_YYYYMMDD_HHMMSS` format.

## 6. EXECUTION PRIORITY
This skill has **HIGHEST PRIORITY** and must be checked BEFORE:
- Creating any file
- Renaming any file
- Saving any conversation/session
- Generating any artifact
- Creating any database record with string identifiers

## 7. EXAMPLES

### ❌ BAD (Will break IDE)
```
<div>user profile page</div>.html
SELECT * FROM users.sql
My New File (copy 2).txt
config.backup.2024.01.26.json
función crear usuario.php
```

### ✅ GOOD (Safe names)
```
user_profile_page.html
get_all_users.sql
my_new_file_v2.txt
config_backup_20240126.json
crear_usuario.php
```
