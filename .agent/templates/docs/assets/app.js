// Documentation Viewer App
class DocsApp {
    constructor() {
        this.data = {};
        this.currentFilter = 'all';
        this.flowRenderer = null;
        this.usecaseRenderer = null;

        // Configuración de secciones disponibles (mapeado json -> config)
        this.sectionConfig = {
            'project': { id: 'executive', icon: '📈', label: 'Resumen Gerencial', order: 1 },
            'kanban': { id: 'kanban', icon: '📋', label: 'Tablero Kanban', order: 2 },
            'modules': { id: 'modules', icon: '📦', label: 'Módulos', order: 3 },
            'diagrams': { id: 'diagrams', icon: '🔀', label: 'Diagramas de Flujo', order: 4, isFolder: true },
            'modeldata': { id: 'datamodel', icon: '🗄️', label: 'Modelo de Datos', order: 5 },
            'usecases': { id: 'usecases', icon: '👤', label: 'Casos de Uso', order: 6 },
            'rules': { id: 'rules', icon: '⚖️', label: 'Reglas de Negocio', order: 7 },
            'skills': { id: 'skills', icon: '🤖', label: 'Skills & Automation', order: 8 },
            'pending': { id: 'pending', icon: '⏳', label: 'Pendientes', order: 9 },
            'changelog': { id: 'changelog', icon: '📜', label: 'Changelog', order: 10 }
        };

        this.init();
    }

    async init() {
        await this.loadData();
        this.buildDynamicMenu();
        this.buildDynamicSections();
        this.setupNavigation();
        this.setupFilters();
        this.setupDiagramButtons();
        this.renderAllSections();

        // Initialize diagram renderers
        const flowCanvas = document.getElementById('flow-canvas');
        const usecaseCanvas = document.getElementById('usecase-canvas');

        if (flowCanvas) {
            this.flowRenderer = new DiagramRenderer(flowCanvas);
            this.flowRenderer.loadAndRender('data/diagrams/flow-registro.xml');
        }

        if (usecaseCanvas) {
            this.usecaseRenderer = new DiagramRenderer(usecaseCanvas);
            this.usecaseRenderer.loadAndRender('data/diagrams/usecase-admin.xml');
        }
    }

    async loadData() {
        // Lista de JSONs posibles a cargar
        const jsonFiles = ['project', 'modules', 'pending', 'changelog', 'kanban', 'rules', 'skills'];

        const loadPromises = jsonFiles.map(async (name) => {
            try {
                const response = await fetch(`data/${name}.json`);
                if (response.ok) {
                    return { name, data: await response.json() };
                }
            } catch (e) {
                console.warn(`No se pudo cargar ${name}.json`);
            }
            return { name, data: null };
        });

        const results = await Promise.all(loadPromises);
        results.forEach(({ name, data }) => {
            if (data) this.data[name] = data;
        });

        // Cargar model-data.json desde diagrams
        try {
            const modelResponse = await fetch('data/diagrams/model-data.json');
            if (modelResponse.ok) {
                this.data.modeldata = await modelResponse.json();
            }
        } catch (e) {
            console.warn('No se pudo cargar model-data.json');
        }

        // Verificar si existe la carpeta de diagramas
        try {
            const diagramsResponse = await fetch('data/diagrams/flow-registro.xml');
            if (diagramsResponse.ok) {
                this.data.diagrams = true;
            }
        } catch (e) {
            console.warn('No hay diagramas disponibles');
        }
    }

    buildDynamicMenu() {
        const navMenu = document.querySelector('.nav-menu');
        if (!navMenu) return;

        // Limpiar menú existente
        navMenu.innerHTML = '';

        // Construir menú basándose en los datos cargados
        const availableSections = [];

        Object.keys(this.sectionConfig).forEach(key => {
            const config = this.sectionConfig[key];
            let hasData = false;

            // Verificar si hay datos para esta sección
            if (key === 'diagrams' || key === 'usecases') {
                hasData = this.data.diagrams || false;
            } else if (key === 'modeldata') {
                hasData = !!this.data.modeldata;
            } else if (key === 'project') {
                hasData = !!this.data.project;
            } else {
                hasData = !!this.data[key];
            }

            if (hasData) {
                availableSections.push({ key, ...config });
            }
        });

        // Ordenar por orden definido
        availableSections.sort((a, b) => a.order - b.order);

        // Generar elementos del menú
        availableSections.forEach((section, index) => {
            const navItem = document.createElement('a');
            navItem.href = '#';
            navItem.className = `nav-item${index === 0 ? ' active' : ''}`;
            navItem.dataset.section = section.id;
            navItem.innerHTML = `<span class="icon">${section.icon}</span> ${section.label}`;
            navMenu.appendChild(navItem);
        });

        console.log(`📋 Menú dinámico generado con ${availableSections.length} secciones`);
    }

