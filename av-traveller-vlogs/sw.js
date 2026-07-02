const CACHE_NAME = 'av-traveller-v1';
const LOCAL_ASSETS = [
  './',
  './index.php',
  './cars.php',
  './booking.php',
  './contact.php',
  './offline.html',
  './assets/css/style.css',
  './assets/js/app.js',
  './assets/js/booking.js',
  './assets/images/logo.svg',
  './assets/images/favicon.svg',
  './assets/images/icons/icon-192.svg',
  './assets/images/icons/icon-512.svg'
];

self.addEventListener('install', (event) => {
  event.waitUntil(
    caches.open(CACHE_NAME).then((cache) => cache.addAll(LOCAL_ASSETS)).then(() => self.skipWaiting())
  );
});

self.addEventListener('activate', (event) => {
  event.waitUntil(
    caches.keys().then((keys) => Promise.all(keys.filter((key) => key !== CACHE_NAME).map((key) => caches.delete(key)))).then(() => self.clients.claim())
  );
});

self.addEventListener('fetch', (event) => {
  if (event.request.method !== 'GET') return;
  const url = new URL(event.request.url);
  if (url.origin !== self.location.origin) return;

  event.respondWith(
    caches.match(event.request).then((cached) => {
      if (cached) return cached;
      return fetch(event.request)
        .then((response) => {
          const clone = response.clone();
          caches.open(CACHE_NAME).then((cache) => cache.put(event.request, clone));
          return response;
        })
        .catch(() => caches.match('./offline.html'));
    })
  );
});

