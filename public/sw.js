// Service Worker for MESMTF (Medical Expert System for Malaria & Typhoid Fever)
// Version 1.0.0

const CACHE_NAME = 'mesmtf-v1.0.0';
const STATIC_CACHE = 'mesmtf-static-v1.0.0';
const DYNAMIC_CACHE = 'mesmtf-dynamic-v1.0.0';

// Static assets to cache
const STATIC_ASSETS = [
    '/',
    '/dashboard',
    '/login',
    '/register',
    '/build/assets/app.css',
    '/build/assets/app.js',
    '/manifest.json',
    '/favicon.ico',
    // Add critical CSS and JS files
    'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css',
    'https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js'
];

// API endpoints to cache
const API_ENDPOINTS = [
    '/api/medical-records',
    '/api/appointments',
    '/api/expert-system/statistics',
    '/api/dashboard/stats'
];

// Install event - cache static assets
self.addEventListener('install', event => {
    console.log('Service Worker: Installing...');
    
    event.waitUntil(
        caches.open(STATIC_CACHE)
            .then(cache => {
                console.log('Service Worker: Caching static assets');
                return cache.addAll(STATIC_ASSETS);
            })
            .then(() => {
                console.log('Service Worker: Static assets cached');
                return self.skipWaiting();
            })
            .catch(error => {
                console.error('Service Worker: Failed to cache static assets', error);
            })
    );
});

// Activate event - clean up old caches
self.addEventListener('activate', event => {
    console.log('Service Worker: Activating...');
    
    event.waitUntil(
        caches.keys()
            .then(cacheNames => {
                return Promise.all(
                    cacheNames.map(cacheName => {
                        if (cacheName !== STATIC_CACHE && cacheName !== DYNAMIC_CACHE) {
                            console.log('Service Worker: Deleting old cache', cacheName);
                            return caches.delete(cacheName);
                        }
                    })
                );
            })
            .then(() => {
                console.log('Service Worker: Activated');
                return self.clients.claim();
            })
    );
});

// Fetch event - serve from cache, fallback to network
self.addEventListener('fetch', event => {
    const { request } = event;
    const url = new URL(request.url);
    
    // Skip non-GET requests
    if (request.method !== 'GET') {
        return;
    }
    
    // Handle different types of requests
    if (request.destination === 'document') {
        // Handle page requests
        event.respondWith(handlePageRequest(request));
    } else if (request.destination === 'style' || request.destination === 'script' || request.destination === 'image') {
        // Handle static assets
        event.respondWith(handleStaticAsset(request));
    } else if (url.pathname.startsWith('/api/')) {
        // Handle API requests
        event.respondWith(handleApiRequest(request));
    } else {
        // Handle other requests
        event.respondWith(handleOtherRequest(request));
    }
});

// Handle page requests with offline fallback
async function handlePageRequest(request) {
    try {
        // Try network first
        const networkResponse = await fetch(request);
        
        if (networkResponse.ok) {
            // Cache successful responses
            const cache = await caches.open(DYNAMIC_CACHE);
            cache.put(request, networkResponse.clone());
            return networkResponse;
        }
        
        throw new Error('Network response not ok');
    } catch (error) {
        console.log('Service Worker: Network failed, trying cache for', request.url);
        
        // Try cache
        const cachedResponse = await caches.match(request);
        if (cachedResponse) {
            return cachedResponse;
        }
        
        // Return offline page for navigation requests
        if (request.destination === 'document') {
            return caches.match('/offline.html') || new Response(
                getOfflinePage(),
                { headers: { 'Content-Type': 'text/html' } }
            );
        }
        
        throw error;
    }
}

// Handle static assets with cache-first strategy
async function handleStaticAsset(request) {
    try {
        // Try cache first
        const cachedResponse = await caches.match(request);
        if (cachedResponse) {
            return cachedResponse;
        }
        
        // Try network
        const networkResponse = await fetch(request);
        
        if (networkResponse.ok) {
            // Cache successful responses
            const cache = await caches.open(STATIC_CACHE);
            cache.put(request, networkResponse.clone());
        }
        
        return networkResponse;
    } catch (error) {
        console.error('Service Worker: Failed to fetch static asset', request.url, error);
        throw error;
    }
}

// Handle API requests with network-first strategy
async function handleApiRequest(request) {
    try {
        // Try network first
        const networkResponse = await fetch(request);
        
        if (networkResponse.ok) {
            // Cache successful API responses
            const cache = await caches.open(DYNAMIC_CACHE);
            cache.put(request, networkResponse.clone());
        }
        
        return networkResponse;
    } catch (error) {
        console.log('Service Worker: Network failed for API, trying cache for', request.url);
        
        // Try cache
        const cachedResponse = await caches.match(request);
        if (cachedResponse) {
            return cachedResponse;
        }
        
        // Return offline response for API requests
        return new Response(
            JSON.stringify({
                error: 'Offline',
                message: 'You are currently offline. Some features may not be available.',
                offline: true
            }),
            {
                status: 503,
                headers: { 'Content-Type': 'application/json' }
            }
        );
    }
}