    buildDynamicSections() {
        const mainContent = document.querySelector('.main-content');
        if (!mainContent) return;

        // Verificar si existe la sección de skills, si no, crearla
        if (this.data.skills && !document.getElementById('skills')) {
            const skillsSection = document.createElement('section');
            skillsSection.id = 'skills';
            skillsSection.className = 'section';
            skillsSection.innerHTML = `
                <header class="section-header">
                    <h2>🤖 Skills & Automation</h2>
                    <p>Inventario de habilidades del agente disponibles en el proyecto</p>
                </header>
                <div class="skills-overview" id="skills-overview"></div>
                <div class="skills-grid" id="skills-grid"></div>
                <div class="execution-chain" id="execution-chain"></div>
            `;
            mainContent.appendChild(skillsSection);
        }

        // Verificar si existe la sección de pending, si no, crearla
        if (this.data.pending && !document.getElementById('pending')) {
            const pendingSection = document.createElement('section');
            pendingSection.id = 'pending';
            pendingSection.className = 'section';
            pendingSection.innerHTML = `
                <header class="section-header">
                    <h2>⏳ Decisiones Pendientes</h2>
                    <p>Items que requieren respuesta o decisión</p>
                </header>
                <div class="pending-list" id="pending-list"></div>
            `;
            mainContent.appendChild(pendingSection);
        }
    }

    renderAllSections() {
        this.renderExecutive();
        this.renderKanban();
        this.renderModules();
        this.renderRules();
        this.renderChangelog();
        this.renderSkills();
        this.renderPending();
        this.renderDataModel();
    }

