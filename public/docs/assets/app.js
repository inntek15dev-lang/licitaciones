// Documentation Viewer App
class DocsApp {
    constructor() {
        // Cache busting: Generate timestamp at app init to force fresh data load
        this.cacheVersion = new Date().toISOString().replace(/[:.]/g, '-');
        console.log(`📄 DocsApp initialized with cache version: ${this.cacheVersion}`);

        this.data = {
            project: null,
            modules: null,
            pending: null,
            changelog: null,
            kanban: null,
            rules: null,
            usecases: null
        };
        this.currentFilter = 'all';
        this.flowRenderer = null;
        this.usecaseRenderer = null;
        this.init();
    }

    /**
     * Generate URL with cache busting parameter
     * @param {string} path - The data file path (e.g., 'data/project.json')
     * @returns {string} URL with ?v=timestamp parameter
     */
    dataUrl(path) {
        return `${path}?v=${this.cacheVersion}`;
    }

    async init() {
        await this.loadData();
        this.setupNavigation();
        this.setupFilters();
        // this.setupDiagramButtons(); // Removed: Now handled in render methods

        this.renderExecutive();
        this.renderKanban();
        this.renderModules();
        this.renderERDiagram();
        this.renderRules();
        this.renderUseCases();
        this.renderChangelog();
        this.renderDiagrams("flow");
        this.renderDiagrams("usecase");
    }

    /**
     * Render ER Diagram (fetch SVG and embed)
     */
    async renderERDiagram() {
        if (!this.data.diagrams || !this.data.diagrams.er) return;

        const erList = this.data.diagrams.er;
        const container = document.getElementById('er-container');

        if (!container || erList.length === 0) {
            if (container) container.innerHTML = '<span class="text-muted">No ER diagram available</span>';
            return;
        }

        try {
            const response = await fetch(this.dataUrl(erList[0].path));
            const svgText = await response.text();
            container.innerHTML = svgText;

            // Make SVG responsive
            const svg = container.querySelector('svg');
            if (svg) {
                svg.style.maxWidth = '100%';
                svg.style.height = 'auto';
            }
        } catch (error) {
            console.error('Error loading ER diagram:', error);
            container.innerHTML = '<span class="text-muted">Error cargando diagrama ER</span>';
        }
    }

    async loadData() {
        try {
            // All data files loaded with cache busting parameter
            const [project, modules, pending, changelog, kanban, rules, diagrams, usecases] = await Promise.all([
                fetch(this.dataUrl('data/project.json')).then(r => r.json().catch(() => null)),
                fetch(this.dataUrl('data/modules.json')).then(r => r.json().catch(() => ({ modules: [] }))),
                fetch(this.dataUrl('data/pending.json')).then(r => r.json().catch(() => ({ items: [] }))),
                fetch(this.dataUrl('data/changelog.json')).then(r => r.json().catch(() => ({ entries: [] }))),
                fetch(this.dataUrl('data/kanban.json')).then(r => r.json().catch(() => ({ columns: [] }))),
                fetch(this.dataUrl('data/rules.json')).then(r => r.json().catch(() => ({ rules: [] }))),
                fetch(this.dataUrl('data/diagrams.json')).then(r => r.json().catch(() => ({ flow: [], usecase: [], er: [] }))),
                fetch(this.dataUrl('data/usecases.json')).then(r => r.json().catch(() => ({ usecases: [] })))
            ]);

            this.data = { project, modules, pending, changelog, kanban, rules, diagrams, usecases };
            console.log(`✅ Data loaded with cache version: ${this.cacheVersion}`);
        } catch (error) {
            console.error('Error loading data:', error);
        }
    }

    setupNavigation() {
        document.querySelectorAll('.nav-item').forEach(item => {
            item.addEventListener('click', (e) => {
                e.preventDefault();
                const section = item.dataset.section;

                document.querySelectorAll('.nav-item').forEach(i => i.classList.remove('active'));
                item.classList.add('active');

                document.querySelectorAll('.section').forEach(s => s.classList.remove('active'));
                document.getElementById(section).classList.add('active');
            });
        });
    }

