<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('AI Expert System - Malaria & Typhoid Diagnosis') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- System Statistics -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-6">
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <div class="text-2xl font-bold text-blue-600 dark:text-blue-400">{{ $statistics['total_rules'] }}</div>
                        <div class="text-sm text-gray-600 dark:text-gray-400">Expert Rules</div>
                    </div>
                </div>
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <div class="text-2xl font-bold text-green-600 dark:text-green-400">{{ $statistics['total_diseases'] }}</div>
                        <div class="text-sm text-gray-600 dark:text-gray-400">Diseases Tracked</div>
                    </div>
                </div>
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <div class="text-2xl font-bold text-purple-600 dark:text-purple-400">{{ $statistics['total_symptoms'] }}</div>
                        <div class="text-sm text-gray-600 dark:text-gray-400">Symptoms Database</div>
                    </div>
                </div>
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <div class="text-2xl font-bold text-orange-600 dark:text-orange-400">{{ round($statistics['system_accuracy'] * 100) }}%</div>
                        <div class="text-sm text-gray-600 dark:text-gray-400">System Accuracy</div>
                    </div>
                </div>
            </div>

            <!-- Main Expert System Interface -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <!-- Symptom Selection Panel -->
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4">
                            <i class="fas fa-stethoscope mr-2"></i>Select Symptoms
                        </h3>
                        
                        <!-- Patient Selection -->
                        <div class="mb-4">
                            <label for="patient-select" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Select Patient
                            </label>
                            <select id="patient-select" class="w-full border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 focus:ring-indigo-500 focus:border-indigo-500 rounded-md shadow-sm">
                                <option value="">Choose a patient...</option>
                                @foreach(\App\Models\User::role('patient')->get() as $patient)
                                    <option value="{{ $patient->id }}">{{ $patient->name }} ({{ $patient->email }})</option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Symptom Category Filter -->
                        <div class="mb-4">
                            <label for="symptom-category" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Filter by Category
                            </label>
                            <select id="symptom-category" class="w-full border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 focus:ring-indigo-500 focus:border-indigo-500 rounded-md shadow-sm">
                                <option value="all">All Categories</option>
                                <option value="fever">Fever</option>
                                <option value="gastrointestinal">Gastrointestinal</option>
                                <option value="neurological">Neurological</option>
                                <option value="respiratory">Respiratory</option>
                            </select>
                        </div>

                        <!-- Symptoms List -->
                        <div class="max-h-96 overflow-y-auto border border-gray-300 dark:border-gray-600 rounded-md p-4">
                            <div id="symptoms-list" class="space-y-2">
                                @foreach($symptoms as $symptom)
                                <label class="flex items-center p-2 hover:bg-gray-50 dark:hover:bg-gray-700 rounded cursor-pointer">
                                    <input type="checkbox" 
                                           value="{{ $symptom->id }}" 
                                           class="symptom-checkbox rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500"
                                           data-category="{{ $symptom->category }}">
                                    <div class="ml-3">
                                        <div class="text-sm font-medium text-gray-900 dark:text-gray-100">
                                            {{ $symptom->name }}
                                        </div>
                                        <div class="text-xs text-gray-500 dark:text-gray-400">
                                            {{ $symptom->description }}
                                        </div>
                                        <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full
                                            {{ $symptom->severity_level === 'critical' ? 'bg-red-100 text-red-800' : 
                                               ($symptom->severity_level === 'severe' ? 'bg-orange-100 text-orange-800' : 
                                               ($symptom->severity_level === 'moderate' ? 'bg-yellow-100 text-yellow-800' : 'bg-green-100 text-green-800')) }}">
                                            {{ ucfirst($symptom->severity_level) }}
                                        </span>
                                    </div>
                                </label>
                                @endforeach
                            </div>
                        </div>

                        <!-- Clinical Notes -->
                        <div class="mt-4">
                            <label for="clinical-notes" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Clinical Notes (Optional)
                            </label>
                            <textarea id="clinical-notes" 
                                      rows="3" 
                                      placeholder="Additional clinical observations..."
                                      class="w-full border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 focus:ring-indigo-500 focus:border-indigo-500 rounded-md shadow-sm"></textarea>
                        </div>

                        <!-- Analyze Button -->
                        <div class="mt-6">
                            <button id="analyze-btn" 
                                    class="w-full bg-indigo-600 border border-transparent rounded-md py-2 px-4 flex items-center justify-center text-sm font-medium text-white hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 disabled:opacity-50 disabled:cursor-not-allowed">
                                <i class="fas fa-brain mr-2"></i>
                                Analyze Symptoms
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Analysis Results Panel -->
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4">
                            <i class="fas fa-microscope mr-2"></i>Analysis Results
                        </h3>
                        
                        <!-- Loading State -->
                        <div id="loading-state" class="hidden text-center py-8">
                            <div class="animate-spin rounded-full h-12 w-12 border-b-2 border-indigo-600 mx-auto"></div>
                            <p class="mt-4 text-gray-600 dark:text-gray-400">Analyzing symptoms...</p>
                        </div>

                        <!-- Results Container -->
                        <div id="results-container" class="hidden">
                            <!-- Primary Diagnosis -->
                            <div id="primary-diagnosis" class="mb-6">
                                <h4 class="text-md font-semibold text-gray-900 dark:text-gray-100 mb-3">Primary Diagnosis</h4>
                                <div id="primary-diagnosis-content" class="bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 rounded-lg p-4">
                                    <!-- Dynamic content will be inserted here -->
                                </div>
                            </div>

                            <!-- Differential Diagnoses -->
                            <div id="differential-diagnoses" class="mb-6">
                                <h4 class="text-md font-semibold text-gray-900 dark:text-gray-100 mb-3">Differential Diagnoses</h4>
                                <div id="differential-diagnoses-content" class="space-y-3">
                                    <!-- Dynamic content will be inserted here -->
                                </div>
                            </div>

                            <!-- Recommended Tests -->
                            <div id="recommended-tests" class="mb-6">
                                <h4 class="text-md font-semibold text-gray-900 dark:text-gray-100 mb-3">Recommended Tests</h4>
                                <div id="recommended-tests-content" class="bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800 rounded-lg p-4">
                                    <!-- Dynamic content will be inserted here -->
                                </div>
                            </div>

                            <!-- Action Buttons -->
                            <div class="flex space-x-3">
                                <button id="save-diagnosis-btn" 
                                        class="flex-1 bg-green-600 border border-transparent rounded-md py-2 px-4 text-sm font-medium text-white hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                                    <i class="fas fa-save mr-2"></i>Save Diagnosis
                                </button>
                                <button id="export-report-btn" 
                                        class="flex-1 bg-purple-600 border border-transparent rounded-md py-2 px-4 text-sm font-medium text-white hover:bg-purple-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-purple-500">
                                    <i class="fas fa-download mr-2"></i>Export Report
                                </button>
                            </div>
                        </div>

                        <!-- No Results State -->
                        <div id="no-results-state" class="hidden text-center py-8">
                            <div class="text-gray-500 dark:text-gray-400">
                                <i class="fas fa-search text-4xl mb-4"></i>
                                <p>Select symptoms and click "Analyze Symptoms" to get AI-powered diagnosis recommendations.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Recent Diagnoses -->
            <div class="mt-6">
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4">
                            <i class="fas fa-history mr-2"></i>Recent Diagnoses
                        </h3>
                        <div id="recent-diagnoses" class="space-y-3">
                            <!-- Dynamic content will be loaded here -->
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const patientSelect = document.getElementById('patient-select');
            const symptomCategory = document.getElementById('symptom-category');
            const symptomsList = document.getElementById('symptoms-list');
            const analyzeBtn = document.getElementById('analyze-btn');
            const loadingState = document.getElementById('loading-state');
            const resultsContainer = document.getElementById('results-container');
            const noResultsState = document.getElementById('no-results-state');
            const recentDiagnoses = document.getElementById('recent-diagnoses');

            // Filter symptoms by category
            symptomCategory.addEventListener('change', function() {
                const category = this.value;
                const checkboxes = symptomsList.querySelectorAll('.symptom-checkbox');
                
                checkboxes.forEach(checkbox => {
                    const symptomItem = checkbox.closest('label');
                    if (category === 'all' || checkbox.dataset.category === category) {
                        symptomItem.style.display = 'flex';
                    } else {
                        symptomItem.style.display = 'none';
                    }
                });
            });

            // Analyze symptoms
            analyzeBtn.addEventListener('click', function() {
                const selectedSymptoms = Array.from(document.querySelectorAll('.symptom-checkbox:checked'))
                    .map(cb => parseInt(cb.value));
                const patientId = patientSelect.value;
                const clinicalNotes = document.getElementById('clinical-notes').value;

                if (selectedSymptoms.length === 0) {
                    alert('Please select at least one symptom.');
                    return;
                }

                if (!patientId) {
                    alert('Please select a patient.');
                    return;
                }

                // Show loading state
                loadingState.classList.remove('hidden');
                resultsContainer.classList.add('hidden');
                noResultsState.classList.add('hidden');

                // Make API call
                fetch('{{ route("expert-system.analyze") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify({
                        symptoms: selectedSymptoms,
                        patient_id: parseInt(patientId),
                        clinical_notes: clinicalNotes
                    })
                })
                .then(response => response.json())
                .then(data => {
                    loadingState.classList.add('hidden');
                    
                    if (data.success) {
                        displayResults(data.analysis);
                        resultsContainer.classList.remove('hidden');
                    } else {
                        alert('Analysis failed: ' + (data.message || 'Unknown error'));
                        noResultsState.classList.remove('hidden');
                    }
                })
                .catch(error => {
                    loadingState.classList.add('hidden');
                    noResultsState.classList.remove('hidden');
                    console.error('Error:', error);
                    alert('Analysis failed. Please try again.');
                });
            });

            // Display analysis results
            function displayResults(analysis) {
                const primaryDiagnosis = document.getElementById('primary-diagnosis-content');
                const differentialDiagnoses = document.getElementById('differential-diagnoses-content');
                const recommendedTests = document.getElementById('recommended-tests-content');

                if (analysis.recommendations.length > 0) {
                    const primary = analysis.recommendations[0];
                    
                    // Primary diagnosis
                    primaryDiagnosis.innerHTML = `
                        <div class="flex justify-between items-start mb-2">
                            <h5 class="font-semibold text-lg">${primary.disease_name}</h5>
                            <span class="px-3 py-1 text-sm font-semibold rounded-full
                                ${primary.confidence_level === 'very_high' ? 'bg-green-100 text-green-800' : 
                                  primary.confidence_level === 'high' ? 'bg-blue-100 text-blue-800' : 
                                  primary.confidence_level === 'medium' ? 'bg-yellow-100 text-yellow-800' : 'bg-red-100 text-red-800'}">
                                ${Math.round(primary.confidence_score * 100)}% Confidence
                            </span>
                        </div>
                        <p class="text-sm text-gray-600 dark:text-gray-400 mb-2">${primary.rule_description}</p>
                        <p class="text-sm text-gray-600 dark:text-gray-400">ICD-10: ${primary.icd10_code}</p>
                    `;

                    // Differential diagnoses
                    if (analysis.recommendations.length > 1) {
                        differentialDiagnoses.innerHTML = analysis.recommendations.slice(1, 4).map(rec => `
                            <div class="bg-gray-50 dark:bg-gray-700 border border-gray-200 dark:border-gray-600 rounded-lg p-3">
                                <div class="flex justify-between items-center">
                                    <span class="font-medium">${rec.disease_name}</span>
                                    <span class="text-sm text-gray-500">${Math.round(rec.confidence_score * 100)}%</span>
                                </div>
                            </div>
                        `).join('');
                    }

                    // Recommended tests
                    recommendedTests.innerHTML = `
                        <ul class="space-y-1">
                            ${primary.recommended_tests.map(test => `<li class="text-sm">• ${test}</li>`).join('')}
                        </ul>
                    `;
                }
            }

            // Load recent diagnoses
            function loadRecentDiagnoses() {
                fetch('{{ route("expert-system.patient-diagnoses") }}?patient_id=' + (patientSelect.value || ''))
                .then(response => response.json())
                .then(data => {
                    if (data.success && data.diagnoses.length > 0) {
                        recentDiagnoses.innerHTML = data.diagnoses.map(diagnosis => `
                            <div class="bg-gray-50 dark:bg-gray-700 border border-gray-200 dark:border-gray-600 rounded-lg p-3">
                                <div class="flex justify-between items-center">
                                    <div>
                                        <span class="font-medium">${diagnosis.disease.name}</span>
                                        <span class="text-sm text-gray-500 ml-2">${diagnosis.diagnosis_date}</span>
                                    </div>
                                    <span class="px-2 py-1 text-xs font-semibold rounded-full
                                        ${diagnosis.status === 'confirmed' ? 'bg-green-100 text-green-800' : 
                                          diagnosis.status === 'tentative' ? 'bg-yellow-100 text-yellow-800' : 'bg-red-100 text-red-800'}">
                                        ${diagnosis.status}
                                    </span>
                                </div>
                            </div>
                        `).join('');
                    } else {
                        recentDiagnoses.innerHTML = '<p class="text-gray-500 text-center py-4">No recent diagnoses found.</p>';
                    }
                });
            }

            // Load recent diagnoses when patient is selected
            patientSelect.addEventListener('change', loadRecentDiagnoses);
        });
    </script>
    @endpush
</x-app-layout>
