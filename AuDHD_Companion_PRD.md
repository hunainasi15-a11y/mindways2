# Product Requirements Document (PRD)

## AuDHD Work Companion — Executive Function Support System for Late-Diagnosed Adults

**Version:** 1.0  
**Date:** 2026-05-31  
**Target User:** Adults (30–60+) with late-diagnosed AuDHD (Autism + ADHD) experiencing task paralysis and initiation difficulties  
**Platform:** Progressive Web App (PWA) — Mobile-first, offline-capable  
**Data Storage:** Browser-based (localStorage + IndexedDB) with optional export/import

---

## 1. Executive Summary

### 1.1 Problem Statement
Late-diagnosed AuDHD adults experience a unique form of executive dysfunction where **ADHD dopamine deficiency** collides with **autistic uncertainty intolerance** and **perfectionism**, creating a "task paralysis" state that generic productivity apps fail to address. Existing tools (Notion, Todoist, Forest) assume neurotypical executive function patterns and often increase shame rather than support.

### 1.2 Research Foundation
The app is grounded in peer-reviewed and clinical research on adult neurodivergence:

| Research Area | Key Findings | App Response |
|---------------|------------|--------------|
| **Executive Function in AuDHD** (Barkley, 2020; Livingston et al., 2020) | Task initiation deficits are neurological, not motivational. "Intention–action gap" is measurable. | Removes motivation-based framing; uses external scaffolding and body doubling |
| **Body Doubling** (Parker, 2021; ADHD community studies) | Presence of another person increases task initiation by 200–300% in ADHD adults. | Integrated body-double launcher with one-tap access |
| **Task Chunking vs. Laddering** (Soler-Gutiérrez et al., 2023) | Equal-sized chunks fail for autistic brains; "ridiculously small first steps" reduce amygdala threat response. | "Task Laddering" with insultingly small first steps (30 sec–2 min) |
| **Uncertainty Intolerance in Autism** (Boulter et al., 2014) | Ambiguity triggers avoidance. Explicit step-mapping reduces prefrontal cortex load. | Pre-work "Task Mapping" with visual step breakdown |
| **Sensory Regulation & Focus** (Ashburner et al., 2008) | Sensory prep *before* cognitive work reduces meltdown probability. | 5-Minute Pre-Game with sensory checklist |
| **Time Blindness** (Barkley, 2012) | ADHD adults lack internal time sense; external visual timers are essential. | Visual countdown timers, session logging |
| **Self-Compassion & Shame** (Devon Price, 2022; Nerenberg, 2020) | Late diagnosis creates internalized shame; productivity tools must be shame-free. | Affirmation system, "good enough" framing, 50% permission protocol |
| **Dopamine Regulation** (Volkow et al., 2009) | ADHD brains initiate via interest, not importance. | Dopamine Menu — interest-based task priming |
| **Parking on a Hill** (KC Davis, 2022) | Ending sessions by setting up the next micro-step reduces next-day initiation cost. | "Park Tomorrow's Step" feature with daily reminders |

### 1.3 Product Vision
> *A shame-free, research-informed executive function scaffold that externalizes the cognitive load of task initiation, initiation, and completion for AuDHD adults — replacing willpower with infrastructure.*

---

## 2. User Personas

### Primary Persona: "The Masked Survivor"
- **Age:** 40–55, late-diagnosed (within last 0–5 years)
- **Work:** Professional, creative, self-employed, or caregiving
- **Pain Points:** 
  - Spends 2+ hours "warming up" to a 30-minute task
  - Has 47 open browser tabs of "research" but never starts
  - Feels "lazy" despite working harder than peers to produce less
  - Experiences "autistic burnout" cycles from masking + ADHD overwhelm
- **Tech Comfort:** Moderate; uses phone constantly but finds most apps overwhelming
- **Goals:** Start tasks without emotional collapse, finish *something* daily, reduce shame

### Secondary Persona: "The Recently Unmasked"
- **Age:** 30–40, diagnosed 6–24 months ago
- **Work:** Navigating accommodations, possibly in therapy
- **Pain Points:**
  - Re-learning productivity after decades of maladaptive coping
  - Overwhelmed by "ADHD advice" that ignores autistic needs
  - Needs validation and gentle structure, not rigid systems
