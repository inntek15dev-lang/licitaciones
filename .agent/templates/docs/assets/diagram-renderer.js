/**
 * Diagram Renderer - Parses XML diagrams and renders on canvas with Drag & Drop support
 */
class DiagramRenderer {
    constructor(canvas) {
        this.canvas = canvas;
        this.ctx = canvas.getContext('2d');
        this.nodes = [];
        this.connections = [];
        this.roles = {}; // Role definitions with colors
        this.isDragging = false;
        this.dragNode = null;
        this.dragOffset = { x: 0, y: 0 };
        this.currentType = null;
        this.showRoles = true; // Toggle role display

        // Default role colors
        this.defaultRoleColors = {
            'sistema': '#3b82f6',
            'contratista': '#22c55e',
            'admin': '#ef4444',
            'admin_contrato': '#a855f7'
        };

        this.setupInteraction();
    }

    setupInteraction() {
        this.canvas.addEventListener('mousedown', this.handleMouseDown.bind(this));
        this.canvas.addEventListener('mousemove', this.handleMouseMove.bind(this));
        this.canvas.addEventListener('mouseup', this.handleMouseUp.bind(this));
        this.canvas.addEventListener('mouseleave', this.handleMouseUp.bind(this));
    }

    async loadAndRender(xmlPath) {
        try {
            const response = await fetch(xmlPath);
            const xmlText = await response.text();
            const parser = new DOMParser();
            const xmlDoc = parser.parseFromString(xmlText, 'text/xml');

            const diagram = xmlDoc.querySelector('diagram');
            this.currentType = diagram.getAttribute('type');

            this.parseDiagram(xmlDoc);
            this.draw();
        } catch (error) {
            console.error('Error loading diagram:', error);
            this.renderError('Error cargando diagrama');
        }
    }

    parseDiagram(xmlDoc) {
        this.nodes = [];
        this.connections = [];
        this.roles = {};

        // Parse roles first
        this.parseRoles(xmlDoc);

        switch (this.currentType) {
            case 'flowchart':
                this.parseFlowchart(xmlDoc);
                break;
            case 'lifecycle':
                this.parseLifecycle(xmlDoc);
                break;
            case 'usecase':
                this.parseUseCase(xmlDoc);
                break;
        }
    }

    parseRoles(xmlDoc) {
        xmlDoc.querySelectorAll('roles > role').forEach(r => {
            this.roles[r.getAttribute('id')] = {
                name: r.getAttribute('name'),
                color: r.getAttribute('color') || this.defaultRoleColors[r.getAttribute('id')] || '#64748b'
            };
        });
    }

    draw() {
        this.clear();

        // Draw connections first
        this.connections.forEach(conn => {
            const fromNode = this.nodes.find(n => n.id === conn.from);
            const toNode = this.nodes.find(n => n.id === conn.to);
            if (fromNode && toNode) {
                if (this.currentType === 'lifecycle') {
                    this.drawCurvedArrow(fromNode, toNode, conn.label);
                } else if (this.currentType === 'usecase') {
                    // For use cases, connections might be associations
                    this.drawAssociation(fromNode, toNode);
                } else {
                    this.drawConnection(fromNode, toNode, conn.label);
                }
            }
        });

        // Draw nodes
        this.nodes.forEach(node => {
            switch (this.currentType) {
                case 'flowchart':
                    this.drawFlowchartNode(node);
                    break;
                case 'lifecycle':
                    this.drawLifecycleNode(node);
                    break;
                case 'usecase':
                    if (node.type === 'actor') this.drawActor(node);
                    else this.drawUseCaseNode(node);
                    break;
            }
        });
    }

    // --- Interaction Handlers ---

    handleMouseDown(e) {
        const rect = this.canvas.getBoundingClientRect();
        const x = e.clientX - rect.left;
        const y = e.clientY - rect.top;

        // Find clicked node (iterate backwards to click top-most)
        for (let i = this.nodes.length - 1; i >= 0; i--) {
            const node = this.nodes[i];
            if (this.isPointInNode(x, y, node)) {
                this.isDragging = true;
                this.dragNode = node;
                this.dragOffset = { x: x - node.x, y: y - node.y };
                this.canvas.style.cursor = 'grabbing';
                return;
            }
        }
    }

    handleMouseMove(e) {
        if (!this.isDragging || !this.dragNode) {
            // Hover effect cursor
            const rect = this.canvas.getBoundingClientRect();
            const x = e.clientX - rect.left;
            const y = e.clientY - rect.top;
            const hovered = this.nodes.some(n => this.isPointInNode(x, y, n));
            this.canvas.style.cursor = hovered ? 'grab' : 'default';
            return;
        }

        const rect = this.canvas.getBoundingClientRect();
        this.dragNode.x = e.clientX - rect.left - this.dragOffset.x;
        this.dragNode.y = e.clientY - rect.top - this.dragOffset.y;

        requestAnimationFrame(() => this.draw());
    }

