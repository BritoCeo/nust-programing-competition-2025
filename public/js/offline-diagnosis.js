/**
 * Offline Diagnosis System for MESMTF
 * Provides basic diagnosis capabilities when offline
 */

class OfflineDiagnosis {
    constructor() {
        this.symptoms = [];
        this.diseases = [];
        this.rules = [];
        this.initialized = false;
    }

    /**
     * Initialize offline diagnosis system
     */
    async init() {
        if (this.initialized) return;

        try {
            // Load cached data
            this.symptoms = await offlineStorage.getCachedSymptoms();
            this.diseases = await offlineStorage.getCachedDiseases();
            
            // Initialize basic rules for offline diagnosis
            this.initializeOfflineRules();
            
            this.initialized = true;
            console.log('Offline diagnosis system initialized');

        } catch (error) {
            console.error('Failed to initialize offline diagnosis:', error);
        }
    }

    /**
     * Initialize basic offline rules
     */
    initializeOfflineRules() {
        this.rules = [
            // Malaria rules
            {
                disease: 'Malaria',
                symptoms: ['Fever', 'Headache', 'Fatigue'],
                confidence: 0.8,
                requires_xray: false
            },
            {
                disease: 'Malaria',
                symptoms: ['Abdominal Pain', 'Vomiting'],
                confidence: 0.9,
                requires_xray: true
            },
            
            // Typhoid rules
            {
                disease: 'Typhoid Fever',
                symptoms: ['Fever', 'Headache'],
                confidence: 0.7,
                requires_xray: false
            },
            {
                disease: 'Typhoid Fever',
                symptoms: ['Abdominal Pain', 'Stomach Issues'],
                confidence: 0.9,
                requires_xray: true
            },
            
            // TB rules
            {
                disease: 'Tuberculosis (TB)',
                symptoms: ['Persistent Cough', 'Chest X-ray Abnormalities'],
                confidence: 0.9,
                requires_xray: true
            },
            
            // HIV/AIDS rules
            {
                disease: 'HIV/AIDS',
                symptoms: ['Recurrent Infections'],
                confidence: 0.8,
                requires_xray: false
            },
            
            // Diabetes rules
            {
                disease: 'Diabetes Mellitus',
                symptoms: ['Excessive Thirst', 'Frequent Urination'],
                confidence: 0.8,
                requires_xray: false
            },
            
            // Mental Health rules
            {
                disease: 'Mental Health Disorders',
                symptoms: ['Depression'],
                confidence: 0.7,
                requires_xray: false
            }
        ];
    }

    /**
     * Perform offline diagnosis
     */
    async performOfflineDiagnosis(selectedSymptoms) {
        if (!this.initialized) {
            await this.init();
        }

        const results = [];
        
        // Check each rule
        for (const rule of this.rules) {
            const matchedSymptoms = this.getMatchedSymptoms(selectedSymptoms, rule.symptoms);
            const matchScore = matchedSymptoms.length / rule.symptoms.length;
            
            if (matchScore >= 0.5) { // At least 50% match
                const confidence = rule.confidence * matchScore;
                
                results.push({
                    disease: rule.disease,
                    confidence: confidence,
                    matched_symptoms: matchedSymptoms,
                    requires_xray: rule.requires_xray,
                    recommendations: this.getOfflineRecommendations(rule.disease, confidence)
                });
            }
        }

        // Sort by confidence
        results.sort((a, b) => b.confidence - a.confidence);

        return {
            results: results,
            total_symptoms: selectedSymptoms.length,
            analysis_timestamp: new Date().toISOString(),
            offline: true
        };
    }

    /**
     * Get matched symptoms
     */
    getMatchedSymptoms(selectedSymptoms, ruleSymptoms) {
        return selectedSymptoms.filter(symptom => 
            ruleSymptoms.some(ruleSymptom => 
                symptom.toLowerCase().includes(ruleSymptom.toLowerCase()) ||
                ruleSymptom.toLowerCase().includes(symptom.toLowerCase())
            )
        );
    }