- **Goals:** Build sustainable routines, understand their own neurology, find community

---

## 3. Functional Requirements

### 3.1 Architecture Overview
The app moves from a **single-page demo** to a **multi-view Single Page Application (SPA)** with persistent data, time-based tracking, and real functionality across 6 core modules:

```
┌─────────────────────────────────────────────────────────────┐
│  AuDHD Work Companion — System Architecture                  │
├─────────────────────────────────────────────────────────────┤
│  PRESENTATION LAYER                                         │
│  ├── Home Dashboard (Today View + Affirmations + Progress)  │
│  ├── Pre-Game Module (Timer + Checklist + Sensory Prep)     │
│  ├── Task Ladder Module (Builder + Templates + Tracker)       │
│  ├── Body Double Module (Launcher + Quick Contacts)           │
│  ├── Emergency Unstick Module (Protocol + Breathing)          │
│  ├── Vibe Check Module (Mood Log + Win Tracker + Parking)     │
│  └── Settings & Data Module (Export/Import/Help)             │
├─────────────────────────────────────────────────────────────┤
│  DATA LAYER (IndexedDB + localStorage)                        │
│  ├── Session Store (date, time, duration, module used)       │
│  ├── Task Ladder Store (ladders, steps, completion status)   │
│  ├── Mood Log Store (timestamp, mood, notes)                  │
│  ├── Win Store (daily wins, timestamp, category)              │
│  ├── Parking Store (tomorrow's steps, scheduled reminders)     │
│  └── Settings Store (preferences, affirmation history)        │
└─────────────────────────────────────────────────────────────┘
```

### 3.2 Module 1: Home Dashboard

**FR-1.1 — Today View**  
- Display current date and time-aware greeting (morning/afternoon/evening)
- Show today's session count (how many times user opened the app)
- Show today's completion rate (tasks started vs. planned)
- Display a rotating research-grounded affirmation (tap to cycle, 10+ affirmations)

**FR-1.2 — Weekly Progress Visualization**  
- Bar chart showing last 7 days of "wins" (completed micro-steps)
- Color-coded: teal = good days, yellow = moderate, coral = rest days
- No "failure" framing — rest days are valid and visually normalized

**FR-1.3 — Dopamine Menu**  
- 4-tap quick-prime system: Appetizer (tool), Entrée (sound), Side (sensory), Dessert (reward)
- Tapping logs the dopamine prime to today's session data
- Help text: "Your brain needs interest to initiate. This isn't cheating — it's neurology."

**FR-1.4 — Quick Actions**  
- One-tap launch to: Pre-Game, Emergency Unstick, Build Ladder
- Context-aware: if user hasn't logged a win today, prioritize "Start Pre-Game"

### 3.3 Module 2: Pre-Game System

**FR-2.1 — 5-Minute Countdown Timer**  
- Visual circular countdown (not just numbers)
- Customizable duration (3, 5, 10 minutes)
- Audio: gentle chime at start, soft bell at end (no alarm sounds)
- Logs: start time, end time, whether user proceeded to task

**FR-2.2 — Sensory Regulation Checklist**  
- 6-item checklist with persistent state:
  1. Beverage ready
  2. Noise-canceling headphones on
  3. Stim toy within reach
  4. One micro-step written down
  5. Phone on Do Not Disturb
  6. Self-compassion statement spoken
- Each item has a **ⓘ help tooltip** explaining the research basis
- Completion percentage shown visually

**FR-2.3 — Breathing Reset**  
- 1-minute guided breathing animation (4-second cycles: in/hold/out/hold)
- Visual: expanding/contracting circle with color gradient
- Logs: usage timestamp, pre/post self-report (optional 1–5 calm rating)

**FR-2.4 — Session Initiation Log**  
- When user completes Pre-Game and taps "I'm Ready", log:
  - Timestamp, day of week, time of day
  - Which checklist items were completed
  - Whether they proceeded to a Task Ladder

### 3.4 Module 3: Task Ladder Builder

**FR-3.1 — Ladder Creation**  
- Input: Big scary task (free text)
- Auto-generate micro-steps using templates (see FR-3.3)
- Manual step addition with time estimates (default 2 min)
- Drag-to-reorder steps
- Each step has: text, time estimate, completion checkbox

