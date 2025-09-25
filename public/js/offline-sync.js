/**
 * Offline Synchronization System for MESMTF
 * Handles data synchronization when connection is restored
 */

class OfflineSync {
    constructor() {
        this.syncInProgress = false;
        this.syncInterval = null;
        this.retryAttempts = 3;
        this.retryDelay = 5000; // 5 seconds
    }

    /**
     * Initialize offline sync
     */
    async init() {
        // Check if we're online
        if (navigator.onLine) {
            await this.syncOfflineData();
        }

        // Listen for online/offline events
        window.addEventListener('online', () => {
            console.log('Connection restored, starting sync...');
            this.syncOfflineData();
        });

        window.addEventListener('offline', () => {
            console.log('Connection lost, switching to offline mode');
            this.showOfflineIndicator();
        });

        // Start periodic sync check
        this.startPeriodicSync();
    }

    /**
     * Start periodic sync check
     */
    startPeriodicSync() {
        this.syncInterval = setInterval(async () => {
            if (navigator.onLine && !this.syncInProgress) {
                await this.syncOfflineData();
            }
        }, 30000); // Check every 30 seconds
    }

    /**
     * Stop periodic sync
     */
    stopPeriodicSync() {
        if (this.syncInterval) {
            clearInterval(this.syncInterval);
            this.syncInterval = null;
        }
    }

    /**
     * Sync offline data with server
     */
    async syncOfflineData() {
        if (this.syncInProgress || !navigator.onLine) {
            return;
        }

        this.syncInProgress = true;
        this.showSyncIndicator();

        try {
            const syncQueue = await offlineStorage.getSyncQueue();
            console.log(`Syncing ${syncQueue.length} offline items...`);

            for (const item of syncQueue) {
                await this.syncItem(item);
            }

            console.log('Offline sync completed successfully');
            this.hideSyncIndicator();
            this.showSyncSuccess();

        } catch (error) {
            console.error('Offline sync failed:', error);
            this.showSyncError();
        } finally {
            this.syncInProgress = false;
        }
    }

    /**
     * Sync individual item
     */
    async syncItem(item) {
        try {
            let response;
            
            switch (item.type) {
                case 'diagnosis':
                    response = await this.syncDiagnosis(item.data);
                    break;
                case 'appointment':
                    response = await this.syncAppointment(item.data);
                    break;
                case 'medical_record':
                    response = await this.syncMedicalRecord(item.data);
                    break;
                default:
                    console.warn(`Unknown sync item type: ${item.type}`);
                    return;
            }

            if (response.ok) {
                // Remove from sync queue
                await offlineStorage.removeFromSyncQueue(item.id);
                console.log(`Synced ${item.type} successfully`);
            } else {
                throw new Error(`Failed to sync ${item.type}: ${response.status}`);
            }

        } catch (error) {
            console.error(`Failed to sync ${item.type}:`, error);
            
            // Increment attempt count
            item.attempts = (item.attempts || 0) + 1;
            
            if (item.attempts < this.retryAttempts) {
                // Retry later
                setTimeout(() => this.syncItem(item), this.retryDelay);
            } else {
                // Max attempts reached, remove from queue
                await offlineStorage.removeFromSyncQueue(item.id);
                console.error(`Max retry attempts reached for ${item.type}`);
            }
        }
    }

