const CACHE_NAME = 'absensi-siswa-v3';
const OFFLINE_URL = '/offline.html';

// File yang di-cache saat install (biar bisa buka tanpa internet) - HANYA file statis offline, JANGAN cache halaman dinamis
const PRECACHE_URLS = [
  '/offline.html',
  '/manifest.json'
];

self.addEventListener('install', (event) => {
  event.waitUntil(
    caches.open(CACHE_NAME).then((cache) => cache.addAll(PRECACHE_URLS)).then(() => self.skipWaiting())
  );
});

self.addEventListener('activate', (event) => {
  event.waitUntil(
    caches.keys().then((keys) =>
      Promise.all(keys.filter((k) => k !== CACHE_NAME).map((k) => caches.delete(k)))
    ).then(() => self.clients.claim())
  );
});

self.addEventListener('fetch', (event) => {
  const req = event.request;

  // Jangan cache POST / login / logout / api yang butuh internet real-time
  if (req.method !== 'GET') return;
  const url = new URL(req.url);
  // Semua halaman dinamis JANGAN DI-CACHE sama sekali - harus selalu ambil dari server biar data tidak balik lagi setelah hapus
  const isDynamicPage = url.pathname.startsWith('/admin') || url.pathname.startsWith('/guru') || url.pathname.startsWith('/ortu') || url.pathname.startsWith('/tunggakan-spp') || url.pathname.startsWith('/absensi') || url.pathname.startsWith('/nilai') || req.headers.get('accept')?.includes('text/html');
  if (isDynamicPage) {
    // Network ONLY - jangan simpan ke cache, biar setelah hapus tidak muncul lagi. Jangan cache response 403/401
    event.respondWith(
      fetch(req, { cache: 'no-store', headers: { 'Cache-Control': 'no-cache', 'Pragma': 'no-cache' } })
        .then(res => {
          // Jangan cache kalau 403/401/500 (biar tidak nyangkut halaman error)
          if (!res.ok && [401,403,419,500].includes(res.status)) {
            return res;
          }
          return res;
        })
        .catch(() => caches.match(req).then((cached) => cached || caches.match(OFFLINE_URL)))
    );
    return;
  }

  // Untuk aset statis: Cache first
  event.respondWith(
    caches.match(req).then((cached) => {
      if (cached) return cached;
      return fetch(req)
        .then((res) => {
          if (res.ok) {
            const copy = res.clone();
            caches.open(CACHE_NAME).then((c) => c.put(req, copy));
          }
          return res;
        })
        .catch(() => {
          if (req.headers.get('accept')?.includes('text/html')) {
            return caches.match(OFFLINE_URL);
          }
        });
    })
  );
});
