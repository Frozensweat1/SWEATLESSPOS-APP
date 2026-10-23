/* Sweatless public site service worker.
   Handles GET only: public pages, static assets and the dashboards (offline copy). POS, auth and Livewire updates always go to the network. */
const VERSION = 'v2';
const STATIC_CACHE = 'sweatless-static-' + VERSION;
const PAGE_CACHE = 'sweatless-pages-' + VERSION;
const IMAGE_CACHE = 'sweatless-images-' + VERSION;
const APP_CACHE = 'sweatless-app-' + VERSION;
const KEEP = [STATIC_CACHE, PAGE_CACHE, IMAGE_CACHE, APP_CACHE];
const IMAGE_LIMIT = 80;
const PAGE_LIMIT = 40;
const APP_LIMIT = 6;

// Works for root and sub-folder installs (e.g. XAMPP): scope is the site base path.
const BASE = new URL(self.registration.scope).pathname;
const OFFLINE_URL = BASE + 'offline';

// Authenticated dashboards: last good copy is kept for offline viewing and wiped on logout.
const APP_ROUTES = /^(dashboard|sales-dashboard|pos\/offline)\/?$/;

const PAGE_ROUTES = /^(about|products|product\/[^/]+|testimonials|gallery|gallery\/[^/]+|contact)?\/?$/;

const PRECACHE = [
    OFFLINE_URL,
    BASE + 'assets/css/site.css',
    BASE + 'assets/js/site.js',
    BASE + 'assets/img/icon-192.png',
];

self.addEventListener('install', (event) => {
    event.waitUntil(
        caches.open(STATIC_CACHE).then((cache) =>
            Promise.all(PRECACHE.map((url) => cache.add(url).catch(() => null)))
        ).then(() => self.skipWaiting())
    );
});

self.addEventListener('activate', (event) => {
    event.waitUntil(
        caches.keys()
            .then((keys) => Promise.all(keys.filter((k) => k.startsWith('sweatless-') && !KEEP.includes(k)).map((k) => caches.delete(k))))
            .then(() => self.clients.claim())
    );
});

async function trim(cacheName, max) {
    const cache = await caches.open(cacheName);
    const keys = await cache.keys();
    for (let i = 0; i < keys.length - max; i++) await cache.delete(keys[i]);
}

function networkWithTimeout(request, ms) {
    return new Promise((resolve, reject) => {
        const timer = setTimeout(() => reject(new Error('timeout')), ms);
        fetch(request).then((res) => { clearTimeout(timer); resolve(res); }, (err) => { clearTimeout(timer); reject(err); });
    });
}

async function pageStrategy(request, cacheName = PAGE_CACHE, limit = PAGE_LIMIT) {
    const cache = await caches.open(cacheName);
    // Key by URL only so full loads and Livewire navigate fetches share one entry.
    const key = new Request(request.url);
    try {
        const res = await networkWithTimeout(request, 6000);
        if (res.ok && !res.redirected) {
            cache.put(key, res.clone()).then(() => trim(cacheName, limit));
        }
        return res;
    } catch (err) {
        const cached = await cache.match(key);
        if (cached) return cached;
        if (request.mode === 'navigate') {
            const offline = await caches.match(OFFLINE_URL);
            if (offline) return offline;
        }
        return Response.error();
    }
}

async function staleWhileRevalidate(request, cacheName, limit) {
    const cache = await caches.open(cacheName);
    const cached = await cache.match(request);
    const update = fetch(request).then((res) => {
        if (res.ok && res.status === 200) {
            cache.put(request, res.clone()).then(() => limit && trim(cacheName, limit));
        }
        return res;
    });
    if (cached) {
        update.catch(() => null);
        return cached;
    }
    return update;
}

self.addEventListener('fetch', (event) => {
    const request = event.request;
    if (request.method !== 'GET') return;

    const url = new URL(request.url);
    if (url.origin !== self.location.origin || !url.pathname.startsWith(BASE)) return;

    const rel = url.pathname.slice(BASE.length);

    if (/^(assets\/(css|js|img|fonts)|vendors)\//.test(rel)) {
        event.respondWith(staleWhileRevalidate(request, STATIC_CACHE));
    } else if (rel.startsWith('storage/')) {
        event.respondWith(staleWhileRevalidate(request, IMAGE_CACHE, IMAGE_LIMIT));
    } else if (/^livewire\/[^/]+\.js$/.test(rel) || rel === 'livewire/livewire.js') {
        event.respondWith(staleWhileRevalidate(request, STATIC_CACHE));
    } else if (rel === 'offline') {
        event.respondWith(caches.match(OFFLINE_URL).then((r) => r || fetch(request)));
    } else if (APP_ROUTES.test(rel) && (request.mode === 'navigate' || (request.headers.get('accept') || '').includes('text/html'))) {
        event.respondWith(pageStrategy(request, APP_CACHE, APP_LIMIT));
    } else if (PAGE_ROUTES.test(rel) && (request.mode === 'navigate' || (request.headers.get('accept') || '').includes('text/html') || request.headers.has('X-Livewire-Navigate'))) {
        event.respondWith(pageStrategy(request));
    }
});
