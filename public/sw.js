const CACHE_NAME = 'mesmtf-v1';
const urlsToCache = [
    '/',
    '/css/app.css',
    '/js/app.js',
    '/manifest.json',
    '/offline.html'
];

// Install event - cache resources
self.addEventListener('install', event => {
    event.waitUntil(
        caches.open(CACHE_NAME)
            .then(cache => {
                console.log('Opened cache');
                return cache.addAll(urlsToCache);
            })
    );
});

// Fetch event - serve from cache when offline
self.addEventListener('fetch', event => {
    event.respondWith(
        caches.match(event.request)
            .then(response => {
                // Return cached version or fetch from network
                if (response) {
                    return response;
                }
                
                // Clone the request
                const fetchRequest = event.request.clone();
                
                return fetch(fetchRequest).then(response => {
                    // Check if valid response
                    if (!response || response.status !== 200 || response.type !== 'basic') {
                        return response;
                    }
                    
                    // Clone the response
                    const responseToCache = response.clone();
                    
                    caches.open(CACHE_NAME)
                        .then(cache => {
                            cache.put(event.request, responseToCache);
                        });
                    
                    return response;
                }).catch(() => {
                    // If network fails, show offline page for navigation requests
                    if (event.request.destination === 'document') {
                        return caches.match('/offline.html');
                    }
                });
            })
    );
});

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