**FR-3.2 — Ladder Execution**  
- During execution, show ONLY the current step (reduce overwhelm)
- "Done" button triggers micro-celebration (confetti + affirmation)
- "Stop Here" button at any step — validates partial completion
- Logs: ladder ID, start time, end time, steps completed, where stopped

**FR-3.3 — Research-Informed Templates**  
Built-in templates for common AuDHD paralysis triggers:
- **Send Email** (6 steps, 30 sec–2 min each)
- **Do Dishes** (5 steps, 10 sec–1 min each)
- **Write Report/Document** (5 steps, 30 sec–5 min each)
- **Exercise/Movement** (5 steps, 1–2 min each)
- **Make Phone Call** (5 steps, 10 sec–2 min each)
- **Clean One Room** (5 steps, 2–5 min each)
- **Pay Bills/Admin** (5 steps, 1–3 min each)
- **Creative Project** (5 steps, 5–10 min each)

**FR-3.4 — Ladder History**  
- View all past ladders with completion status
- Filter by: completed, partial, abandoned
- Re-run previous ladders with one tap
- Weekly summary: "You completed 12 micro-steps this week"

### 3.5 Module 4: Body Double Launcher

**FR-4.1 — Service Directory**  
- One-tap launch cards for:
  - Focusmate (free tier: 3 sessions/day)
  - Flow Club
  - Study Together Discord
  - YouTube "Study With Me" livestreams
  - Local library/café finder (optional geolocation)
- Each card shows: name, description, cost, best-for tag

**FR-4.2 — Quick Contact Templates**  
- Pre-written texts for accountability partners:
  - "Silent work call — no talking needed, just presence"
  - "I need to start [TASK]. Can you check on me in 10 min?"
  - "I'm stuck. Send me a random GIF to break the freeze?"
- One-tap copy to clipboard
- Optional: native share API (WhatsApp, SMS, etc.)

**FR-4.3 — Body Double Session Log**  
- Log: date, time, service used, task attempted, perceived effectiveness (1–5)
- Over time, show which body-double methods work best for this user

### 3.6 Module 5: Emergency Unstick Protocol

**FR-5.1 — 5-4-3-2-1 Grounding**  
- Animated countdown (5, 4, 3, 2, 1)
- At 0: prompt to physically move (stand, walk, stretch)
- Logs: trigger time, resolution method, post-unstick action

**FR-5.2 — Protocol Library**  
- 6 evidence-based unstick methods:
  1. **Location Change** — couch → desk → café
  2. **2-Minute Momentum** — do one absurdly small task
  3. **50% Permission** — "good enough" version is valid
  4. **Voice Dump** — 2-min voice memo, no writing
  5. **Breathing Reset** — 1-min guided breathing
  6. **Dopamine Bridge** — switch to interesting sub-task for 5 min
- Each has **ⓘ help text** citing the research/principle
- User can reorder protocols by personal effectiveness

**FR-5.3 — Paralysis Log**  
- Optional: log what triggered the paralysis (dropdown: ambiguity, perfectionism, sensory overload, emotional exhaustion, time pressure, unclear steps)
- Over time, generate insights: "You often get stuck on unclear first steps. Try Task Mapping first."

### 3.7 Module 6: Vibe Check & Daily Log

**FR-6.1 — Mood Logging**  
- 6-tap mood selector: Energized, Okay, Tired, Overwhelmed, Anxious, Numb
- Optional: 1-sentence note (voice-to-text supported)
- Timestamped, viewable as calendar heatmap

**FR-6.2 — Win Tracker**  
- 5 daily win categories (check all that apply):
  1. Showed up for myself
  2. Did one tiny thing
  3. Rested without guilt
  4. Practiced self-compassion
  5. Used a tool/strategy
- Progress bar with confetti at 5/5
- Weekly summary: "You had 4/7 days with at least 3 wins"

**FR-6.3 — Parking on a Hill**  
- Input: tomorrow's first micro-step (max 30 seconds of work)
- Saves with timestamp
- Next-day reminder: "You parked this step for yourself: [text]"
- Weekly review: "You parked 5 steps this week. 3 helped you start."

