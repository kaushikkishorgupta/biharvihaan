const CACHE_NAME = 'bihar-vihaan-v3';
const OFFLINE_URL = 'offline.html';

const ASSETS_TO_CACHE = [
    'offline.html',
    'assets/css/style.css',
    'assets/js/app.js',
    'assets/js/payments.js'
];

self.addEventListener('install', (event) => {
    event.waitUntil(
        caches.open(CACHE_NAME).then((cache) => {
            return cache.addAll(ASSETS_TO_CACHE);
        })
    );
    self.skipWaiting();
});

self.addEventListener('activate', (event) => {
    event.waitUntil(
        caches.keys().then((cacheNames) => {
            return Promise.all(
                cacheNames.map((cache) => {
                    if (cache !== CACHE_NAME) {
                        return caches.delete(cache);
                    }
                })
            );
        })
    );
    self.clients.claim();
});

self.addEventListener('fetch', (event) => {
    // Only handle GET requests and document requests
    if (event.request.method !== 'GET' || !event.request.headers.get('accept').includes('text/html')) {
        return;
    }

    event.respondWith(
        fetch(event.request).catch(() => {
            return caches.match(event.request).then((cachedResponse) => {
                if (cachedResponse) {
                    return cachedResponse;
                }
                return caches.match(OFFLINE_URL);
            });
        })
    );
});
