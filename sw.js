const CACHE_NAME = 'my-site-cache-v1';
const BASE_PATH = '/gritskevich.github.io/';
const urlsToCache = [
  '/popendus228.github.io/',
  '/gritskevich.github.io/index.html',
  'manifest.json',
  'favicon.ico',
  '/icons/icon-192x192.png',
  '/icons/icon-512x512.png'
];

self.addEventListener('install', event => {
  event.waitUntil(
    caches.open(CACHE_NAME)
      .then(cache => cache.addAll(urlsToCache))
  );
});

self.addEventListener('fetch', event => {
  event.respondWith(
    caches.match(event.request)
      .then(response => response || fetch(event.request))
  );
});
