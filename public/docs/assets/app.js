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