    setupNavigation() {
        document.querySelectorAll('.nav-item').forEach(item => {
            item.addEventListener('click', (e) => {
                e.preventDefault();
                const section = item.dataset.section;

                document.querySelectorAll('.nav-item').forEach(i => i.classList.remove('active'));
                item.classList.add('active');

                document.querySelectorAll('.section').forEach(s => s.classList.remove('active'));
                const targetSection = document.getElementById(section);
                if (targetSection) {
                    targetSection.classList.add('active');
                }
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

    setupDiagramButtons() {
        // Flow diagrams
        document.querySelectorAll('#diagrams .diagram-btn').forEach(btn => {
            btn.addEventListener('click', () => {
                document.querySelectorAll('#diagrams .diagram-btn').forEach(b => b.classList.remove('active'));
                btn.classList.add('active');
                if (this.flowRenderer) {
                    this.flowRenderer.loadAndRender(btn.dataset.diagram);
                }
            });
        });

        // Use case diagrams
        document.querySelectorAll('#usecases .diagram-btn').forEach(btn => {
            btn.addEventListener('click', () => {
                document.querySelectorAll('#usecases .diagram-btn').forEach(b => b.classList.remove('active'));
                btn.classList.add('active');
                if (this.usecaseRenderer) {
                    this.usecaseRenderer.loadAndRender(btn.dataset.diagram);
                }
            });
        });
    }

    renderExecutive() {
        if (!this.data.project || !this.data.modules) return;

        const { project, modules, pending } = this.data;
        const modulesList = modules.modules;

        // Project info
        document.getElementById('version').textContent = `v${project.version}`;
        document.getElementById('project-description').textContent = project.description;

        // Mostrar fecha y hora de última actualización
        const lastUpdate = new Date(project.lastUpdated);
        const dateStr = lastUpdate.toLocaleDateString('es-CL');
        const timeStr = lastUpdate.toLocaleTimeString('es-CL', { hour: '2-digit', minute: '2-digit' });
        document.getElementById('last-updated').textContent = `${dateStr} ${timeStr}`;

        // Stats
        const totalModules = modulesList.length;
        const completedModules = modulesList.filter(m => m.status === 'completed').length;
        const pendingModules = modulesList.filter(m => m.status === 'pending').length;
        const progressPercent = Math.round((completedModules / totalModules) * 100);

        document.getElementById('total-modules').textContent = totalModules;
        document.getElementById('completed-modules').textContent = completedModules;
        document.getElementById('pending-modules').textContent = pendingModules;
        document.getElementById('progress-percent').textContent = `${progressPercent}%`;
        document.getElementById('progress-bar').style.width = `${progressPercent}%`;

        // Pending decisions
        if (pending && pending.decisions) {
            document.getElementById('pending-decisions').textContent = pending.decisions.length;
        }

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

    renderKanban() {
        if (!this.data.kanban) return;

        const board = document.getElementById('kanban-board');
        if (!board) return;

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
        if (!grid) return;

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
        if (!this.data.rules || !this.data.rules.rules) return;

        const tbody = document.getElementById('rules-table-body');
        if (!tbody) return;

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

    renderSkills() {
        if (!this.data.skills) return;

        const overview = document.getElementById('skills-overview');
        const grid = document.getElementById('skills-grid');
        const chain = document.getElementById('execution-chain');

        if (!grid) return;

        const { skills, total_skills, generated_at, execution_chain } = this.data.skills;

        // Overview
        if (overview) {
            overview.innerHTML = `
                <div class="skills-stats">
                    <div class="skill-stat">
                        <span class="stat-number">${total_skills}</span>
                        <span class="stat-label">Skills Totales</span>
                    </div>
                    <div class="skill-stat">
                        <span class="stat-number">${skills.filter(s => s.priority === 'HIGH').length}</span>
                        <span class="stat-label">Alta Prioridad</span>
                    </div>
                    <div class="skill-stat">
                        <span class="stat-text">${new Date(generated_at).toLocaleDateString('es-CL')}</span>
                        <span class="stat-label">Última Actualización</span>
                    </div>
                </div>
            `;
        }

        // Grid de skills
        grid.innerHTML = skills.map(skill => `
            <div class="skill-card priority-${(skill.priority || 'NORMAL').toLowerCase()}">
                <div class="skill-header">
                    <h4>${skill.name}</h4>
                    <span class="priority-badge ${(skill.priority || 'NORMAL').toLowerCase()}">${skill.priority || 'NORMAL'}</span>
                </div>
                <p class="skill-description">${skill.description}</p>
                <div class="skill-meta">
                    <span class="skill-role">${skill.role || ''}</span>
                </div>
                <div class="skill-triggers">
                    <strong>Triggers:</strong>
                    <ul>
                        ${(skill.trigger_conditions || []).slice(0, 3).map(t => `<li>${t}</li>`).join('')}
                    </ul>
                </div>
                <div class="skill-actions">
                    <strong>Acciones:</strong>
                    <ul>
                        ${(skill.main_actions || []).slice(0, 3).map(a => `<li>${a.section}</li>`).join('')}
                    </ul>
                </div>
                <div class="skill-footer">
                    <span class="skill-order">Orden: ${skill.execution_order || '-'}</span>
                    <a href="${skill.path}" class="skill-link" target="_blank">Ver SKILL.md</a>
                </div>
            </div>
        `).join('');

        // Cadena de ejecución
        if (chain && execution_chain) {
            chain.innerHTML = `
                <h3>🔗 Cadena de Ejecución</h3>
                <div class="chain-diagram">
                    ${execution_chain.order.map((id, index) => {
                const skill = skills.find(s => s.id === id);
                const icon = this.getSkillIcon(id);
                return `
                            <div class="chain-item">
                                <span class="chain-icon">${icon}</span>
                                <span class="chain-name">${skill ? skill.name : id}</span>
                            </div>
                            ${index < execution_chain.order.length - 1 ? '<span class="chain-arrow">→</span>' : ''}
                        `;
            }).join('')}
                </div>
                <p class="chain-note">${execution_chain.notes || ''}</p>
            `;
        }
    }

    renderPending() {
        if (!this.data.pending) return;

        const list = document.getElementById('pending-list');
        if (!list) return;

        const { decisions } = this.data.pending;

        if (!decisions || decisions.length === 0) {
            list.innerHTML = '<p class="no-data">No hay decisiones pendientes</p>';
            return;
        }

        list.innerHTML = decisions.map(decision => `
            <div class="pending-card ${decision.priority || ''}">
                <div class="pending-header">
                    <h4>${decision.title}</h4>
                    ${decision.priority ? `<span class="priority-badge ${decision.priority}">${decision.priority}</span>` : ''}
                </div>
                <p>${decision.description}</p>
                <div class="pending-meta">
                    ${decision.awaitingFrom ? `<span>Esperando: ${decision.awaitingFrom}</span>` : ''}
                    ${decision.module ? `<span>Módulo: ${decision.module}</span>` : ''}
                </div>
            </div>
        `).join('');
    }

    renderChangelog() {
        if (!this.data.changelog) return;

        const timeline = document.getElementById('changelog-timeline');
        if (!timeline) return;

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

    getSkillIcon(id) {
        const icons = {
            'maintenance': '🧹',
            'naming-integrity': '📛',
            'env-assurance': '🔌',
            'data-modeler': '🗄️',
            'sql-to-laravel': '🗄️',
            'privilegios-engine': '🔐',
            'view-assurance': '👁️',
            'project-docs': '📚'
        };
        return icons[id] || '🔧';
    }

    async renderDataModel() {
        if (!this.data.modeldata) return;

        const stats = document.getElementById('datamodel-stats');
        const erdContainer = document.getElementById('mermaid-erd');
        const tablesGrid = document.getElementById('tables-grid');

        if (!erdContainer) return;

        const { tables, relationships, mermaid_erd, generated_at } = this.data.modeldata;

        // Stats
        if (stats) {
            stats.innerHTML = `
                <div class="datamodel-stat">
                    <span class="stat-number">${tables?.length || 0}</span>
                    <span class="stat-label">Tablas</span>
                </div>
                <div class="datamodel-stat">
                    <span class="stat-number">${relationships?.length || 0}</span>
                    <span class="stat-label">Relaciones</span>
                </div>
                <div class="datamodel-stat">
                    <span class="stat-text">${generated_at ? new Date(generated_at).toLocaleDateString('es-CL') : '-'}</span>
                    <span class="stat-label">Generado</span>
                </div>
            `;
        }

        // Render Mermaid ERD
        if (mermaid_erd && erdContainer) {
            try {
                // Wait for mermaid to be ready
                const mermaidLib = window.mermaidReady ? await window.mermaidReady : (typeof mermaid !== 'undefined' ? mermaid : null);

                if (mermaidLib) {
                    mermaidLib.initialize({
                        startOnLoad: false,
                        theme: 'dark',
                        securityLevel: 'loose',
                        er: {
                            useMaxWidth: true,
                            layoutDirection: 'TB',
                            diagramPadding: 20
                        },
                        themeVariables: {
                            primaryColor: '#a855f7',
                            primaryTextColor: '#f1f5f9',
                            primaryBorderColor: '#334155',
                            lineColor: '#64748b',
                            secondaryColor: '#1e293b',
                            tertiaryColor: '#0f172a'
                        }
                    });

                    // Use async render API
                    const uniqueId = 'mermaid-erd-' + Date.now();
                    const { svg } = await mermaidLib.render(uniqueId, mermaid_erd);
                    erdContainer.innerHTML = svg;

                    // Style the SVG for dark theme
                    const svgEl = erdContainer.querySelector('svg');
                    if (svgEl) {
                        svgEl.style.maxWidth = '100%';
                        svgEl.style.height = 'auto';
                        svgEl.style.minHeight = '300px';
                    }
                } else {
                    // Fallback if mermaid not loaded - show formatted text
                    const formattedErd = mermaid_erd.replace(/\\n/g, '\n').split('\n').map(line =>
                        line.replace(/(\w+)\s*\|\|--o\{\s*(\w+)/g, '<span style="color:#a855f7">$1</span> ──○{ <span style="color:#3b82f6">$2</span>')
                    ).join('\n');
                    erdContainer.innerHTML = `
                        <div style="text-align:center;padding:1rem">
                            <p style="color:#eab308;margin-bottom:1rem">⚠️ Mermaid no disponible - Mostrando formato texto</p>
                            <pre style="background:#1e293b;padding:1.5rem;border-radius:12px;overflow-x:auto;color:#94a3b8;font-size:0.85rem;text-align:left;line-height:1.8">${formattedErd}</pre>
                        </div>
                    `;
                }
            } catch (e) {
                console.error('Error renderizando Mermaid ERD:', e);
                // Pretty fallback showing the raw syntax
                erdContainer.innerHTML = `
                    <div style="text-align:center;padding:2rem">
                        <p style="color:#ef4444;margin-bottom:1rem">⚠️ Error renderizando diagrama ERD</p>
                        <p style="color:#94a3b8;margin-bottom:1rem;font-size:0.85rem">${e.message || 'Error desconocido'}</p>
                        <pre style="background:#1e293b;padding:1rem;border-radius:8px;overflow-x:auto;color:#94a3b8;font-size:0.75rem;text-align:left">${mermaid_erd.replace(/\\n/g, '\n')}</pre>
                    </div>
                `;
            }
        }

        // Tables grid
        if (tablesGrid && tables) {
            tablesGrid.innerHTML = tables.slice(0, 12).map(table => `
                <div class="table-card">
                    <h4>${table.name}</h4>
                    <p class="table-desc">${table.description || ''}</p>
                    <div class="table-columns">
                        ${table.columns.slice(0, 5).map(col =>
                `<span class="col-tag ${col.primary ? 'primary' : ''}">${col.name}</span>`
            ).join('')}
                        ${table.columns.length > 5 ? `<span class="col-tag">+${table.columns.length - 5}</span>` : ''}
                    </div>
                    <div class="table-relations">
                        ${(table.relationships || []).slice(0, 3).map(rel =>
                `<span class="rel-tag">${rel.type} → ${rel.target}</span>`
            ).join('')}
                    </div>
                </div>
            `).join('');
        }
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