    handleMouseUp() {
        this.isDragging = false;
        this.dragNode = null;
        this.canvas.style.cursor = 'default';
    }

    isPointInNode(x, y, node) {
        // Approximate hit testing
        const w = node.width || 100;
        const h = node.height || 50;

        // Center-based coordinates for most nodes
        return x >= node.x - w / 2 && x <= node.x + w / 2 &&
            y >= node.y - h / 2 && y <= node.y + h / 2;
    }

    // --- Parsing ---

    parseFlowchart(xmlDoc) {
        xmlDoc.querySelectorAll('nodes > node').forEach(n => {
            this.nodes.push({
                id: n.getAttribute('id'),
                type: n.getAttribute('type'),
                x: parseInt(n.getAttribute('x')),
                y: parseInt(n.getAttribute('y')),
                label: n.getAttribute('label'),
                role: n.getAttribute('role') || null,
                width: n.getAttribute('type') === 'process' ? 140 : 100,
                height: 50
            });
        });
        xmlDoc.querySelectorAll('connections > connect').forEach(c => {
            this.connections.push({
                from: c.getAttribute('from'),
                to: c.getAttribute('to'),
                label: c.getAttribute('label') || ''
            });
        });
    }

    parseLifecycle(xmlDoc) {
        xmlDoc.querySelectorAll('states > state').forEach(s => {
            this.nodes.push({
                id: s.getAttribute('id'),
                type: 'state',
                x: parseInt(s.getAttribute('x')),
                y: parseInt(s.getAttribute('y')),
                label: s.getAttribute('label'),
                color: s.getAttribute('color'),
                role: s.getAttribute('role') || null,
                width: 100,
                height: 40
            });
        });
        xmlDoc.querySelectorAll('transitions > transition').forEach(t => {
            this.connections.push({
                from: t.getAttribute('from'),
                to: t.getAttribute('to'),
                label: t.getAttribute('label') || '',
                actor: t.getAttribute('actor') || null
            });
        });
    }

    parseUseCase(xmlDoc) {
        xmlDoc.querySelectorAll('actor').forEach(a => {
            this.nodes.push({
                id: a.getAttribute('id'),
                type: 'actor',
                x: parseInt(a.getAttribute('x')),
                y: parseInt(a.getAttribute('y')),
                label: a.getAttribute('label'),
                width: 40,
                height: 80
            });
        });
        xmlDoc.querySelectorAll('usecases > usecase').forEach(u => {
            this.nodes.push({
                id: u.getAttribute('id'),
                type: 'usecase',
                x: parseInt(u.getAttribute('x')),
                y: parseInt(u.getAttribute('y')),
                label: u.getAttribute('label'),
                width: 150,
                height: 50
            });
        });
        xmlDoc.querySelectorAll('associations > associate').forEach(a => {
            this.connections.push({
                from: a.getAttribute('actor'),
                to: a.getAttribute('usecase')
            });
        });
    }

    // --- Drawing Helpers ---

    clear() {
        this.ctx.fillStyle = '#1e293b';
        this.ctx.fillRect(0, 0, this.canvas.width, this.canvas.height);

        // Draw roles legend if roles are defined
        if (this.showRoles && Object.keys(this.roles).length > 0) {
            this.drawRolesLegend();
        }

        // Draw system boundary for Use Case
        if (this.currentType === 'usecase') {
            this.ctx.save();
            this.ctx.strokeStyle = '#64748b';
            this.ctx.lineWidth = 2;
            this.ctx.setLineDash([5, 5]);
            this.roundRect(180, 30, 250, this.nodes.filter(n => n.type === 'usecase').length * 60 + 40, 10);
            this.ctx.stroke();
            this.ctx.setLineDash([]);
            this.ctx.fillStyle = '#94a3b8';
            this.ctx.font = '14px Segoe UI';
            this.ctx.textAlign = 'center';
            this.ctx.fillText('Sistema OIEM', 305, 20);
            this.ctx.restore();
        }
    }

    drawRolesLegend() {
        const ctx = this.ctx;
        ctx.save();

        const legendX = 520;
        const legendY = 20;
        const roleCount = Object.keys(this.roles).length;
        const legendHeight = roleCount * 25 + 30;

        // Legend background
        ctx.fillStyle = 'rgba(30, 41, 59, 0.95)';
        ctx.strokeStyle = '#475569';
        ctx.lineWidth = 1;
        ctx.beginPath();
        ctx.roundRect(legendX, legendY, 160, legendHeight, 8);
        ctx.fill();
        ctx.stroke();

        // Legend title
        ctx.fillStyle = '#94a3b8';
        ctx.font = 'bold 11px Segoe UI';
        ctx.textAlign = 'left';
        ctx.fillText('👤 ROLES / ACTORES', legendX + 10, legendY + 18);

        // Role items
        let yOffset = 40;
        Object.entries(this.roles).forEach(([id, role]) => {
            // Color indicator
            ctx.fillStyle = role.color;
            ctx.beginPath();
            ctx.arc(legendX + 18, legendY + yOffset - 4, 6, 0, Math.PI * 2);
            ctx.fill();

            // Role name
            ctx.fillStyle = '#e2e8f0';
            ctx.font = '11px Segoe UI';
            ctx.fillText(role.name, legendX + 30, legendY + yOffset);

            yOffset += 25;
        });

        ctx.restore();
    }