**FR-6.4 — Data Visualization**  
- Calendar view: color-coded days by mood + win count
- Trend line: wins over last 30 days
- Correlation insight: "On days you did Pre-Game, you averaged 2.3 more wins"

### 3.8 Module 7: Settings, Help & Data

**FR-7.1 — Contextual Help System**  
- Every feature has a **ⓘ icon** that opens a bottom-sheet explaining:
  - What this feature does
  - Why it works (research citation)
  - How to use it
  - When to use it
- Example help text for Task Laddering:  
  *"Research (Soler-Gutiérrez et al., 2023) shows that autistic brains experience ambiguity as threat. Breaking tasks into insultingly small steps (30 seconds) bypasses the amygdala freeze response. The first step should be so small it feels ridiculous. That's the point."*

**FR-7.2 — Research Library**  
- In-app reference section with:
  - Key papers summarized in plain language
  - Book recommendations (Divergent Mind, Unmasking Autism, How to Keep House While Drowning)
  - Community resources (Reddit, Discord, ADHD Adults UK)

**FR-7.3 — Data Export/Import**  
- Export all data as JSON (portable, human-readable)
- Export as CSV (for spreadsheets or sharing with therapists/coaches)
- Import from backup file
- Clear all data with confirmation

**FR-7.4 — Accessibility**  
- Font size: Small / Medium / Large / Extra Large
- Color modes: Warm (default), High Contrast, Dark Mode
- Reduce motion toggle (disables animations for sensory sensitivity)
- Screen reader optimized (ARIA labels on all interactive elements)

---

## 4. Data Model

### 4.1 IndexedDB Schema

```javascript
// Database: audhd_companion_v1

// Store: sessions
{
  id: "uuid",
  date: "2026-05-31",
  time_start: "10:30:00",
  time_end: "10:45:00",
  duration_minutes: 15,
  module: "pre-game|ladder|body-double|emergency|vibe-check",
  completed: true,
  notes: "optional string"
}

// Store: ladders
{
  id: "uuid",
  title: "Send work email",
  created_date: "2026-05-31",
  template_used: "email|custom",
  steps: [
    { id: 1, text: "Open email app", time_estimate: 30, completed: true, completed_at: "10:32:00" },
    { id: 2, text: "Click compose", time_estimate: 10, completed: false, completed_at: null }
  ],
  status: "completed|partial|abandoned",
  stopped_at_step: 2,
  total_time_minutes: 12
}

// Store: mood_logs
{
  id: "uuid",
  timestamp: "2026-05-31T10:30:00",
  date: "2026-05-31",
  mood: "energized|okay|tired|overwhelmed|anxious|numb",
  note: "optional string"
}

// Store: wins
{
  id: "uuid",
  date: "2026-05-31",
  timestamp: "2026-05-31T10:30:00",
  category: "showed_up|tiny_thing|rest|self_compassion|used_tool",
  context: "optional: which task/ladder"
}

// Store: parking
{
  id: "uuid",
  created_date: "2026-05-31",
  target_date: "2026-06-01",
  step_text: "Open laptop and write subject line",
  used: false,
  helpful: null // boolean, asked after completion
}

// Store: unstick_logs
{
  id: "uuid",
  timestamp: "2026-05-31T10:30:00",
  trigger: "ambiguity|perfectionism|sensory|emotional|time_pressure|unclear",
  protocol_used: "countdown|location|momentum|permission|voice|breathing|dopamine",
  resolved: true,
  post_action: "started task|rested|pivoted|still stuck"
}

// Store: settings
{
  key: "user_preferences",
  font_size: "medium|large|xlarge",
  color_mode: "warm|high-contrast|dark",
  reduce_motion: false,
  default_timer_minutes: 5,
  affirmation_index: 0,
  onboarding_complete: true
}
```

### 4.2 Data Retention
- Local storage only (privacy-first, no cloud required)
- Auto-backup prompt weekly: "Export your data?"
- Retain 90 days of detailed logs; aggregate older data into weekly summaries

---

## 5. User Flows

### 5.1 First-Time User (Onboarding)
1. Welcome screen: "This app was built for brains like yours."
2. One-question setup: "What triggers your task paralysis most?" (ambiguity / perfectionism / sensory / emotional)
3. Brief tutorial (3 slides): Pre-Game → Ladder → Vibe Check
4. Prompt to add to home screen
5. Drop into Home Dashboard with first affirmation

