const CACHE_NAME = 'frotamaster-v2'; // Alterei para v2 para forçar atualização
const OFFLINE_URL = '/offline';

// Arquivos críticos para cachear imediatamente
const ASSETS_TO_CACHE = [
    OFFLINE_URL,
    '/img/logo.svg',
    '/img/android/icon-192.png'
];

// 1. Instalação: Baixa e salva a página offline
self.addEventListener('install', (event) => {
    event.waitUntil(
        caches.open(CACHE_NAME)
            .then((cache) => {
                console.log('[ServiceWorker] Caching offline page');
                return cache.addAll(ASSETS_TO_CACHE);
            })
    );
    self.skipWaiting();
});

// 2. Ativação: Limpa caches antigos se houver atualização
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

// 3. Fetch: Tenta rede, se falhar, usa o cache
self.addEventListener('fetch', (event) => {
    // Apenas intercepta requisições de página (HTML)
    if (event.request.mode === 'navigate') {
        event.respondWith(
            fetch(event.request)
                .catch(() => {
                    // Se a rede falhar (catch), retorna a página offline do cache
                    return caches.match(OFFLINE_URL);
                })
        );
    } else {
        // Para imagens, css, js, etc., tenta a rede normalmente
        // (Poderíamos cachear aqui também, mas vamos manter simples por enquanto)
        event.respondWith(fetch(event.request));
    }
});