    renderError(message) {
        this.ctx.fillStyle = '#ef4444';
        this.ctx.font = '16px Segoe UI';
        this.ctx.fillText(message, 50, 50);
    }

    roundRect(x, y, width, height, radius) {
        this.ctx.beginPath();
        this.ctx.roundRect(x, y, width, height, radius);
        this.ctx.closePath();
    }

    // --- Specific Drawing Methods ---

    drawFlowchartNode(node) {
        const { type, x, y, label, role } = node;
        this.ctx.save();

        // Get role color if available
        const roleColor = role && this.roles[role] ? this.roles[role].color : null;

        switch (type) {
            case 'start':
            case 'end':
                this.ctx.beginPath();
                this.ctx.ellipse(x, y, 40, 25, 0, 0, Math.PI * 2);
                this.ctx.fillStyle = type === 'start' ? '#22c55e' : '#ef4444';
                this.ctx.fill();
                this.ctx.strokeStyle = '#fff';
                this.ctx.lineWidth = 2;
                this.ctx.stroke();
                break;
            case 'process':
                // Use role color for processes
                this.ctx.fillStyle = roleColor || '#3b82f6';
                this.ctx.beginPath();
                this.ctx.roundRect(x - 70, y - 20, 140, 40, 8);
                this.ctx.fill();
                this.ctx.strokeStyle = this.lightenColor(roleColor || '#3b82f6', 30);
                this.ctx.lineWidth = 2;
                this.ctx.stroke();

                // Draw role indicator dot
                if (role && this.showRoles) {
                    this.drawRoleIndicator(x + 60, y - 15, roleColor);
                }
                break;
            case 'decision':
                this.ctx.beginPath();
                this.ctx.moveTo(x, y - 30);
                this.ctx.lineTo(x + 50, y);
                this.ctx.lineTo(x, y + 30);
                this.ctx.lineTo(x - 50, y);
                this.ctx.closePath();
                // Use role color for decisions too
                this.ctx.fillStyle = roleColor || '#eab308';
                this.ctx.fill();
                this.ctx.strokeStyle = this.lightenColor(roleColor || '#eab308', 30);
                this.ctx.lineWidth = 2;
                this.ctx.stroke();

                // Draw role indicator dot
                if (role && this.showRoles) {
                    this.drawRoleIndicator(x + 45, y - 25, roleColor);
                }
                break;
        }

        this.drawLabel(x, y, label);
        this.ctx.restore();
    }

    drawRoleIndicator(x, y, color) {
        this.ctx.save();
        this.ctx.beginPath();
        this.ctx.arc(x, y, 5, 0, Math.PI * 2);
        this.ctx.fillStyle = '#1e293b';
        this.ctx.fill();
        this.ctx.beginPath();
        this.ctx.arc(x, y, 4, 0, Math.PI * 2);
        this.ctx.fillStyle = color || '#64748b';
        this.ctx.fill();
        this.ctx.restore();
    }

    lightenColor(color, percent) {
        // Simple color lightening
        const num = parseInt(color.replace('#', ''), 16);
        const amt = Math.round(2.55 * percent);
        const R = Math.min(255, (num >> 16) + amt);
        const G = Math.min(255, ((num >> 8) & 0x00FF) + amt);
        const B = Math.min(255, (num & 0x0000FF) + amt);
        return '#' + (0x1000000 + R * 0x10000 + G * 0x100 + B).toString(16).slice(1);
    }

    drawLifecycleNode(node) {
        const { x, y, label, color, role } = node;
        this.ctx.save();
        this.ctx.fillStyle = color || '#3b82f6';
        this.ctx.beginPath();
        this.ctx.roundRect(x - 50, y - 20, 100, 40, 8);
        this.ctx.fill();
        this.ctx.strokeStyle = '#fff';
        this.ctx.lineWidth = 1;
        this.ctx.stroke();

        // Draw role indicator for lifecycle states
        if (role && this.showRoles && this.roles[role]) {
            const roleColor = this.roles[role].color;
            this.ctx.beginPath();
            this.ctx.arc(x + 45, y - 15, 6, 0, Math.PI * 2);
            this.ctx.fillStyle = '#1e293b';
            this.ctx.fill();
            this.ctx.beginPath();
            this.ctx.arc(x + 45, y - 15, 5, 0, Math.PI * 2);
            this.ctx.fillStyle = roleColor;
            this.ctx.fill();
        }

        this.drawLabel(x, y, label);
        this.ctx.restore();
    }