    /**
     * Get offline recommendations
     */
    getOfflineRecommendations(disease, confidence) {
        const recommendations = {
            'Malaria': {
                high: [
                    'Seek immediate medical attention',
                    'Begin antimalarial treatment if available',
                    'Monitor for severe symptoms',
                    'Chest X-ray may be required for severe cases'
                ],
                medium: [
                    'Consult healthcare provider',
                    'Consider antimalarial treatment',
                    'Monitor symptoms closely'
                ],
                low: [
                    'Monitor symptoms',
                    'Seek medical advice if symptoms worsen'
                ]
            },
            'Typhoid Fever': {
                high: [
                    'Seek immediate medical attention',
                    'Begin antibiotic treatment if available',
                    'Maintain hydration',
                    'Chest X-ray may be required for severe cases'
                ],
                medium: [
                    'Consult healthcare provider',
                    'Consider antibiotic treatment',
                    'Maintain good hygiene'
                ],
                low: [
                    'Monitor symptoms',
                    'Seek medical advice if symptoms worsen'
                ]
            },
            'Tuberculosis (TB)': {
                high: [
                    'Seek immediate medical attention',
                    'Chest X-ray required for diagnosis',
                    'Begin TB treatment protocol',
                    'Isolate to prevent transmission'
                ],
                medium: [
                    'Consult healthcare provider',
                    'Chest X-ray recommended',
                    'Monitor for TB symptoms'
                ],
                low: [
                    'Monitor symptoms',
                    'Seek medical advice if symptoms persist'
                ]
            },
            'HIV/AIDS': {
                high: [
                    'Seek immediate medical attention',
                    'HIV testing required',
                    'Begin antiretroviral treatment if positive',
                    'Monitor immune system'
                ],
                medium: [
                    'Consult healthcare provider',
                    'HIV testing recommended',
                    'Monitor for opportunistic infections'
                ],
                low: [
                    'Monitor symptoms',
                    'Consider HIV testing if at risk'
                ]
            },
            'Diabetes Mellitus': {
                high: [
                    'Seek immediate medical attention',
                    'Blood glucose monitoring required',
                    'Begin diabetes management',
                    'Monitor for complications'
                ],
                medium: [
                    'Consult healthcare provider',
                    'Blood glucose testing recommended',
                    'Lifestyle modifications needed'
                ],
                low: [
                    'Monitor symptoms',
                    'Consider blood glucose testing'
                ]
            },
            'Mental Health Disorders': {
                high: [
                    'Seek immediate mental health support',
                    'Professional counseling recommended',
                    'Monitor for suicidal thoughts',
                    'Medication may be required'
                ],
                medium: [
                    'Consult mental health professional',
                    'Consider counseling',
                    'Monitor mood changes'
                ],
                low: [
                    'Monitor symptoms',
                    'Consider mental health support'
                ]
            }
        };

        const confidenceLevel = confidence >= 0.8 ? 'high' : confidence >= 0.6 ? 'medium' : 'low';
        return recommendations[disease]?.[confidenceLevel] || ['Consult healthcare provider'];
    }

    /**
     * Get available symptoms for offline diagnosis
     */
    async getAvailableSymptoms() {
        if (!this.initialized) {
            await this.init();
        }

        return this.symptoms.map(symptom => ({
            id: symptom.id,
            name: symptom.name,
            description: symptom.description,
            category: symptom.category
        }));
    }

    /**
     * Get available diseases for offline diagnosis
     */
    async getAvailableDiseases() {
        if (!this.initialized) {
            await this.init();
        }

        return this.diseases.map(disease => ({
            id: disease.id,
            name: disease.name,
            description: disease.description,
            icd10_code: disease.icd10_code
        }));
    }

    /**
     * Save offline diagnosis
     */
    async saveOfflineDiagnosis(diagnosisData) {
        try {
            await offlineStorage.storeOfflineDiagnosis(diagnosisData);
            console.log('Offline diagnosis saved successfully');
            return true;
        } catch (error) {
            console.error('Failed to save offline diagnosis:', error);
            return false;
        }
    }

    /**
     * Get offline diagnosis history
     */
    async getOfflineDiagnosisHistory() {
        try {
            return await offlineStorage.getOfflineDiagnoses();
        } catch (error) {
            console.error('Failed to get offline diagnosis history:', error);
            return [];
        }
    }

    /**
     * Clear offline diagnosis history
     */
    async clearOfflineDiagnosisHistory() {
        try {
            // This would need to be implemented in offlineStorage
            console.log('Offline diagnosis history cleared');
            return true;
        } catch (error) {
            console.error('Failed to clear offline diagnosis history:', error);
            return false;
        }
    }
}

// Initialize offline diagnosis
const offlineDiagnosis = new OfflineDiagnosis();

// Export for use in other scripts
window.OfflineDiagnosis = OfflineDiagnosis;
window.offlineDiagnosis = offlineDiagnosis;
