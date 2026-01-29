/**
 * Diagram Renderer - Parses XML diagrams and renders on canvas with Drag & Drop support
 */
class DiagramRenderer {
    constructor(canvas) {
        this.canvas = canvas;
        this.ctx = canvas.getContext('2d');
        this.nodes = [];
        this.connections = [];
        this.isDragging = false;
        this.dragNode = null;
        this.dragOffset = { x: 0, y: 0 };
        this.currentType = null;

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

        // Detection: Check if it's mxGraph format (draw.io)
        const mxfile = xmlDoc.querySelector('mxfile');
        if (mxfile) {
            this.currentType = 'mxgraph';
            this.parseMxGraph(xmlDoc);
            return;
        }

        // Otherwise use our custom format
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

    /**
     * Parse draw.io / mxGraph format XML
     */
    parseMxGraph(xmlDoc) {
        const cells = xmlDoc.querySelectorAll('mxCell');

        cells.forEach(cell => {
            const id = cell.getAttribute('id');
            const value = cell.getAttribute('value');
            const style = cell.getAttribute('style') || '';
            const geometry = cell.querySelector('mxGeometry');

            // Skip root cells (id=0, id=1)
            if (id === '0' || id === '1') return;

            // Check if it's an edge (connection)
            if (cell.getAttribute('edge') === '1') {
                this.connections.push({
                    from: cell.getAttribute('source'),
                    to: cell.getAttribute('target'),
                    label: value || ''
                });
                return;
            }

            // Check if it's a vertex (node)
            if (geometry && (style.includes('swimlane') || cell.getAttribute('vertex') === '1')) {
                const x = parseFloat(geometry.getAttribute('x')) || 0;
                const y = parseFloat(geometry.getAttribute('y')) || 0;
                const width = parseFloat(geometry.getAttribute('width')) || 100;
                const height = parseFloat(geometry.getAttribute('height')) || 50;

                // Clean up value (remove HTML entities)
                let label = (value || id).replace(/&amp;#xa;/g, ' ').replace(/🔑|🔗/g, '').substring(0, 30);

                // Determine color from style
                let color = '#3b82f6';
                if (style.includes('fillColor=#d5e8d4')) color = '#22c55e';
                else if (style.includes('fillColor=#dae8fc')) color = '#3b82f6';
                else if (style.includes('fillColor=#fff2cc')) color = '#eab308';
                else if (style.includes('fillColor=#e1d5e7')) color = '#a855f7';
                else if (style.includes('fillColor=#ffe6cc')) color = '#f97316';
                else if (style.includes('fillColor=#f8cecc')) color = '#ef4444';
                else if (style.includes('fillColor=#b1ddf0')) color = '#06b6d4';

                this.nodes.push({
                    id,
                    type: style.includes('swimlane') ? 'entity' : 'attribute',
                    x: x + width / 2,  // Center coordinates
                    y: y + height / 2,
                    label: label.trim(),
                    width,
                    height,
                    color
                });
            }
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
                case 'mxgraph':
                    this.drawMxGraphNode(node);
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
                width: 100,
                height: 40
            });
        });
        xmlDoc.querySelectorAll('transitions > transition').forEach(t => {
            this.connections.push({
                from: t.getAttribute('from'),
                to: t.getAttribute('to'),
                label: t.getAttribute('label') || ''
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
            this.ctx.fillText('Sistema', 305, 20);
            this.ctx.restore();
        }

        // Draw title for mxGraph diagrams
        if (this.currentType === 'mxgraph') {
            this.ctx.fillStyle = '#94a3b8';
            this.ctx.font = '12px Segoe UI';
            this.ctx.textAlign = 'left';
            this.ctx.fillText('Diagrama ER (Escalado)', 10, 20);
        }
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
        const { type, x, y, label } = node;
        this.ctx.save();

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
                this.ctx.fillStyle = '#3b82f6';
                this.ctx.beginPath();
                this.ctx.roundRect(x - 70, y - 20, 140, 40, 8);
                this.ctx.fill();
                this.ctx.strokeStyle = '#60a5fa';
                this.ctx.lineWidth = 2;
                this.ctx.stroke();
                break;
            case 'decision':
                this.ctx.beginPath();
                this.ctx.moveTo(x, y - 30);
                this.ctx.lineTo(x + 50, y);
                this.ctx.lineTo(x, y + 30);
                this.ctx.lineTo(x - 50, y);
                this.ctx.closePath();
                this.ctx.fillStyle = '#eab308';
                this.ctx.fill();
                this.ctx.strokeStyle = '#fbbf24';
                this.ctx.lineWidth = 2;
                this.ctx.stroke();
                break;
        }

        this.drawLabel(x, y, label);
        this.ctx.restore();
    }

    drawLifecycleNode(node) {
        const { x, y, label, color } = node;
        this.ctx.save();
        this.ctx.fillStyle = color || '#3b82f6';
        this.ctx.beginPath();
        this.ctx.roundRect(x - 50, y - 20, 100, 40, 8);
        this.ctx.fill();
        this.ctx.strokeStyle = '#fff';
        this.ctx.lineWidth = 1;
        this.ctx.stroke();

        this.drawLabel(x, y, label);
        this.ctx.restore();
    }

    /**
     * Draw mxGraph/draw.io entity node with scaling
     */
    drawMxGraphNode(node) {
        const { x, y, label, width, height, color, type } = node;

        // Apply scaling for large diagrams
        const scale = this.scale || 0.35;
        const sx = x * scale;
        const sy = y * scale;
        const sw = width * scale;
        const sh = Math.min(height * scale, 30); // Cap height for readability

        this.ctx.save();

        // Only draw swimlane (entity) nodes, skip attribute detail cells
        if (type === 'entity') {
            this.ctx.fillStyle = color || '#3b82f6';
            this.ctx.beginPath();
            this.ctx.roundRect(sx - sw / 2, sy - sh / 2, sw, sh, 4);
            this.ctx.fill();
            this.ctx.strokeStyle = '#fff';
            this.ctx.lineWidth = 1;
            this.ctx.stroke();

            // Label
            this.ctx.fillStyle = '#fff';
            this.ctx.font = '10px Segoe UI';
            this.ctx.textAlign = 'center';
            this.ctx.textBaseline = 'middle';
            this.ctx.fillText(label, sx, sy);
        }

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
