const CACHE_NAME = 'frotamaster-v1';
const OFFLINE_URL = '/offline';

self.addEventListener('install', (event) => {
    self.skipWaiting();
    console.log('[ServiceWorker] Instalado com sucesso');
});

self.addEventListener('activate', (event) => {
    event.waitUntil(self.clients.claim());
    console.log('[ServiceWorker] Ativado');
});

self.addEventListener('fetch', (event) => {
    // Estratégia simples: Network First. 
    // Garante que o usuário sempre veja dados atualizados da frota.
    event.respondWith(
        fetch(event.request).catch(() => {
            // Opcional: Aqui você pode retornar uma página offline customizada no futuro
            return caches.match(event.request);
        })
    );
});
