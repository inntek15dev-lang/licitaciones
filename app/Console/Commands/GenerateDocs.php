<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use ReflectionClass;

class GenerateDocs extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'docs:generate {--force : Force regenerate viewer assets}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Autonomous documentation generator with Role-Based Flow Diagrams and Cache Busting';

    protected $docsPath;
    protected $dataPath;
    protected $assetsPath;
    protected $diagramsPath;

    public function __construct()
    {
        parent::__construct();
        $this->docsPath = public_path('docs');
        $this->dataPath = public_path('docs/data');
        $this->assetsPath = public_path('docs/assets');
        $this->diagramsPath = public_path('docs/data/diagrams');
    }

    public function handle()
    {
        $this->info('🚀 Starting Robust Project Docs Generation...');

        $this->ensureDirectoryStructure();
        $this->ensureViewerAssets($this->option('force'));

        $this->updateProjectMetadata();
        $modules = $this->updateModulesFromCode();
        $this->updateSkillsInventory();

        $this->generateFlowDiagrams($modules);
        $this->generateDiagramsManifest();

        $this->info('✅ Documentation successfully generated at public/docs/');
        $this->info('🔗 Access URL: ' . url('docs/index.html'));
    }

    protected function ensureDirectoryStructure()
    {
        // Permission Check (Auto-Fix)
        $this->ensurePermissions();

        $dirs = [$this->docsPath, $this->dataPath, $this->assetsPath, $this->diagramsPath];
        foreach ($dirs as $dir) {
            if (!File::exists($dir)) {
                File::makeDirectory($dir, 0755, true);
            }
        }
    }

    protected function ensurePermissions()
    {
        $targets = [
            base_path('.agent/templates'), // Source
            public_path('docs'),           // Target
        ];

        // If 'docs' doesn't exist, check 'public'
        if (!File::exists(public_path('docs'))) {
            $targets[] = public_path();
        }

        $isWindows = strtoupper(substr(PHP_OS, 0, 3)) === 'WIN';

        foreach ($targets as $path) {
            if (!File::exists($path))
                continue;

            if (!is_readable($path) || !is_writable($path)) {
                $this->warn("⚠️ Permissions issue detected on: $path. Attempting Auto-Fix...");

                if ($isWindows) {
                    // Windows: Grant explicit rights to "Users" group
                    // /grant:r replaces explicit permissions (safer than /grant which adds)
                    // (OI)(CI)F = Object Inherit, Container Inherit, Full Control
                    $cmd = "icacls \"$path\" /grant:r \"Users:(OI)(CI)F\" /T";
                    exec($cmd, $output, $returnVar);
                } else {
                    // Linux/Mac: Standard 755
                    $cmd = "chmod -R 755 \"$path\"";
                    exec($cmd, $output, $returnVar);
                }

                if ($returnVar === 0) {
                    $this->info("✓ Permissions fixed for: $path");
                } else {
                    $this->error("❌ Failed to set permissions for: $path. Manual intervention required.");
                }
            }
        }
    }

    protected function ensureViewerAssets($force = false)
    {
        // 1. Index.html
        if ($force || !File::exists($this->docsPath . '/index.html')) {
            $html = <<<'HTML'
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Project Documentation</title>
    <link rel="stylesheet" href="assets/style.css">
    <!-- Using local mermaid if available, otherwise fallback (viewer should handle offline) -->
    <script src="https://cdn.jsdelivr.net/npm/mermaid/dist/mermaid.min.js"></script> 
</head>
<body>
    <div id="app" class="app-container">
        <nav class="sidebar">
            <div class="brand">📚 Project Docs</div>
            <div id="nav-menu" class="nav-menu"></div>
        </nav>
        <main class="main-content">
            <header class="top-bar">
                <h1 id="page-title">Cargando...</h1>
                <div class="last-updated" id="last-updated"></div>
            </header>
            <div id="content-area" class="content-area"></div>
        </main>
    </div>
    <script src="assets/diagram-renderer.js"></script>
    <script src="assets/app.js"></script>
</body>
</html>
HTML;
            File::put($this->docsPath . '/index.html', $html);
            $this->info('✓ Viewer HTML Generated');
        }

        // 2. Style.css
        if ($force || !File::exists($this->assetsPath . '/style.css')) {
            $css = <<<'CSS'
:root { --primary: #2563eb; --sidebar-bg: #1e293b; --text: #334155; --bg: #f8fafc; }
body { margin: 0; font-family: 'Segoe UI', system-ui, sans-serif; background: var(--bg); color: var(--text); display: flex; height: 100vh; overflow: hidden; }
.app-container { display: flex; width: 100%; }
.sidebar { width: 260px; background: var(--sidebar-bg); color: white; display: flex; flex-direction: column; }
.brand { padding: 20px; font-size: 1.2rem; font-weight: bold; border-bottom: 1px solid #334155; }
.nav-menu { padding: 20px 0; overflow-y: auto; flex: 1; }
.nav-item { padding: 12px 20px; cursor: pointer; display: flex; align-items: center; gap: 10px; transition: 0.2s; opacity: 0.8; }
.nav-item:hover, .nav-item.active { background: #334155; opacity: 1; border-left: 4px solid var(--primary); }
.main-content { flex: 1; display: flex; flex-direction: column; overflow: hidden; }
.top-bar { background: white; padding: 20px 30px; border-bottom: 1px solid #e2e8f0; display: flex; justify-content: space-between; align-items: center; }
.content-area { padding: 30px; overflow-y: auto; flex: 1; }
.card { background: white; border-radius: 8px; padding: 20px; box-shadow: 0 1px 3px rgba(0,0,0,0.1); margin-bottom: 20px; }
.grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(300px, 1fr)); gap: 20px; }
h1, h2, h3 { color: #1e293b; margin-top: 0; }
.badge { padding: 4px 8px; border-radius: 4px; font-size: 0.8rem; font-weight: bold; }
.badge.active { background: #dcfce7; color: #166534; }
.badge.pending { background: #fef9c3; color: #854d0e; }
.diagram-container { overflow-x: auto; background: white; padding: 20px; border-radius: 8px; border: 1px solid #e2e8f0; }
/* Node Roles */
.node-role-admin { stroke: #ef4444 !important; stroke-width: 2px; }
.node-role-contratista { stroke: #22c55e !important; stroke-width: 2px; }
.node-role-sistema { stroke: #3b82f6 !important; stroke-width: 2px; }
CSS;
            File::put($this->assetsPath . '/style.css', $css);
            $this->info('✓ Viewer CSS Generated');
        }

        // 3. App.js (Robust Cache Busting)
        if ($force || !File::exists($this->assetsPath . '/app.js')) {
            $js = <<<'JS'
class DocsApp {
    constructor() {
        this.cacheVersion = new Date().getTime(); // Cache buster
        this.data = {};
        this.init();
    }

    async init() {
        console.log('🚀 Initializing DocsApp with cache version:', this.cacheVersion);
        await this.loadProjectData();
        this.renderSidebar();
        this.loadSection('executive'); // Default
    }

    dataUrl(path) {
        return `data/${path}?v=${this.cacheVersion}`;
    }

    async loadProjectData() {
        try {
            const [proj, mods, skills, diagrams] = await Promise.all([
                fetch(this.dataUrl('project.json')).then(r => r.json()),
                fetch(this.dataUrl('modules.json')).then(r => r.json()),
                fetch(this.dataUrl('skills.json')).then(r => r.json()),
                fetch(this.dataUrl('diagrams.json')).then(r => r.json())
            ]);
            this.data = { project: proj, modules: mods.modules, skills: skills, diagrams: diagrams };
            document.title = `${proj.name} - Docs`;
            document.getElementById('last-updated').textContent = `Actualizado: ${proj.lastUpdated}`;
        } catch (e) {
            console.error("Error loading data", e);
            document.getElementById('content-area').innerHTML = `<div class="card"><h2>Error Loading Data</h2><p>${e.message}</p></div>`;
        }
    }

    renderSidebar() {
        const menu = document.getElementById('nav-menu');
        const items = [
            { id: 'executive', icon: '📈', label: 'Resumen Gerencial' },
            { id: 'modules', icon: '📦', label: 'Módulos' },
            { id: 'skills', icon: '🤖', label: 'Skills & Automation' },
            { id: 'diagrams', icon: '🔀', label: 'Diagramas de Flujo' }
        ];

        menu.innerHTML = items.map(item => 
            `<div class="nav-item" onclick="app.loadSection('${item.id}')">
                <span>${item.icon}</span> ${item.label}
            </div>`
        ).join('');
    }

    loadSection(id) {
        const area = document.getElementById('content-area');
        document.getElementById('page-title').textContent = id.toUpperCase();
        
        if (id === 'executive') this.renderExecutive(area);
        if (id === 'modules') this.renderModules(area);
        if (id === 'skills') this.renderSkills(area);
        if (id === 'diagrams') this.renderDiagramsList(area);
    }

    renderExecutive(area) {
        area.innerHTML = `
            <div class="card">
                <h2>${this.data.project.name}</h2>
                <p>${this.data.project.description}</p>
                <div class="grid">
                    <div class="card"><h3>Módulos</h3><p>${this.data.modules.length}</p></div>
                    <div class="card"><h3>Skills</h3><p>${this.data.skills.total_skills}</p></div>
                </div>
            </div>`;
    }

    renderModules(area) {
        area.innerHTML = `<div class="grid">
            ${this.data.modules.map(m => `
                <div class="card">
                    <h3>${m.name}</h3>
                    <p>${m.description}</p>
                    <span class="badge ${m.status}">${m.status}</span>
                </div>
            `).join('')}
        </div>`;
    }

    renderSkills(area) {
        area.innerHTML = `<div class="grid">
            ${this.data.skills.skills.map(s => `
                <div class="card">
                    <h3>${s.name}</h3>
                    <p>${s.description}</p>
                    <code style="display:block;margin-top:10px;background:#eee;padding:5px;">${s.path}</code>
                </div>
            `).join('')}
        </div>`;
    }

    renderDiagramsList(area) {
        area.innerHTML = `<div class="grid">
            ${this.data.diagrams.flow.map(d => `
                <div class="card pointer" onclick="app.renderFlowDiagram('${d.file}')">
                    <h3>${d.title}</h3>
                    <p>Role: ${d.role}</p>
                </div>
            `).join('')}
        </div><div id="diagram-view"></div>`;
    }

    async renderFlowDiagram(file) {
        const area = document.getElementById('diagram-view');
        area.innerHTML = '<div class="card"><h3>Cargando Diagrama...</h3></div>';
        try {
            // Fetch raw XML (cache busted)
            const xmlUrl = this.dataUrl(`diagrams/${file}`);
            const xml = await fetch(xmlUrl).then(r => r.text()); // Pure XML fetch logic here is fine for renderer

            // Create Canvas Container
            area.innerHTML = `
                <div class="card">
                    <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:10px;">
                        <h3>Visualización: ${file}</h3>
                        <button onclick="app.loadSection('diagrams')" style="padding:5px 10px;cursor:pointer;">⬅ Volver</button>
                    </div>
                    <div class="diagram-container" style="overflow:auto;background:#1e293b;border-radius:8px;">
                        <canvas id="flowCanvas" width="800" height="600"></canvas>
                    </div>
                    <p style="margin-top:10px;font-size:0.9em;color:#666;">💡 Arrastra los nodos para reorganizar.</p>
                </div>`;

            // Initialize Renderer
            const canvas = document.getElementById('flowCanvas');
            // Auto resize canvas to fit container width if needed, or keep fixed
            canvas.width = area.offsetWidth - 60; // Approximate padding adjustment
            if (canvas.width < 600) canvas.width = 600;

            if (window.DiagramRenderer) {
                const renderer = new window.DiagramRenderer(canvas);
                await renderer.loadAndRender(xmlUrl);
            } else {
                area.innerHTML += `<p style="color:red">Error: DiagramRenderer not loaded.</p>`;
            }

        } catch(e) { 
            console.error(e);
            area.innerHTML = `<div class="card"><h3 style="color:red">Error cargando diagrama</h3><p>${e.message}</p></div>`; 
        }
    }
}

const app = new DocsApp();
window.app = app;
JS;
            File::put($this->assetsPath . '/app.js', $js);
            $this->info('✓ Viewer App.js (Robust) Generated');
        }
    }

    protected function updateProjectMetadata()
    {
        $data = [
            'name' => 'Licitaciones',
            'version' => '1.0.0',
            'description' => 'Sistema de Gestión de Licitaciones e Inspecciones (RyCE Ecosystem)',
            'lastUpdated' => now()->toIso8601String(),
            'repo' => 'local',
        ];
        File::put($this->dataPath . '/project.json', json_encode($data, JSON_PRETTY_PRINT));
    }

    protected function updateModulesFromCode()
    {
        $modelsPath = app_path('Models');
        $files = File::glob($modelsPath . '/*.php');
        $modules = [];

        foreach ($files as $file) {
            $name = basename($file, '.php');
            if ($name === 'Model')
                continue;

            $modules[] = [
                'id' => Str::lower($name),
                'name' => $name,
                'description' => "Entidad de negocio: $name",
                'status' => 'active',
                'progress' => 100,
            ];
        }

        File::put($this->dataPath . '/modules.json', json_encode(['modules' => $modules], JSON_PRETTY_PRINT));
        $this->info("✓ Modules Inventory Updated (" . count($modules) . " found)");
        return $modules;
    }

    protected function updateSkillsInventory()
    {
        $skillsPath = base_path('.agent/skills');
        $directories = File::directories($skillsPath);
        $skills = [];

        foreach ($directories as $dir) {
            $name = basename($dir);
            $skills[] = [
                'id' => $name,
                'name' => Str::title(str_replace('-', ' ', $name)),
                'description' => 'Skill automatizada del agente',
                'path' => ".agent/skills/$name/SKILL.md"
            ];
        }

        $json = [
            'generated_at' => now()->toIso8601String(),
            'total_skills' => count($skills),
            'skills' => $skills
        ];
        File::put($this->dataPath . '/skills.json', json_encode($json, JSON_PRETTY_PRINT));
    }

    protected function generateFlowDiagrams($modules)
    {
        $diagrams = [];

        // Strategy: Scan Controllers AND Livewire Components
        $sources = [
            app_path('Http/Controllers'),
            app_path('Livewire')
        ];

        $files = [];
        foreach ($sources as $source) {
            if (File::exists($source)) {
                $files = array_merge($files, File::allFiles($source));
            }
        }

        foreach ($files as $file) {
            $content = File::get($file);
            $className = $file->getBasename('.php');

            // Heuristic Role Detection
            $role = 'usuario'; // Default
            if (str_contains($file->getPath(), 'Admin'))
                $role = 'admin';
            if (str_contains($file->getPath(), 'Contratista'))
                $role = 'contratista';
            if (str_contains($file->getPath(), 'Principal'))
                $role = 'principal';
            if (str_contains($content, 'middleware(\'auth:admin\')'))
                $role = 'admin';

            // Method extraction (Simple Regex)
            preg_match_all('/public function ([a-zA-Z0-9_]+)\(.*\)/', $content, $matches);
            $methods = $matches[1] ?? [];

            // Filter typical Livewire/Controller noise
            $methods = array_filter($methods, fn($m) => !in_array($m, ['__construct', 'authorize', 'rules', 'messages']));

            if (empty($methods))
                continue;

            // Use Module Name if possible
            $title = $className;

            $xml = $this->buildFlowXml($title, $methods, $role);
            $fileName = "flow_" . Str::lower($title) . ".xml";

            File::put($this->diagramsPath . '/' . $fileName, $xml);
            $diagrams[] = [
                'title' => "$title Flow",
                'file' => $fileName,
                'role' => $role,
                'nodes' => count($methods)
            ];
        }

        $this->info("✓ Generated " . count($diagrams) . " Role-Based Flow Diagrams");
        return $diagrams;
    }

    protected function buildFlowXml($title, $methods, $role)
    {
        $roleColor = match ($role) {
            'admin' => '#ef4444',
            'contratista' => '#22c55e',
            default => '#3b82f6'
        };

        $nodes = "";
        $y = 50;
        foreach ($methods as $method) {
            if (in_array($method, ['__construct']))
                continue;
            $nodes .= "    <node id=\"$method\" type=\"process\" x=\"200\" y=\"$y\" label=\"$method\" role=\"$role\" color=\"$roleColor\"/>\n";
            $y += 80;
        }

        return <<<XML
<diagram type="flowchart" id="$title">
  <metadata>
    <description>Flow for $title</description>
    <role>$role</role>
  </metadata>
  <nodes>
$nodes
  </nodes>
</diagram>
XML;
    }

    protected function generateDiagramsManifest()
    {
        // Scan directory for generated diagrams to ensure manifest matches reality
        $files = File::glob($this->diagramsPath . '/*.xml');
        $flow = [];
        foreach ($files as $file) {
            $xml = File::get($file);
            // Extract role from metadata
            preg_match('/<role>(.*)<\/role>/', $xml, $matches);
            $role = $matches[1] ?? 'system';

            $flow[] = [
                'file' => basename($file),
                'title' => str_replace(['flow_', '.xml'], '', basename($file)),
                'role' => $role
            ];
        }

        $json = ['er' => [], 'flow' => $flow, 'usecase' => []];
        File::put($this->dataPath . '/diagrams.json', json_encode($json, JSON_PRETTY_PRINT));
        $this->info("✓ Diagrams Manifest Updated");
    }
}
