const CACHE_NAME = 'audhd-companion-v1';
const STATIC_ASSETS = ['/', '/index.html', '/styles.css', '/app.js', '/db.js', '/manifest.json'];

self.addEventListener('install', (e) => {
  e.waitUntil(
    caches.open(CACHE_NAME).then((cache) => cache.addAll(STATIC_ASSETS))
  );
});

self.addEventListener('fetch', (e) => {
  e.respondWith(
    caches.match(e.request).then((res) => res || fetch(e.request))
  );
});