<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>AuDHD Companion - DB Layer & Fallback</title>
    <style>
        :root {
            --bg: #FDF6E3;
            --card: #FFFFFF;
            --primary: #2A9D8F;
            --secondary: #E9C46A;
            --alert: #E76F51;
            --text: #2B2D42;
            --dark: #264653;
            --rest: #F8EDEB;
        }
        body {
            font-family: 'Nunito', system-ui, sans-serif;
            background-color: var(--bg);
            color: var(--text);
            margin: 0;
            padding: 20px;
            display: flex;
            justify-content: center;
            min-height: 100vh;
        }
        .container {
            width: 100%;
            max-width: 800px;
        }
        h1 { font-size: 24px; color: var(--dark); margin-bottom: 5px; }
        .subtitle { font-size: 14px; color: var(--dark); opacity: 0.8; margin-bottom: 20px; }
        .card {
            background: var(--card);
            border-radius: 12px;
            padding: 20px;
            margin-bottom: 20px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.05);
        }
        .status-bar {
            padding: 12px;
            border-radius: 8px;
            background: var(--rest);
            border-left: 5px solid var(--secondary);
            margin-bottom: 15px;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }
        .status-bar.error { border-left-color: var(--alert); background: #ffeae5; }
        .status-bar.success { border-left-color: var(--primary); background: #e8f8f6; }
        .schema-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(220px, 1fr));
            gap: 10px;
        }
        .store-badge {
            background: var(--rest);
            padding: 8px 12px;
            border-radius: 6px;
            font-size: 13px;
            font-family: monospace;
            color: var(--dark);
        }
        .store-badge strong { color: var(--primary); }
        button {
            background: var(--primary);
            color: white;
            border: none;
            padding: 12px 24px;
            border-radius: 8px;
            cursor: pointer;
            font-size: 16px;
            font-weight: bold;
            transition: transform 0.1s, background 0.2s;
        }
        button:hover { background: #21867a; transform: scale(1.02); }
        button:active { transform: scale(0.98); }
        button.secondary { background: var(--dark); }
        button.danger { background: var(--alert); }
        .log-container {
            background: var(--dark);
            color: var(--secondary);
            padding: 15px;
            border-radius: 8px;
            font-family: 'Fira Code', monospace;
            font-size: 12px;
            max-height: 200px;
            overflow-y: auto;
            line-height: 1.5;
        }
        .log-entry { margin-bottom: 4px; border-bottom: 1px solid rgba(255,255,255,0.1); padding-bottom: 4px; }
        .log-entry.success { color: #A8E6CF; }
        .log-entry.error { color: #FF6B6B; }
        .log-entry.warn { color: var(--secondary); }
        .flex-row { display: flex; gap: 10px; margin-top: 15px; }
    </style>
</head>
<body>

<div class="container">
    <h1>AuDHD Companion: Database Layer</h1>
    <div class="subtitle">Phase 1: Schema & Connection (with Graceful Degradation)</div>

    <div class="status-bar" id="statusBar">
        <span id="statusText">System Idle</span>
        <span id="modeBadge" style="font-size:11px; opacity:0.7;">Waiting...</span>
    </div>

    <div class="card">
        <h3 style="margin-top:0">IndexedDB Schema (PRD 4.1)</h3>
        <div class="schema-grid">
            <div class="store-badge"><strong>sessions</strong> | logs, modules, time</div>
            <div class="store-badge"><strong>ladders</strong> | tasks, steps, status</div>
            <div class="store-badge"><strong>mood_logs</strong> | mood, timestamp</div>
            <div class="store-badge"><strong>wins</strong> | daily categories</div>
            <div class="store-badge"><strong>parking</strong> | tomorrow's steps</div>
            <div class="store-badge"><strong>unstick_logs</strong> | triggers, protocols</div>
            <div class="store-badge"><strong>settings</strong> | prefs, key-value</div>
        </div>
    </div>

    <div class="card">
        <h3 style="margin-top:0">Operations</h3>
        <div class="flex-row">
            <button id="btnInit" onclick="DatabaseService.init()">1. Initialize DB</button>
            <button id="btnTest" onclick="DatabaseService.testOperations()" class="secondary" disabled>2. Run Test Write</button>
            <button id="btnClear" onclick="DatabaseService.clearAll()" class="danger" disabled>3. Clear Data</button>
        </div>
    </div>

    <div class="log-container" id="logs">
        <div class="log-entry">> Ready to initialize...</div>
    </div>
</div>

<script>
/**
 * AuDHD Companion - Database Service
 * PRD Section 4.1 & 7.1 (Graceful Degradation)
 * 
 * Implements IndexedDB with automatic fallback to localStorage
 * if access is denied (e.g., in sandboxed environments).
 */
const DatabaseService = (() => {
    // Configuration
    const DB_NAME = 'audhd_companion_v1';
    const DB_VERSION = 1;
    const STORES = ['sessions', 'ladders', 'mood_logs', 'wins', 'parking', 'unstick_logs', 'settings'];
    
    // State
    let dbInstance = null;
    let adapter = null; // 'idb' or 'local'

    // Logger
    const log = (msg, type = 'info') => {
        const logsEl = document.getElementById('logs');
        const entry = document.createElement('div');
        entry.className = `log-entry ${type}`;
        const time = new Date().toLocaleTimeString();
        entry.textContent = `[${time}] ${msg}`;
        logsEl.appendChild(entry);
        logsEl.scrollTop = logsEl.scrollHeight;
    };

    const setStatus = (msg, mode, isError) => {
        const bar = document.getElementById('statusBar');
        const text = document.getElementById('statusText');
        const badge = document.getElementById('modeBadge');
        text.textContent = msg;
        badge.textContent = `Backend: ${mode}`;
        bar.className = `status-bar ${isError ? 'error' : 'success'}`;
        
        document.getElementById('btnTest').disabled = false;
        document.getElementById('btnClear').disabled = false;
    };

    // --- IndexedDB Adapter ---
    const IDBAdapter = {
        db: null,
        
        init: () => new Promise((resolve, reject) => {
            log('Attempting to open IndexedDB...', 'info');
            const request = indexedDB.open(DB_NAME, DB_VERSION);

            request.onupgradeneeded = (event) => {
                const db = event.target.result;
                log('Upgrading schema...', 'info');
                
                STORES.forEach(storeName => {
                    if (!db.objectStoreNames.contains(storeName)) {
                        const options = storeName === 'settings' ? { keyPath: 'key' } : { keyPath: 'id' };
                        const store = db.createObjectStore(storeName, options);
                        
                        // Create Indexes per PRD
                        if (storeName === 'sessions') {
                            store.createIndex('date', 'date', { unique: false });
                            store.createIndex('module', 'module', { unique: false });
                        } else if (storeName === 'ladders') {
                            store.createIndex('created_date', 'created_date', { unique: false });
                            store.createIndex('status', 'status', { unique: false });
                        } else if (storeName === 'mood_logs') {
                            store.createIndex('timestamp', 'timestamp', { unique: false });
                        } else if (storeName === 'wins') {
                            store.createIndex('date', 'date', { unique: false });
                            store.createIndex('category', 'category', { unique: false });
                        }
                        log(`Created store: ${storeName}`, 'success');
                    }
                });
            };

            request.onsuccess = (event) => {
                IDBAdapter.db = event.target.result;
                log('IndexedDB Connected.', 'success');
                resolve();
            };

            request.onerror = (event) => {
                log(`IDB Error: ${event.target.error.message}`, 'error');
                reject(event.target.error);
            };
        }),

        add: (storeName, item) => new Promise((resolve, reject) => {
            const tx = IDBAdapter.db.transaction(storeName, 'readwrite');
            const store = tx.objectStore(storeName);
            const req = store.add(item);
            req.onsuccess = () => resolve(req.result);
            req.onerror = () => reject(req.error);
        }),

        getAll: (storeName) => new Promise((resolve, reject) => {
            const tx = IDBAdapter.db.transaction(storeName, 'readonly');
            const store = tx.objectStore(storeName);
            const req = store.getAll();
            req.onsuccess = () => resolve(req.result);
            req.onerror = () => reject(req.error);
        }),

        clear: (storeName) => new Promise((resolve, reject) => {
            const tx = IDBAdapter.db.transaction(storeName, 'readwrite');
            const store = tx.objectStore(storeName);
            const req = store.clear();
            req.onsuccess = () => resolve();
            req.onerror = () => reject(req.error);
        })
    };

    // --- LocalStorage Adapter (Fallback) ---
    const LSAdapter = {
        _getKey: (storeName) => `audhd_db_${storeName}`,

        init: () => Promise.resolve(), // LS is always ready

        add: (storeName, item) => {
            const key = LSAdapter._getKey(storeName);
            const data = JSON.parse(localStorage.getItem(key) || '[]');
            
            // Ensure ID exists
            if (!item.id && item.key) item.id = item.key; // Handle settings key/id mismatch
            if (!item.id) item.id = crypto.randomUUID(); 

            data.push(item);
            localStorage.setItem(key, JSON.stringify(data));
            return Promise.resolve(item.id);
        },

        getAll: (storeName) => {
            const key = LSAdapter._getKey(storeName);
            const data = JSON.parse(localStorage.getItem(key) || '[]');
            return Promise.resolve(data);
        },

        clear: (storeName) => {
            const key = LSAdapter._getKey(storeName);
            localStorage.removeItem(key);
            return Promise.resolve();
        }
    };

    // --- Service Logic ---

    return {
        init: async () => {
            try {
                // Try IndexedDB
                await IDBAdapter.init();
                adapter = IDBAdapter;
                setStatus('Database Connected', 'IndexedDB', false);
            } catch (error) {
                // Fallback
                log(`IndexedDB Failed. Falling back to localStorage (${error.name})`, 'warn');
                await LSAdapter.init();
                adapter = LSAdapter;
                setStatus('Running in Safe Mode', 'LocalStorage', false);
            }
        },

        testOperations: async () => {
            try {
                log('Testing Write Operation...', 'info');
                
                // Create a dummy session
                const session = {
                    id: 'test-session-' + Date.now(),
                    date: new Date().toISOString().split('T')[0],
                    time_start: new Date().toLocaleTimeString(),
                    module: 'pre-game',
                    completed: true,
                    notes: 'Test entry from UI'
                };

                await adapter.add('sessions', session);
                log(`Wrote session ID: ${session.id}`, 'success');

                log('Testing Read Operation...', 'info');
                const sessions = await adapter.getAll('sessions');
                log(`Found ${sessions.length} sessions in store.`, 'success');

            } catch (e) {
                log(`Test Failed: ${e.message}`, 'error');
            }
        },

        clearAll: async () => {
            if(!confirm("Are you sure? This will delete all test data.")) return;
            
            for (const store of STORES) {
                await adapter.clear(store);
            }
            log('All stores cleared.', 'warn');
        }
    };
})();
</script>

</body>
</html>