    setupFilters() {
        document.querySelectorAll('.filter-btn').forEach(btn => {
            btn.addEventListener('click', () => {
                document.querySelectorAll('.filter-btn').forEach(b => b.classList.remove('active'));
                btn.classList.add('active');
                this.currentFilter = btn.dataset.filter;
                this.renderModules();
            });
        });
    }

    renderExecutive() {
        if (!this.data.project) return;
        const { project, modules } = this.data;

        // Dynamic Title & Headers
        document.title = `${project.name} - Documentación`;
        document.getElementById('app-title').textContent = project.name;
        document.getElementById('version').textContent = `v${project.version}`;
        document.getElementById('project-description').textContent = project.description;
        document.getElementById('last-updated').textContent = new Date(project.lastUpdated).toLocaleDateString('es-CL');

        if (modules && modules.modules) {
            const modulesList = modules.modules;
            // Stats
            const totalModules = modulesList.length;
            const completedModules = modulesList.filter(m => m.status === 'completed').length;
            const pendingModules = modulesList.filter(m => m.status === 'pending').length;
            const progressPercent = totalModules > 0 ? Math.round((completedModules / totalModules) * 100) : 0;

            document.getElementById('total-modules').textContent = totalModules;
            document.getElementById('completed-modules').textContent = completedModules;
            document.getElementById('pending-modules').textContent = pendingModules;
            document.getElementById('progress-percent').textContent = `${progressPercent}%`;
            document.getElementById('progress-bar').style.width = `${progressPercent}%`;
        }

        // ... pending decisions and stack render logic remains same ...
        // Stack
        const stackGrid = document.getElementById('stack-grid');
        if (project.stack && stackGrid) {
            stackGrid.innerHTML = Object.entries(project.stack).map(([name, version]) => `
                <div class="stack-item">
                    <span class="name">${this.capitalize(name)}</span>
                    <span class="version-tag">${version}</span>
                </div>
            `).join('');
        }
    }

    renderDiagrams(type) {
        if (!this.data.diagrams) return;

        const list = this.data.diagrams[type] || [];
        const selectorId = type === 'flow' ? 'flow-selector' : 'usecase-selector';
        const canvasId = type === 'flow' ? 'flow-canvas' : 'usecase-canvas';
        const container = document.getElementById(selectorId);

        if (!container) return;
        container.innerHTML = '';

        if (list.length === 0) {
            container.innerHTML = '<span class="text-muted">No diagrams available</span>';
            return;
        }

        let renderer = null;
        const canvas = document.getElementById(canvasId);
        if (canvas) {
            renderer = new DiagramRenderer(canvas);
        }

        // Store reference to this for closure
        const self = this;

        list.forEach((diagram, index) => {
            const btn = document.createElement('button');
            btn.className = `diagram-btn ${index === 0 ? 'active' : ''}`;
            btn.textContent = diagram.title;
            btn.addEventListener('click', () => {
                container.querySelectorAll('.diagram-btn').forEach(b => b.classList.remove('active'));
                btn.classList.add('active');
                // Cache busting for diagram XML files
                if (renderer) renderer.loadAndRender(self.dataUrl(diagram.path));
            });
            container.appendChild(btn);

            // Auto load first with cache busting
            if (index === 0 && renderer) {
                renderer.loadAndRender(self.dataUrl(diagram.path));
            }
        });
    }

    // ... renderKanban, renderModules, renderRules, renderChangelog, helpers remain same ...


    renderKanban() {
        if (!this.data.kanban || !this.data.kanban.columns) return;

        const board = document.getElementById('kanban-board');
        const { columns } = this.data.kanban;

        board.innerHTML = columns.map(column => `
            <div class="kanban-column">
                <div class="kanban-header">
                    <h3>${column.title}</h3>
                    <span class="kanban-count">${column.items.length}</span>
                </div>
                <div class="kanban-items">
                    ${column.items.map(item => `
                        <div class="kanban-card ${item.priority || ''}">
                            <h4>${item.title}</h4>
                            <p>${item.description}</p>
                            <div class="kanban-meta">
                                ${item.version ? `<span class="kanban-tag">v${item.version}</span>` : ''}
                                ${item.priority ? `<span class="kanban-tag">${item.priority}</span>` : ''}
                                ${item.requestedBy ? `<span class="kanban-tag">Por: ${item.requestedBy}</span>` : ''}
                                ${item.awaitingFrom ? `<span class="kanban-tag">Espera: ${item.awaitingFrom}</span>` : ''}
                            </div>
                        </div>
                    `).join('')}
                </div>
            </div>
        `).join('');
    }

