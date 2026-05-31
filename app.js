import { addRecord, getTodaySessions } from './db.js';

// State
let currentView = 'dashboard';
let timerInterval = null;
let timeLeft = 300; // 5 min default

// DOM Elements
const appView = document.getElementById('app-view');
const greetingEl = document.getElementById('greeting');
const navBtns = document.querySelectorAll('.nav-btn');

// Init
document.addEventListener('DOMContentLoaded', async () => {
  setGreeting();
  setupNavigation();
  renderView('dashboard');
});

function setGreeting() {
  const hour = new Date().getHours();
  let msg = hour < 12 ? 'Good morning' : hour < 18 ? 'Good afternoon' : 'Good evening';
  const affirmations = [
    "Task initiation is neurological, not motivational. You're not broken.",
    "Starting small bypasses the amygdala threat response.",
    "Rest days are valid. Your brain needs them.",
    "Interest > Importance for ADHD brains. That's okay."
  ];
  greetingEl.innerHTML = `${msg}. <span style="display:block;margin-top:0.5rem;font-style:italic;color:var(--accent)">"${affirmations[Math.floor(Math.random()*affirmations.length)]}"</span>`;
}

function setupNavigation() {
  navBtns.forEach(btn => {
    btn.addEventListener('click', () => {
      currentView = btn.dataset.view;
      navBtns.forEach(b => b.classList.remove('active'));
      btn.classList.add('active');
      renderView(currentView);
    });
  });
}

async function renderView(view) {
  appView.innerHTML = '';
  const sessions = await getTodaySessions();

  if (view === 'dashboard') {
    appView.innerHTML = `
      <div class="card">
        <h2>📊 Today's Progress</h2>
        <p>Sessions started: <strong>${sessions.length}</strong></p>
        <p>Mood: <em>Track in Vibe Check</em></p>
      </div>
      <div class="card">
        <h3>🍽️ Dopamine Menu</h3>
        <p>Your brain needs interest to initiate. This isn't cheating — it's neurology.</p>
        <div style="display:grid;grid-template-columns:1fr 1fr;gap:0.5rem;margin:1rem 0">
          <button class="btn secondary" onclick="logDopamine('tool')">🛠️ Tool</button>
          <button class="btn secondary" onclick="logDopamine('sound')">🎵 Sound</button>
          <button class="btn secondary" onclick="logDopamine('sensory')">👓 Sensory</button>
          <button class="btn secondary" onclick="logDopamine('reward')">🍰 Reward</button>
        </div>
      </div>
      <button class="btn alert" onclick="window.location.href='#unstick'">🆘 I'm Stuck Right Now</button>
    `;
  } else if (view === 'pregame') {
    appView.innerHTML = `
      <div class="card">
        <h2>🧘 5-Min Pre-Game</h2>
        <div class="timer-circle" style="--progress: 0%">
          <div class="timer-inner" id="timer-display">5:00</div>
        </div>
        <div style="text-align:center">
          <button class="btn" id="start-timer">Start Pre-Game</button>
          <button class="btn secondary" id="reset-timer" style="margin-left:0.5rem;width:auto">Reset</button>
        </div>
      </div>
      <div class="card">
        <h3>🔍 Sensory Prep Checklist</h3>
        <ul class="checklist" id="checklist">
          <li><input type="checkbox"> Beverage ready</li>
          <li><input type="checkbox"> Noise-canceling headphones on</li>
          <li><input type="checkbox"> Stim toy within reach</li>
          <li><input type="checkbox"> One micro-step written down</li>
          <li><input type="checkbox"> Phone on Do Not Disturb</li>
          <li><input type="checkbox"> Self-compassion statement spoken</li>
        </ul>
        <button class="btn" id="ready-btn">✅ I'm Ready to Start</button>
      </div>
    `;
    initTimer();
  } else if (view === 'ladder') {
    appView.innerHTML = `<div class="card"><h2>🪜 Task Ladder</h2><p>Coming in Phase 2: Builder + Templates + Execution</p></div>`;
  } else if (view === 'unstick') {
    appView.innerHTML = `<div class="card"><h2>🆘 Emergency Unstick</h2><p>Coming in Phase 2: 5-4-3-2-1, Location Change, 50% Permission</p></div>`;
  } else {
    appView.innerHTML = `<div class="card"><h2>⚙️ Settings</h2><p>Coming in Phase 3: Accessibility, Export/Import, Help</p></div>`;
  }
}

// Timer Logic
function initTimer() {
  const display = document.getElementById('timer-display');
  const startBtn = document.getElementById('start-timer');
  const resetBtn = document.getElementById('reset-timer');
  const readyBtn = document.getElementById('ready-btn');
  let timeLeft = 300;

  startBtn?.addEventListener('click', () => {
    if (timerInterval) return;
    timerInterval = setInterval(() => {
      timeLeft--;
      const mins = Math.floor(timeLeft / 60).toString().padStart(2, '0');
      const secs = (timeLeft % 60).toString().padStart(2, '0');
      display.textContent = `${mins}:${secs}`;
      const progress = ((300 - timeLeft) / 300) * 100;
      document.querySelector('.timer-circle').style.setProperty('--progress', `${progress}%`);
      if (timeLeft <= 0) {
        clearInterval(timerInterval);
        new Audio('data:audio/wav;base64,UklGRl9vT19teleWQVZm...').play().catch(()=>{}); // Replace with actual gentle chime
        alert('Pre-Game complete. How are you feeling?');
      }
    }, 1000);
  });

  resetBtn?.addEventListener('click', () => {
    clearInterval(timerInterval); timerInterval = null; timeLeft = 300;
    display.textContent = '5:00';
    document.querySelector('.timer-circle').style.setProperty('--progress', '0%');
  });

  readyBtn?.addEventListener('click', async () => {
    clearInterval(timerInterval);
    const checks = Array.from(document.querySelectorAll('#checklist input')).filter(i => i.checked).length;
    await addRecord('sessions', {
      id: crypto.randomUUID(),
      date: new Date().toISOString().split('T')[0],
      time_start: new Date().toTimeString().split(' ')[0],
      duration_minutes: (300 - timeLeft) / 60,
      module: 'pre-game',
      completed: checks >= 3,
      notes: `Checklist: ${checks}/6`
    });
    alert('Logged! Proceed to your task or take a gentle break.');
  });
}

window.logDopamine = async (type) => {
  await addRecord('sessions', { id: crypto.randomUUID(), date: new Date().toISOString().split('T')[0], time_start: new Date().toTimeString().split(' ')[0], module: 'dopamine-menu', completed: true, notes: `Primed with: ${type}` });
  alert(`Dopamine prime logged: ${type}. Your brain is warming up.`);
};