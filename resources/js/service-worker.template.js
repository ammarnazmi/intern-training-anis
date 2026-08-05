const CACHE_NAME = '{CACHE_NAME}';
const DOMAIN_WHITELIST = [self.location.host, 'fonts.googleapis.com', 'fonts.gstatic.com', 'cdn.jsdelivr.net', 'embed.tawk.to'];
const EXCLUDED_PATHS = ['/citra', '/storage'];

const removeOldCaches = async () => {
    const keys = await caches.keys();

    for (const key of keys) {
        if (key === CACHE_NAME) continue;

        await caches.delete(key);
    }
};

self.addEventListener('install', (event) => {
    self.skipWaiting();
});

self.addEventListener('activate', (event) => {
    event.waitUntil(removeOldCaches());
    event.waitUntil(clients.claim());
});

self.addEventListener('message', (event) => {
    if (event.data === 'ACTIVATE') {
        self.skipWaiting();
        event.waitUntil(removeOldCaches());
        event.waitUntil(clients.claim());
    }
});

self.addEventListener('fetch', (event) => {
    const requestUrl = new URL(event.request.url);
    const requestHeaders = event.request.headers;

    if (
        requestHeaders.has('X-Bolt') ||
        (requestHeaders.get('X-Requested-With') ?? '').toLowerCase() === 'xmlhttprequest' ||
        DOMAIN_WHITELIST.includes(requestUrl.host) === false ||
        EXCLUDED_PATHS.some((path) => requestUrl.pathname.startsWith(path))
    ) {
        return;
    }

    const response = (async () => {
        const response = await (async () => {
            let response = await caches.match(requestUrl, { ignoreVary: true });

            if (response) {
                return response;
            }

            try {
                response = await fetch(event.request.clone());
            } catch (error) {
                return new Response('408 REQUEST TIMEOUT: Unable to connect with the server', {
                    status: 408,
                    headers: {
                        'Content-Type': 'text/plain',
                    },
                });
            }

            if (
                requestUrl.pathname === self.location.pathname ||
                requestUrl.pathname.search(/(\.(s?css|(c|m)?js|json|a?png|avif|gif|jpe?g|svg|webp|eot|(o|t)tf|woff2?|mp3)|\+esm)$/) === -1 ||
                response.type === 'opaque' ||
                response.ok === false
            ) {
                return response;
            }

            (await caches.open(CACHE_NAME)).put(requestUrl, response.clone());

            return response;
        })();

        return response;
    })();

    event.respondWith(response);
});