    renderModules() {
        if (!this.data.modules) return;

        const grid = document.getElementById('modules-grid');
        let modules = this.data.modules.modules;

        if (this.currentFilter !== 'all') {
            modules = modules.filter(m => m.status === this.currentFilter);
        }

        grid.innerHTML = modules.map(module => `
            <div class="module-card ${module.status}">
                <div class="module-header">
                    <h4>${module.name}</h4>
                    <span class="status-badge ${module.status}">${this.getStatusLabel(module.status)}</span>
                </div>
                <p class="module-description">${module.description}</p>
                <div class="module-progress">
                    <div class="module-progress-bar" style="width: ${module.progress}%"></div>
                </div>
                <div class="module-features">
                    ${module.features.slice(0, 4).map(f => `
                        <span class="feature-tag ${f.status}">${f.name}</span>
                    `).join('')}
                    ${module.features.length > 4 ? `<span class="feature-tag">+${module.features.length - 4} más</span>` : ''}
                </div>
            </div>
        `).join('');
    }

    renderRules() {
        console.log('Rendering rules:', this.data.rules); // DEBUG
        if (!this.data.rules || !this.data.rules.rules) {
            console.warn('Rules data missing', this.data);
            return;
        }

        const tbody = document.getElementById('rules-table-body');
        if (!tbody) {
            console.error('Tbody not found');
            return;
        }
        tbody.innerHTML = this.data.rules.rules.map(rule => `
            <tr>
                <td class="rule-id">${rule.id}</td>
                <td class="rule-title">${rule.title}</td>
                <td>${rule.description}</td>
                <td><span class="rule-type ${rule.type}">${this.capitalize(rule.type)}</span></td>
                <td><span class="module-tag">${rule.module_id}</span></td>
            </tr>
        `).join('');
    }

    renderUseCases() {
        if (!this.data.usecases || !this.data.usecases.usecases) {
            console.warn('Use cases data missing');
            return;
        }

        const container = document.getElementById('usecases-list');
        if (!container) {
            console.error('Use cases container not found');
            return;
        }

        const usecases = this.data.usecases.usecases;

        // Group by actor
        const byActor = usecases.reduce((acc, uc) => {
            const actor = uc.actor || 'Sin Actor';
            if (!acc[actor]) acc[actor] = [];
            acc[actor].push(uc);
            return acc;
        }, {});

        container.innerHTML = Object.entries(byActor).map(([actor, cases]) => `
            <div class="usecase-group">
                <h3 class="usecase-actor">👤 ${actor}</h3>
                <div class="usecase-cards">
                    ${cases.map(uc => `
                        <div class="usecase-card">
                            <span class="usecase-id">${uc.id}</span>
                            <h4>${uc.title}</h4>
                            <p>${uc.description}</p>
                            <span class="module-tag">${uc.module_id}</span>
                        </div>
                    `).join('')}
                </div>
            </div>
        `).join('');
    }

    renderChangelog() {
        if (!this.data.changelog || !this.data.changelog.entries) return;

        const timeline = document.getElementById('changelog-timeline');
        const entries = [...this.data.changelog.entries].reverse();

        timeline.innerHTML = entries.map(entry => `
            <div class="timeline-item ${entry.type}">
                <div class="timeline-header">
                    <span class="timeline-version">v${entry.version}</span>
                    <span class="timeline-date">${new Date(entry.date).toLocaleDateString('es-CL')}</span>
                </div>
                <div class="timeline-content">
                    <p>${entry.description}</p>
                </div>
            </div>
        `).join('');
    }

    getStatusLabel(status) {
        const labels = {
            'completed': 'Completado',
            'in-progress': 'En Progreso',
            'pending': 'Pendiente'
        };
        return labels[status] || status;
    }

    capitalize(str) {
        return str.charAt(0).toUpperCase() + str.slice(1);
    }
}

// Initialize app
document.addEventListener('DOMContentLoaded', () => {
    new DocsApp();
});
