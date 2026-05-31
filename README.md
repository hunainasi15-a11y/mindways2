# mindways2 — AuDHD Work Companion

An app to help navigate the neurodivergence amusement and hell.

## Overview

**AuDHD Work Companion** is a shame-free, research-informed executive function scaffold designed for late-diagnosed adults (30–60+) with AuDHD (Autism + ADHD). It replaces willpower with infrastructure, helping users overcome task paralysis through external cognitive scaffolding.

**Platform:** Progressive Web App (PWA) — Mobile-first, offline-capable  
**Data Storage:** Browser-based (localStorage + IndexedDB) with optional export/import

## The Problem

Late-diagnosed AuDHD adults experience a unique form of executive dysfunction where **ADHD dopamine deficiency** collides with **autistic uncertainty intolerance** and **perfectionism**, creating a "task paralysis" state that generic productivity apps fail to address.

## Core Features

### 🎯 Home Dashboard
- Time-aware greetings and daily affirmations
- Weekly progress visualization (no failure framing)
- Dopamine Menu for interest-based task priming
- Quick actions to core modules

### 🧘 Pre-Game System
- 5-minute visual countdown timer
- Sensory regulation checklist (beverages, headphones, stim toys, etc.)
- 1-minute guided breathing reset
- Research-backed preparation before cognitive work

### 🪜 Task Ladder Builder
- Break big scary tasks into insultingly small first steps (30 sec–2 min)
- Templates for common paralysis triggers (emails, dishes, reports, calls)
- Shows only current step to reduce overwhelm
- Validates partial completion — "Stop Here" is okay

### 👥 Body Double Launcher
- One-tap access to Focusmate, Flow Club, Study Together Discord
- Pre-written text templates for accountability partners
- Session effectiveness tracking

### 🚨 Emergency Unstick Protocol
- 5-4-3-2-1 grounding countdown
- 6 evidence-based unstick methods (location change, 2-min momentum, 50% permission, voice dump, breathing, dopamine bridge)
- Paralysis trigger logging for pattern insights

### 📊 Vibe Check & Daily Log
- Mood logging (6 states: Energized, Okay, Tired, Overwhelmed, Anxious, Numb)
- Win tracker (5 categories: showed up, tiny thing, rested, self-compassion, used tool)
- "Parking on a Hill" — set up tomorrow's micro-step today
- Calendar heatmap with mood + win correlations

### ⚙️ Settings & Accessibility
- Contextual help system with research citations
- Font size options (Small → Extra Large)
- Color modes (Warm, High Contrast, Dark)
- Reduce motion toggle for sensory sensitivity
- Data export/import (JSON & CSV)

## Research Foundation

The app is grounded in peer-reviewed research on adult neurodivergence:

| Research Area | Key Finding | App Response |
|---------------|-------------|--------------|
| Executive Function (Barkley, 2020) | Task initiation deficits are neurological | External scaffolding, not motivation |
| Body Doubling (Parker, 2021) | Presence increases initiation by 200–300% | Integrated body-double launcher |
| Task Chunking (Soler-Gutiérrez et al., 2023) | Small first steps reduce amygdala threat | "Insultingly small" 30-sec steps |
| Uncertainty Intolerance (Boulter et al., 2014) | Ambiguity triggers avoidance | Visual step mapping |
| Self-Compassion (Devon Price, 2022) | Late diagnosis creates shame | Affirmation system, "good enough" framing |
| Time Blindness (Barkley, 2012) | ADHD lacks internal time sense | Visual countdown timers |

## Getting Started

```bash
# Clone the repository
git clone <repository-url>
cd mindways2

# Open in browser (PWA — no build required for basic version)
open index.html
```

For development with live reload:

```bash
# Using Python
python -m http.server 8000

# Or using Node.js
npx serve .
```

Then navigate to `http://localhost:8000`

## Data & Privacy

- **Local-first:** All data stored in browser (IndexedDB + localStorage)
- **No cloud required:** Your data never leaves your device
- **Portable:** Export/import as JSON or CSV
- **Auto-backup:** Weekly prompts to export your data

## Target Users

### Primary: "The Masked Survivor"
- Age 40–55, late-diagnosed (within last 0–5 years)
- Spends 2+ hours "warming up" to 30-minute tasks
- Feels "lazy" despite working harder than peers

### Secondary: "The Recently Unmasked"
- Age 30–40, diagnosed 6–24 months ago
- Re-learning productivity after decades of maladaptive coping
- Needs validation and gentle structure

## Philosophy

> *"Your brain needs interest to initiate. This isn't cheating — it's neurology."*

This app rejects:
- ❌ Motivation-based framing
- ❌ Rigid productivity systems
- ❌ Shame and guilt tactics
- ❌ One-size-fits-all advice

This app embraces:
- ✅ Neurological reality of executive dysfunction
- ✅ External scaffolding over willpower
- ✅ "Good enough" and partial completion
- ✅ Research-informed, community-tested strategies

## Recommended Resources

### Books
- *Divergent Mind* by Jenara Nerenberg
- *Unmasking Autism* by Devon Price
- *How to Keep House While Drowning* by KC Davis

### Communities
- r/AuDHD (Reddit)
- ADHD Adults UK
- Neurodivergent Discord servers

## License

[Add your license here]

## Contributing

Contributions welcome! Please read our contributing guidelines before submitting PRs.

---

**Built for brains like ours.** 🧠💚
