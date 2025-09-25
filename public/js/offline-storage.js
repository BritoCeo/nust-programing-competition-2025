/**
 * Offline Data Storage System for MESMTF
 * Uses IndexedDB for offline data storage and synchronization
 */

class OfflineStorage {
    constructor() {
        this.dbName = 'mesmtf-offline';
        this.dbVersion = 1;
        this.db = null;
    }

    /**
     * Initialize IndexedDB
     */
    async init() {
        return new Promise((resolve, reject) => {
            const request = indexedDB.open(this.dbName, this.dbVersion);

            request.onerror = () => reject(request.error);
            request.onsuccess = () => {
                this.db = request.result;
                resolve(this.db);
            };

            request.onupgradeneeded = (event) => {
                const db = event.target.result;
                
                // Create object stores for offline data
                if (!db.objectStoreNames.contains('symptoms')) {
                    db.createObjectStore('symptoms', { keyPath: 'id' });
                }
                
                if (!db.objectStoreNames.contains('diseases')) {
                    db.createObjectStore('diseases', { keyPath: 'id' });
                }
                
                if (!db.objectStoreNames.contains('drugs')) {
                    db.createObjectStore('drugs', { keyPath: 'id' });
                }
                
                if (!db.objectStoreNames.contains('diagnoses')) {
                    db.createObjectStore('diagnoses', { keyPath: 'id', autoIncrement: true });
                }
                
                if (!db.objectStoreNames.contains('appointments')) {
                    db.createObjectStore('appointments', { keyPath: 'id', autoIncrement: true });
                }
                
                if (!db.objectStoreNames.contains('medical_records')) {
                    db.createObjectStore('medical_records', { keyPath: 'id', autoIncrement: true });
                }
                
                if (!db.objectStoreNames.contains('sync_queue')) {
                    db.createObjectStore('sync_queue', { keyPath: 'id', autoIncrement: true });
                }
            };
        });
    }

    /**
     * Store data offline
     */
    async storeData(storeName, data) {
        if (!this.db) await this.init();
        
        return new Promise((resolve, reject) => {
            const transaction = this.db.transaction([storeName], 'readwrite');
            const store = transaction.objectStore(storeName);
            
            const request = store.put(data);
            request.onsuccess = () => resolve(request.result);
            request.onerror = () => reject(request.error);
        });
    }

    /**
     * Retrieve data from offline storage
     */
    async getData(storeName, key) {
        if (!this.db) await this.init();
        
        return new Promise((resolve, reject) => {
            const transaction = this.db.transaction([storeName], 'readonly');
            const store = transaction.objectStore(storeName);
            
            const request = store.get(key);
            request.onsuccess = () => resolve(request.result);
            request.onerror = () => reject(request.error);
        });
    }

    /**
     * Get all data from a store
     */
    async getAllData(storeName) {
        if (!this.db) await this.init();
        
        return new Promise((resolve, reject) => {
            const transaction = this.db.transaction([storeName], 'readonly');
            const store = transaction.objectStore(storeName);
            
            const request = store.getAll();
            request.onsuccess = () => resolve(request.result);
            request.onerror = () => reject(request.error);
        });
    }

    /**
     * Delete data from offline storage
     */
    async deleteData(storeName, key) {
        if (!this.db) await this.init();
        
        return new Promise((resolve, reject) => {
            const transaction = this.db.transaction([storeName], 'readwrite');
            const store = transaction.objectStore(storeName);
            
            const request = store.delete(key);
            request.onsuccess = () => resolve(request.result);
            request.onerror = () => reject(request.error);
        });
    }

    /**
     * Store offline diagnosis
     */
    async storeOfflineDiagnosis(diagnosisData) {
        const offlineDiagnosis = {
            ...diagnosisData,
            offline: true,
            created_at: new Date().toISOString(),
            synced: false
        };
        
        await this.storeData('diagnoses', offlineDiagnosis);
        await this.addToSyncQueue('diagnosis', offlineDiagnosis);
    }

    /**
     * Store offline appointment
     */
    async storeOfflineAppointment(appointmentData) {
        const offlineAppointment = {
            ...appointmentData,
            offline: true,
            created_at: new Date().toISOString(),
            synced: false
        };
        
        await this.storeData('appointments', offlineAppointment);
        await this.addToSyncQueue('appointment', offlineAppointment);
    }

    /**
     * Store offline medical record
     */
    async storeOfflineMedicalRecord(recordData) {
        const offlineRecord = {
            ...recordData,
            offline: true,
            created_at: new Date().toISOString(),
            synced: false
        };
        
        await this.storeData('medical_records', offlineRecord);
        await this.addToSyncQueue('medical_record', offlineRecord);
    }

    /**
     * Add item to sync queue
     */
    async addToSyncQueue(type, data) {
        const syncItem = {
            type: type,
            data: data,
            created_at: new Date().toISOString(),
            attempts: 0
        };
        
        await this.storeData('sync_queue', syncItem);
    }

    /**
     * Get sync queue items
     */
    async getSyncQueue() {
        return await this.getAllData('sync_queue');
    }

    /**
     * Remove item from sync queue
     */
    async removeFromSyncQueue(id) {
        await this.deleteData('sync_queue', id);
    }

    /**
     * Cache symptoms for offline use
     */
    async cacheSymptoms(symptoms) {
        for (const symptom of symptoms) {
            await this.storeData('symptoms', symptom);
        }
    }

    /**
     * Cache diseases for offline use
     */
    async cacheDiseases(diseases) {
        for (const disease of diseases) {
            await this.storeData('diseases', disease);
        }
    }

    /**
     * Cache drugs for offline use
     */
    async cacheDrugs(drugs) {
        for (const drug of drugs) {
            await this.storeData('drugs', drug);
        }
    }

    /**
     * Get cached symptoms
     */
    async getCachedSymptoms() {
        return await this.getAllData('symptoms');
    }

    /**
     * Get cached diseases
     */
    async getCachedDiseases() {
        return await this.getAllData('diseases');
    }

    /**
     * Get cached drugs
     */
    async getCachedDrugs() {
        return await this.getAllData('drugs');
    }

    /**
     * Get offline diagnoses
     */
    async getOfflineDiagnoses() {
        return await this.getAllData('diagnoses');
    }

    /**
     * Get offline appointments
     */
    async getOfflineAppointments() {
        return await this.getAllData('appointments');
    }

    /**
     * Get offline medical records
     */
    async getOfflineMedicalRecords() {
        return await this.getAllData('medical_records');
    }

    /**
     * Clear all offline data
     */
    async clearAllData() {
        if (!this.db) await this.init();
        
        const storeNames = ['symptoms', 'diseases', 'drugs', 'diagnoses', 'appointments', 'medical_records', 'sync_queue'];
        
        for (const storeName of storeNames) {
            const transaction = this.db.transaction([storeName], 'readwrite');
            const store = transaction.objectStore(storeName);
            await store.clear();
        }
    }
}

// Initialize offline storage
const offlineStorage = new OfflineStorage();

// Export for use in other scripts
window.OfflineStorage = OfflineStorage;
window.offlineStorage = offlineStorage;