### 5.2 Daily Active User (Morning)
1. Open app → sees greeting + today's parked step (if any)
2. Taps "Start Pre-Game" → completes 5-min timer + checklist
3. Taps "Build Ladder" → creates or re-runs ladder
4. Completes 1–3 micro-steps → marks wins in Vibe Check
5. Parks tomorrow's step → closes app

### 5.3 Crisis User (Stuck Right Now)
1. Open app → taps "I'm Stuck" (prominent on Home)
2. Emergency Unstick opens → selects protocol (e.g., 5-4-3-2-1)
3. Follows animated guide → unsticks
4. Optional: logs what worked
5. Redirected to Pre-Game or Ladder

### 5.4 Reflective User (Evening)
1. Open app → Vibe Check tab
2. Logs mood + checks daily wins
3. Views calendar heatmap → sees patterns
4. Reads research library → validates experience
5. Exports weekly data for therapist/coach

---

## 6. UI/UX Requirements

### 6.1 Design Principles (Neurodivergent-First)
1. **Low Cognitive Load** — One primary action per screen. No dashboards with 12 widgets.
2. **Gentle Friction** — Important actions (delete data) require confirmation. Unimportant actions (check a win) are instant.
3. **Shame-Free Language** — No "streaks," "failures," "overdue," or "behind." Use "rest days," "partial wins," "showed up."
4. **Predictable Structure** — Same tab order every time. No surprise UI changes.
5. **Sensory Safety** — Warm colors (no harsh reds for "bad"), optional reduced motion, no sudden sounds.
6. **Externalization** — The app holds the plan so the brain doesn't have to.

### 6.2 Color System
| Token | Hex | Usage |
|-------|-----|-------|
| Background | #FDF6E3 | Main background (warm, low stimulation) |
| Card | #FFFFFF | Content cards |
| Primary | #2A9D8F | Action buttons, success, progress |
| Secondary | #E9C46A | Highlights, dopamine menu, wins |
| Accent | #F4A261 | Warnings, body double, interest |
| Alert | #E76F51 | Emergency (not "bad" — just urgent) |
| Dark | #264653 | Text, headers, dark mode elements |
| Text | #2B2D42 | Body text |
| Rest | #F8EDEB | Rest days, neutral states |

### 6.3 Typography
- **Font:** Nunito (rounded, friendly, dyslexia-friendly weight)
- **Scale:** 14px minimum body, 18px headings, 24px timer display
- **Line height:** 1.6 minimum (readability)
- **Max width:** 600px content (no eye strain from wide lines)

### 6.4 Animation Guidelines
- **Allowed:** Fade-ins (0.3s), gentle scale on tap (0.97), progress bar fills, breathing circle expansion
- **Forbidden:** Shake animations, flashing, parallax, auto-playing carousels, slide-in from bottom (unexpected)
- **Motion reduced mode:** All animations become instant fades

---

## 7. Non-Functional Requirements

### 7.1 Performance
- First paint < 1.5s on 3G
- All interactions respond within 100ms
- Works offline after first load (service worker caches all assets)
- App size < 2MB (excluding optional media)

### 7.2 Privacy & Security
- **Zero cloud dependency.** All data stays in browser.
- No accounts, no passwords, no tracking pixels, no analytics
- Optional: encrypted export using user-provided passphrase
- Data deletion: one-tap "Clear All My Data" with confirmation

### 7.3 Accessibility (WCAG 2.1 AA)
- Color contrast ratios ≥ 4.5:1
- All interactive elements ≥ 44×44px touch targets
- Full keyboard navigation support
- Screen reader announcements for dynamic content (toast messages, timer changes)
- Focus indicators visible and high-contrast

### 7.4 Reliability
- Graceful degradation: if IndexedDB fails, fall back to localStorage
- Auto-save: all inputs save on change (no "save" button needed)
- Recovery: if app crashes during ladder, restore state on reopen

---

## 8. Implementation Phases

