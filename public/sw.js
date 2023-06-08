const PREFIX = "V0"
const CACHED_FILES = [
    'https://localhost:8888/offline.css',
    'https://localhost:8888/offline.js',
]

self.addEventListener('install', (event) => {
    // Utiliser pour de suite activer la nouvelle version du service worker après son installation
    self.skipWaiting();
    event.waitUntil((async() => {
        const response = await fetch("https://localhost:8888/api/article/last")
        const data = await response.json()
        // Rajouter données json de l'api dans le cache

        const cache = await caches.open(PREFIX)
        const jsonRequest = new Request("/api/data")
        const jsonResponse = new Response(JSON.stringify(data), { headers: { 'Content-Type': 'application/json' } })
        await cache.put(jsonRequest, jsonResponse)
        await Promise.all([...CACHED_FILES, '/offline.html'].map(path => {
            return cache.add(new Request(path))
        }))
    })())
    // console.log(`${PREFIX} install`)
})

self.addEventListener('activate', (event) => {
    // Permet au service worker de directement controler le comportement de la page dès que celui ci est actif
    clients.claim()
    // Vider les caches associés aux anciennes clés de cache
    event.waitUntil((async () => {
        const keys = await caches.keys();
        await Promise.all(
            keys.map((key) => {
            if(!key.includes(PREFIX)) {
                console.log(`Clé supprimée: ${key}`)
                return caches.delete(key)
            }
        })
        )
    })())
    // console.log(`${PREFIX} active`)
})

self.addEventListener('fetch', (event) => {
    if (event.request.mode === "navigate") {
        event.respondWith(
            (async () => {
                try {
                    const preloadResponse = await event.preloadResponse;
                    if (preloadResponse) {
                        return preloadResponse
                    }
                    return await fetch(event.request)
                } catch (e) {
                    // Si hors ligne
                    const cache = await caches.open(PREFIX)
                    return await cache.match('/offline.html')
                }
            })()
        );
    } else if (CACHED_FILES.includes(event.request.url)) {
        event.respondWith(caches.match(event.request))
    }
});
