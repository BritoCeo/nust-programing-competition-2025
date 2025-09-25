const CACHE_NAME = 'mesmtf-v2';
const urlsToCache = [
    '/',
    '/css/app.css',
    '/js/app.js',
    '/manifest.json',
    '/offline.html',
    '/medical-records',
    '/appointments',
    '/expert-system',
    '/treatments',
    '/pharmacy',
    '/reports'
];

// API endpoints to cache for offline use
const API_CACHE_NAME = 'mesmtf-api-v1';
const apiEndpoints = [
    '/api/symptoms',
    '/api/diseases',
    '/api/drugs',
    '/api/expert-system/analyze',
    '/api/medical-records',
    '/api/appointments'
];

// Install event - cache resources
self.addEventListener('install', event => {
    event.waitUntil(
        Promise.all([
            caches.open(CACHE_NAME).then(cache => {
                console.log('Caching app resources');
                return cache.addAll(urlsToCache);
            }),
            caches.open(API_CACHE_NAME).then(cache => {
                console.log('Caching API endpoints');
                return cache.addAll(apiEndpoints.map(url => new Request(url, {method: 'GET'})));
            })
        ])
    );
});

// Fetch event - serve from cache when offline
self.addEventListener('fetch', event => {
    const request = event.request;
    const url = new URL(request.url);
    
    // Handle API requests
    if (url.pathname.startsWith('/api/')) {
        event.respondWith(handleApiRequest(request));
        return;
    }
    
    // Handle navigation requests
    if (request.destination === 'document') {
        event.respondWith(handleNavigationRequest(request));
        return;
    }
    
    // Handle other requests (CSS, JS, images, etc.)
    event.respondWith(handleResourceRequest(request));
});

// Handle API requests with offline support
async function handleApiRequest(request) {
    try {
        // Try network first
        const networkResponse = await fetch(request);
        
        // Cache successful responses
        if (networkResponse.ok) {
            const cache = await caches.open(API_CACHE_NAME);
            cache.put(request, networkResponse.clone());
        }
        
        return networkResponse;
    } catch (error) {
        // Network failed, try cache
        const cachedResponse = await caches.match(request);
        if (cachedResponse) {
            return cachedResponse;
        }
        
        // Return offline API response
        return new Response(JSON.stringify({
            error: 'Offline',
            message: 'This feature is not available offline',
            offline: true
        }), {
            status: 503,
            headers: { 'Content-Type': 'application/json' }
        });
    }
}

// Handle navigation requests
async function handleNavigationRequest(request) {
    try {
        // Try network first
        const networkResponse = await fetch(request);
        
        if (networkResponse.ok) {
            return networkResponse;
        }
    } catch (error) {
        // Network failed, try cache
        const cachedResponse = await caches.match(request);
        if (cachedResponse) {
            return cachedResponse;
        }
        
        // Return offline page
        return caches.match('/offline.html');
    }
}

// Handle resource requests (CSS, JS, images)
async function handleResourceRequest(request) {
    try {
        // Try cache first for better performance
        const cachedResponse = await caches.match(request);
        if (cachedResponse) {
            return cachedResponse;
        }
        
        // Try network
        const networkResponse = await fetch(request);
        
        if (networkResponse.ok) {
            // Cache successful responses
            const cache = await caches.open(CACHE_NAME);
            cache.put(request, networkResponse.clone());
        }
        
        return networkResponse;
    } catch (error) {
        // Both cache and network failed
        return new Response('Resource not available offline', {
            status: 503,
            headers: { 'Content-Type': 'text/plain' }
        });
    }
}

// Activate event - clean up old caches
self.addEventListener('activate', event => {
    event.waitUntil(
        caches.keys().then(cacheNames => {
            return Promise.all(
                cacheNames.map(cacheName => {
                    if (cacheName !== CACHE_NAME) {
                        console.log('Deleting old cache:', cacheName);
                        return caches.delete(cacheName);
                    }
                })
            );
        })
    );
});

// Background sync for offline data
self.addEventListener('sync', event => {
    if (event.tag === 'background-sync') {
        event.waitUntil(doBackgroundSync());
    }
});

async function doBackgroundSync() {
    // Sync offline data when back online
    try {
        const offlineData = await getOfflineData();
        if (offlineData.length > 0) {
            await syncOfflineData(offlineData);
        }
    } catch (error) {
        console.error('Background sync failed:', error);
    }
}

async function getOfflineData() {
    // Get stored offline data from IndexedDB
    return new Promise((resolve) => {
        const request = indexedDB.open('mesmtf-offline', 1);
        request.onsuccess = () => {
            const db = request.result;
            const transaction = db.transaction(['symptoms', 'diagnoses'], 'readonly');
            const symptomsStore = transaction.objectStore('symptoms');
            const diagnosesStore = transaction.objectStore('diagnoses');
            
            const symptoms = [];
            const diagnoses = [];
            
            symptomsStore.getAll().onsuccess = (e) => {
                symptoms.push(...e.target.result);
            };
            
            diagnosesStore.getAll().onsuccess = (e) => {
                diagnoses.push(...e.target.result);
            };
            
            transaction.oncomplete = () => {
                resolve([...symptoms, ...diagnoses]);
            };
        };
    });
}

async function syncOfflineData(data) {
    // Send offline data to server
    for (const item of data) {
        try {
            await fetch('/api/sync-offline-data', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: JSON.stringify(item)
            });
        } catch (error) {
            console.error('Failed to sync item:', error);
        }
    }
}