    drawActor(node) {
        const { x, y, label } = node;
        const ctx = this.ctx;
        ctx.save();
        ctx.strokeStyle = '#a855f7';
        ctx.fillStyle = '#a855f7';
        ctx.lineWidth = 2;

        ctx.beginPath(); ctx.arc(x, y - 30, 12, 0, Math.PI * 2); ctx.stroke(); // Head
        ctx.beginPath(); ctx.moveTo(x, y - 18); ctx.lineTo(x, y + 10); ctx.stroke(); // Body
        ctx.beginPath(); ctx.moveTo(x - 20, y - 5); ctx.lineTo(x + 20, y - 5); ctx.stroke(); // Arms
        ctx.beginPath(); ctx.moveTo(x, y + 10); ctx.lineTo(x - 15, y + 35); ctx.moveTo(x, y + 10); ctx.lineTo(x + 15, y + 35); ctx.stroke(); // Legs

        this.drawLabel(x, y + 55, label);
        ctx.restore();
    }

    drawUseCaseNode(node) {
        const { x, y, label } = node;
        this.ctx.save();
        this.ctx.beginPath();
        this.ctx.ellipse(x, y, 75, 22, 0, 0, Math.PI * 2);
        this.ctx.fillStyle = 'rgba(59, 130, 246, 0.3)';
        this.ctx.fill();
        this.ctx.strokeStyle = '#3b82f6';
        this.ctx.lineWidth = 2;
        this.ctx.stroke();
        this.drawLabel(x, y, label);
        this.ctx.restore();
    }

    drawLabel(x, y, label) {
        if (!label) return;

        this.ctx.fillStyle = '#fff';
        this.ctx.font = '11px Segoe UI'; // Slightly smaller font to fit better
        this.ctx.textAlign = 'center';
        this.ctx.textBaseline = 'middle';

        const words = label.split(' ');
        const lineHeight = 14;
        const totalHeight = words.length * lineHeight;
        const startY = y - (totalHeight / 2) + (lineHeight / 2);

        words.forEach((word, index) => {
            this.ctx.fillText(word, x, startY + (index * lineHeight));
        });
    }

    drawConnection(from, to, label) {
        this.ctx.save();
        this.ctx.strokeStyle = '#64748b';
        this.ctx.lineWidth = 2;
        this.ctx.beginPath();
        this.ctx.moveTo(from.x, from.y + 20); // Rough offset
        this.ctx.lineTo(to.x, to.y - 20);
        this.ctx.stroke();

        // Arrow
        const angle = Math.atan2(to.y - 20 - (from.y + 20), to.x - from.x);
        this.drawArrowHead(to.x, to.y - 20, angle);

        if (label) this.drawLabel((from.x + to.x) / 2 + 20, (from.y + to.y) / 2, label);
        this.ctx.restore();
    }

    drawCurvedArrow(from, to, label) {
        this.ctx.save();
        this.ctx.strokeStyle = '#64748b';
        this.ctx.lineWidth = 1.5;

        const cp1x = from.x + (to.x - from.x) / 2;
        const cp1y = from.y - 40; // Curve up

        this.ctx.beginPath();
        this.ctx.moveTo(from.x, from.y - 20);
        this.ctx.quadraticCurveTo(cp1x, cp1y, to.x, to.y - 20);
        this.ctx.stroke();

        // Arrow at 'to' side? Complex with quadratic, skip precise arrowhead for now
        if (label) this.drawLabel(cp1x, cp1y - 10, label);
        this.ctx.restore();
    }

    drawAssociation(from, to) {
        this.ctx.save();
        this.ctx.strokeStyle = '#64748b';
        this.ctx.setLineDash([5, 5]);
        this.ctx.beginPath();
        this.ctx.moveTo(from.x + 20, from.y);
        this.ctx.lineTo(to.x - 70, to.y);
        this.ctx.stroke();
        this.ctx.restore();
    }

    drawArrowHead(x, y, angle) {
        this.ctx.beginPath();
        this.ctx.moveTo(x, y);
        this.ctx.lineTo(x - 10 * Math.cos(angle - 0.5), y - 10 * Math.sin(angle - 0.5));
        this.ctx.lineTo(x - 10 * Math.cos(angle + 0.5), y - 10 * Math.sin(angle + 0.5));
        this.ctx.closePath();
        this.ctx.fillStyle = '#64748b';
        this.ctx.fill();
    }
}

// Export for use
window.DiagramRenderer = DiagramRenderer;
