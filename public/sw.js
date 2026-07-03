const CACHE_NAME = 'edukan-cache-v1';
// Aquí guardamos los archivos principales para que carguen sin internet
const urlsToCache = [
  './',
  './index.html',
  './style.css',
  './script.js'
];

// Instalación del Service Worker
self.addEventListener('install', event => {
  event.waitUntil(
    caches.open(CACHE_NAME).then(cache => {
      return cache.addAll(urlsToCache);
    })
  );
});

// Interceptar peticiones para devolver la versión ultra rápida del caché
self.addEventListener('fetch', event => {
  event.respondWith(
    caches.match(event.request).then(response => {
      // Si el archivo está en la memoria caché, lo devuelve al instante. Si no, usa internet.
      return response || fetch(event.request);
    })
  );
});