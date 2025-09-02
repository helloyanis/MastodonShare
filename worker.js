// This will cache the site files so it works offline, and faster if visited many times

const addResourcesToCache = async (resources) => {
  const cache = await caches.open("v1");
  await cache.addAll(resources);
};

self.addEventListener("install", (event) => {
  event.waitUntil(
    addResourcesToCache([
      "/",
      "/index.html",
      "/style.css",
      "/script.js",
      "/assets/mastodonshare.png",
      "/libraries/mdui.css",
      "/libraries/mdui.js"
    ]),
  );
});

self.addEventListener("fetch", (event) => {
  event.respondWith(
    caches.match(event.request).then((cachedResponse) => {
        if (cachedResponse) {
            return cachedResponse;
        }
        return fetch(event.request);
    })
  );
});