    /**
     * Sync diagnosis data
     */
    async syncDiagnosis(diagnosisData) {
        const response = await fetch('/api/diagnoses', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            },
            body: JSON.stringify(diagnosisData)
        });

        return response;
    }

    /**
     * Sync appointment data
     */
    async syncAppointment(appointmentData) {
        const response = await fetch('/api/appointments', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            },
            body: JSON.stringify(appointmentData)
        });

        return response;
    }

    /**
     * Sync medical record data
     */
    async syncMedicalRecord(recordData) {
        const response = await fetch('/api/medical-records', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            },
            body: JSON.stringify(recordData)
        });

        return response;
    }

    /**
     * Cache essential data for offline use
     */
    async cacheEssentialData() {
        try {
            // Cache symptoms
            const symptomsResponse = await fetch('/api/symptoms');
            if (symptomsResponse.ok) {
                const symptoms = await symptomsResponse.json();
                await offlineStorage.cacheSymptoms(symptoms);
            }

            // Cache diseases
            const diseasesResponse = await fetch('/api/diseases');
            if (diseasesResponse.ok) {
                const diseases = await diseasesResponse.json();
                await offlineStorage.cacheDiseases(diseases);
            }

            // Cache drugs
            const drugsResponse = await fetch('/api/drugs');
            if (drugsResponse.ok) {
                const drugs = awaitdrugsResponse.json();
                await offlineStorage.cacheDrugs(drugs);
            }

            console.log('Essential data cached for offline use');

        } catch (error) {
            console.error('Failed to cache essential data:', error);
        }
    }

    /**
     * Show offline indicator
     */
    showOfflineIndicator() {
        const indicator = document.createElement('div');
        indicator.id = 'offline-indicator';
        indicator.className = 'fixed top-4 right-4 bg-red-500 text-white px-4 py-2 rounded-lg shadow-lg z-50';
        indicator.innerHTML = `
            <div class="flex items-center">
                <i class="fas fa-wifi-slash mr-2"></i>
                <span>You're offline</span>
            </div>
        `;
        document.body.appendChild(indicator);
    }

    /**
     * Hide offline indicator
     */
    hideOfflineIndicator() {
        const indicator = document.getElementById('offline-indicator');
        if (indicator) {
            indicator.remove();
        }
    }

    /**
     * Show sync indicator
     */
    showSyncIndicator() {
        const indicator = document.createElement('div');
        indicator.id = 'sync-indicator';
        indicator.className = 'fixed top-4 right-4 bg-blue-500 text-white px-4 py-2 rounded-lg shadow-lg z-50';
        indicator.innerHTML = `
            <div class="flex items-center">
                <i class="fas fa-sync-alt fa-spin mr-2"></i>
                <span>Syncing data...</span>
            </div>
        `;
        document.body.appendChild(indicator);
    }

    /**
     * Hide sync indicator
     */
    hideSyncIndicator() {
        const indicator = document.getElementById('sync-indicator');
        if (indicator) {
            indicator.remove();
        }
    }

    /**
     * Show sync success message
     */
    showSyncSuccess() {
        const message = document.createElement('div');
        message.className = 'fixed top-4 right-4 bg-green-500 text-white px-4 py-2 rounded-lg shadow-lg z-50';
        message.innerHTML = `
            <div class="flex items-center">
                <i class="fas fa-check mr-2"></i>
                <span>Data synced successfully</span>
            </div>
        `;
        document.body.appendChild(message);
        
        setTimeout(() => message.remove(), 3000);
    }

    /**
     * Show sync error message
     */
    showSyncError() {
        const message = document.createElement('div');
        message.className = 'fixed top-4 right-4 bg-red-500 text-white px-4 py-2 rounded-lg shadow-lg z-50';
        message.innerHTML = `
            <div class="flex items-center">
                <i class="fas fa-exclamation-triangle mr-2"></i>
                <span>Sync failed - will retry later</span>
            </div>
        `;
        document.body.appendChild(message);
        
        setTimeout(() => message.remove(), 5000);
    }

    /**
     * Get sync status
     */
    async getSyncStatus() {
        const syncQueue = await offlineStorage.getSyncQueue();
        return {
            pendingItems: syncQueue.length,
            isOnline: navigator.onLine,
            syncInProgress: this.syncInProgress
        };
    }
}

// Initialize offline sync
const offlineSync = new OfflineSync();

// Export for use in other scripts
window.OfflineSync = OfflineSync;
window.offlineSync = offlineSync;