### Phase 1: Foundation (Weeks 1–2)
- [ ] Set up PWA shell (service worker, manifest, offline support)
- [ ] Build IndexedDB layer with all stores
- [ ] Implement Home Dashboard with real data persistence
- [ ] Build Pre-Game module (timer + checklist + breathing)
- [ ] Basic help text system (ⓘ tooltips)

### Phase 2: Core Functionality (Weeks 3–4)
- [ ] Task Ladder Builder with templates and execution
- [ ] Body Double Launcher with external links
- [ ] Emergency Unstick with all 6 protocols
- [ ] Vibe Check mood logging + win tracker
- [ ] Parking on a Hill feature

### Phase 3: Intelligence & Polish (Weeks 5–6)
- [ ] Weekly progress charts and calendar heatmap
- [ ] Data export (JSON/CSV)
- [ ] Research library with plain-language summaries
- [ ] Settings panel (accessibility, font size, color modes)
- [ ] Onboarding flow

### Phase 4: Advanced Features (Weeks 7–8)
- [ ] Correlation insights ("On days you Pre-Game, you complete 2x more steps")
- [ ] Custom ladder template creation
- [ ] Voice-to-text input for notes and ladder steps
- [ ] Native share API integration
- [ ] Community resource links

---

## 9. Success Metrics

### 9.1 User Success (What Matters)
- **Task Initiation Rate:** % of Pre-Game sessions that lead to a started ladder
- **Micro-Step Completion:** Average steps completed per ladder (goal: ≥ 2)
- **Session Consistency:** Days per week with ≥ 1 app session (goal: 4+)
- **Unstick Recovery:** % of emergency uses that lead to action within 10 min
- **Self-Reported Shame Reduction:** Optional monthly 1–5 scale: "I feel less shame about my productivity"

### 9.2 Technical Metrics
- App load time < 2s
- Offline functionality score: 100%
- Accessibility audit: WCAG 2.1 AA pass
- Zero data loss incidents

---

## 10. Open Questions

1. **Should we integrate with calendar APIs** (Google/Outlook) to pre-populate "Parked Steps" as reminders? *(Risk: increases complexity, privacy concerns)*
2. **Should we add a "Coach Mode"** where a therapist/coach can view anonymized weekly exports? *(Risk: requires data sharing architecture)*
3. **Should we include medication timing reminders** (if user takes stimulants)? *(Risk: medical advice liability)*
4. **Should we build a community feature** (anonymous peer support)? *(Risk: moderation, safety)*
5. **Should we support multiple languages** from launch? *(Priority: English first, Spanish and German next)*

---

## 11. Appendices

### Appendix A: Research Bibliography
- Barkley, R. A. (2012). *Executive Functions: What They Are, How They Work, and Why They Evolved.* Guilford Press.
- Boulter, C., et al. (2014). "Intolerance of uncertainty as a contributor to fear and anxiety in autism." *Journal of Autism and Developmental Disorders.*
- Davis, K. C. (2022). *How to Keep House While Drowning.* Simon & Schuster.
- Livingston, L. A., et al. (2020). "Development and validation of the Compensatory Autism Behaviors Scale." *Journal of Autism and Developmental Disorders.*
- Nerenberg, J. (2020). *Divergent Mind.* HarperOne.
- Parker, A. (2021). "The efficacy of body doubling for adults with ADHD." *ADHD Attention.*
- Price, D. (2022). *Unmasking Autism.* Harmony Books.
- Soler-Gutiérrez, A. M., et al. (2023). "Task initiation and executive function in autistic adults." *Frontiers in Psychology.*
- Volkow, N. D., et al. (2009). "Evaluating dopamine reward pathway in ADHD." *JAMA.*

### Appendix B: Glossary
- **AuDHD:** Co-occurring Autism and ADHD
- **Body Doubling:** Working alongside another person (in person or virtual) to increase task initiation
- **Executive Function:** Cognitive processes including planning, initiation, working memory, and inhibition
- **Task Laddering:** Breaking tasks into insultingly small sequential steps
- **Parking on a Hill:** Setting up the next micro-step at the end of a session to reduce next-day initiation cost
- **Time Blindness:** Difficulty perceiving the passage of time, common in ADHD
- **Task Paralysis:** Inability to initiate a task despite intention and capacity

---

**End of PRD**
