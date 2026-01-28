# .agent - Agent Subsystem

This directory contains all agent-related files, keeping the project root clean and organized.

## Directory Structure

```
.agent/
├── skills/           # Agent capabilities and specialized knowledge
│   ├── env-assurance/    # Environment & connectivity verification
│   └── sql-to-laravel/   # SQL to Laravel transformation
├── artifacts/        # Conversation history and generated documents
│   └── <conversation-id>/  # Per-conversation artifacts
│       ├── task.md
│       ├── implementation_plan.md
│       └── walkthrough.md
├── context/          # Project context and state files
├── docs/             # Agent-generated documentation
└── workflows/        # Automation workflows (.md files)
```

## Purpose

| Directory | Purpose |
|-----------|---------|
| `skills/` | Specialized instructions extending agent capabilities |
| `artifacts/` | Historical conversation artifacts (plans, tasks, walkthroughs) |
| `context/` | Project state, memory, and contextual information |
| `docs/` | Generated documentation and reports |
| `workflows/` | Reusable automation procedures |

## Guidelines

1. **Keep project root clean** - All agent-related files go in `.agent/`
2. **Skills are persistent** - Define once, use across conversations
3. **Artifacts are historical** - Reference for past decisions
4. **Workflows are reusable** - Define common procedures once

## Git Integration

Add to `.gitignore` if you don't want to version agent state:
```
.agent/artifacts/
.agent/context/
```

Keep versioned:
```
.agent/skills/       # Valuable knowledge base
.agent/workflows/    # Reusable procedures
.agent/docs/         # Important documentation
```