// Handle other requests
async function handleOtherRequest(request) {
    try {
        return await fetch(request);
    } catch (error) {
        const cachedResponse = await caches.match(request);
        return cachedResponse || new Response('Offline', { status: 503 });
    }
}

// Background sync for offline actions
self.addEventListener('sync', event => {
    console.log('Service Worker: Background sync triggered', event.tag);
    
    if (event.tag === 'medical-record-sync') {
        event.waitUntil(syncMedicalRecords());
    } else if (event.tag === 'appointment-sync') {
        event.waitUntil(syncAppointments());
    }
});

// Sync medical records when back online
async function syncMedicalRecords() {
    try {
        // Get pending medical records from IndexedDB
        const pendingRecords = await getPendingMedicalRecords();
        
        for (const record of pendingRecords) {
            try {
                const response = await fetch('/api/medical-records', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': record.csrf_token
                    },
                    body: JSON.stringify(record.data)
                });
                
                if (response.ok) {
                    // Remove from pending records
                    await removePendingMedicalRecord(record.id);
                    console.log('Service Worker: Synced medical record', record.id);
                }
            } catch (error) {
                console.error('Service Worker: Failed to sync medical record', record.id, error);
            }
        }
    } catch (error) {
        console.error('Service Worker: Failed to sync medical records', error);
    }
}

// Sync appointments when back online
async function syncAppointments() {
    try {
        // Get pending appointments from IndexedDB
        const pendingAppointments = await getPendingAppointments();
        
        for (const appointment of pendingAppointments) {
            try {
                const response = await fetch('/api/appointments', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': appointment.csrf_token
                    },
                    body: JSON.stringify(appointment.data)
                });
                
                if (response.ok) {
                    // Remove from pending appointments
                    await removePendingAppointment(appointment.id);
                    console.log('Service Worker: Synced appointment', appointment.id);
                }
            } catch (error) {
                console.error('Service Worker: Failed to sync appointment', appointment.id, error);
            }
        }
    } catch (error) {
        console.error('Service Worker: Failed to sync appointments', error);
    }
}

// Push notifications
self.addEventListener('push', event => {
    console.log('Service Worker: Push notification received');
    
    const options = {
        body: event.data ? event.data.text() : 'New notification from MESMTF',
        icon: '/icons/icon-192x192.png',
        badge: '/icons/badge-72x72.png',
        vibrate: [100, 50, 100],
        data: {
            dateOfArrival: Date.now(),
            primaryKey: 1
        },
        actions: [
            {
                action: 'explore',
                title: 'View Details',
                icon: '/icons/checkmark.png'
            },
            {
                action: 'close',
                title: 'Close',
                icon: '/icons/xmark.png'
            }
        ]
    };
    
    event.waitUntil(
        self.registration.showNotification('MESMTF Notification', options)
    );
});

// Notification click handler
self.addEventListener('notificationclick', event => {
    console.log('Service Worker: Notification clicked');
    
    event.notification.close();
    
    if (event.action === 'explore') {
        event.waitUntil(
            clients.openWindow('/dashboard')
        );
    }
});

// Helper functions for IndexedDB operations
async function getPendingMedicalRecords() {
    // Implementation for getting pending records from IndexedDB
    return [];
}

async function removePendingMedicalRecord(id) {
    // Implementation for removing pending record from IndexedDB
}

async function getPendingAppointments() {
    // Implementation for getting pending appointments from IndexedDB
    return [];
}

async function removePendingAppointment(id) {
    // Implementation for removing pending appointment from IndexedDB
}

// Offline page HTML
function getOfflinePage() {
    return `
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Offline - MESMTF</title>
    <style>
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            margin: 0;
            padding: 0;
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
            color: white;
        }
        .container {
            text-align: center;
            max-width: 500px;
            padding: 2rem;
        }
        .icon {
            font-size: 4rem;
            margin-bottom: 1rem;
        }
        h1 {
            font-size: 2rem;
            margin-bottom: 1rem;
        }
        p {
            font-size: 1.1rem;
            margin-bottom: 2rem;
            opacity: 0.9;
        }
        .btn {
            background: rgba(255, 255, 255, 0.2);
            border: 2px solid rgba(255, 255, 255, 0.3);
            color: white;
            padding: 12px 24px;
            border-radius: 8px;
            text-decoration: none;
            display: inline-block;
            transition: all 0.3s ease;
        }
        .btn:hover {
            background: rgba(255, 255, 255, 0.3);
            border-color: rgba(255, 255, 255, 0.5);
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="icon">📱</div>
        <h1>You're Offline</h1>
        <p>Don't worry! You can still access your medical records and use the expert system. Your data will sync when you're back online.</p>
        <a href="/" class="btn">Go to Dashboard</a>
    </div>
</body>
</html>
    `;
}
