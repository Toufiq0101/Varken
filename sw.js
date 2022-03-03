var staticfiles = [
    "/",
    "./index.html",
    "./index.js",
    "./tab_ctrl.js",
    "./css/header.css",
    "./css/main.css",
    "./css/colors.css",
    "./transmitter.js",
    "./splide-2.4.21/dist/css/splide.min.css",
    "./splide-2.4.21/dist/js/splide.min.js",
    "./web_files/halka.gif",
    "./web_files/halka.webp",
    "./css/svg/profile.svg"
];
var staticCacheName = 'halka-v-0.2'
self.addEventListener('install', e => {
    e.waitUntil(
        caches.open(staticCacheName).then(cache => {
            cache.addAll(staticfiles);
        })
    );
});
self.addEventListener("fetch", e => {
    e.respondWith(
        caches.match(e.request).then(response => {
            return response || fetch(e.request);
        })
    );
});
self.addEventListener('activate', evt => {
    evt.waitUntil(
        caches.keys().then(keys => {
            return Promise.all(keys
                .filter(key => key !== staticCacheName)
                .map(key => caches.delete(key))
            );
        })
    );
});