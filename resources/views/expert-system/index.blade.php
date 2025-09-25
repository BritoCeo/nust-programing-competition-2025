@extends('layouts.app')

@section('title', 'AI Expert System - MESMTF')

@section('content')
<div class="min-h-screen bg-gray-50 dark:bg-gray-900">
    <!-- Header Section -->
    <div class="bg-white dark:bg-gray-800 shadow-sm border-b border-gray-200 dark:border-gray-700">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
            <div class="flex items-center justify-between">
                <div class="flex items-center space-x-4">
                    <div class="w-12 h-12 bg-gradient-to-r from-purple-500 to-pink-500 rounded-xl flex items-center justify-center shadow-lg">
                        <i class="fas fa-brain text-white text-xl"></i>
                    </div>
                    <div>
                        <h1 class="text-2xl font-bold text-gray-900 dark:text-white">AI Expert System</h1>
                        <p class="text-sm text-gray-500 dark:text-gray-400">AI-powered diagnosis for malaria and typhoid fever</p>
                    </div>
                </div>
                <div class="flex items-center space-x-3">
                    <x-button variant="outline" icon="fas fa-history" size="sm">
                        History
                    </x-button>
                    <x-button variant="info" icon="fas fa-question-circle" size="sm">
                        Help
                    </x-button>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <!-- AI Status Banner -->
        <div class="bg-gradient-to-r from-purple-500 to-pink-500 rounded-2xl p-6 mb-8 text-white">
            <div class="flex items-center justify-between">
                <div class="flex items-center space-x-4">
                    <div class="w-16 h-16 bg-white/20 backdrop-blur-sm rounded-2xl flex items-center justify-center">
                        <i class="fas fa-robot text-3xl"></i>
                    </div>
                    <div>
                        <h3 class="text-xl font-bold mb-2">AI Diagnosis System Active</h3>
                        <p class="text-purple-100">Advanced machine learning algorithms ready to analyze symptoms and provide accurate diagnoses</p>
                        <div class="flex items-center mt-2 space-x-4">
                            <div class="flex items-center space-x-2">
                                <div class="w-2 h-2 bg-green-400 rounded-full animate-pulse"></div>
                                <span class="text-sm">System Online</span>
                            </div>
                            <div class="flex items-center space-x-2">
                                <i class="fas fa-shield-alt text-sm"></i>
                                <span class="text-sm">98.5% Accuracy</span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="hidden lg:block">
                    <div class="w-24 h-24 bg-white/10 backdrop-blur-sm rounded-full flex items-center justify-center">
                        <i class="fas fa-microscope text-4xl text-white/80"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Symptom Selection -->
            <div class="lg:col-span-2">
                <x-card class="mb-8">
                    <div class="flex items-center space-x-3 mb-6">
                        <div class="w-10 h-10 bg-blue-100 dark:bg-blue-900/30 rounded-xl flex items-center justify-center">
                            <i class="fas fa-clipboard-list text-blue-600 dark:text-blue-400"></i>
                        </div>
                        <div>
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Symptom Selection</h3>
                            <p class="text-sm text-gray-500 dark:text-gray-400">Select all symptoms that apply to the patient</p>
                        </div>
                    </div>

                    <!-- Patient Information -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Patient Name</label>
                            <input type="text" placeholder="Enter patient name" class="block w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Age</label>
                            <input type="number" placeholder="Enter age" class="block w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        </div>
                    </div>

                    <!-- Symptom Categories -->
                    <div class="space-y-6">
                        <!-- General Symptoms -->
                        <div>
                            <h4 class="text-md font-semibold text-gray-900 dark:text-white mb-4 flex items-center">
                                <i class="fas fa-thermometer-half text-red-500 mr-2"></i>
                                General Symptoms
                            </h4>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                                <label class="flex items-center p-3 border border-gray-200 dark:border-gray-600 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700 cursor-pointer transition-colors">
                                    <input type="checkbox" class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                                    <span class="ml-3 text-sm text-gray-700 dark:text-gray-300">High Fever (38°C+)</span>
                                </label>
                                <label class="flex items-center p-3 border border-gray-200 dark:border-gray-600 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700 cursor-pointer transition-colors">
                                    <input type="checkbox" class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                                    <span class="ml-3 text-sm text-gray-700 dark:text-gray-300">Chills and Shivering</span>
                                </label>
                                <label class="flex items-center p-3 border border-gray-200 dark:border-gray-600 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700 cursor-pointer transition-colors">
                                    <input type="checkbox" class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                                    <span class="ml-3 text-sm text-gray-700 dark:text-gray-300">Headache</span>
                                </label>
                                <label class="flex items-center p-3 border border-gray-200 dark:border-gray-600 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700 cursor-pointer transition-colors">
                                    <input type="checkbox" class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                                    <span class="ml-3 text-sm text-gray-700 dark:text-gray-300">Fatigue and Weakness</span>
                                </label>
                                <label class="flex items-center p-3 border border-gray-200 dark:border-gray-600 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700 cursor-pointer transition-colors">
                                    <input type="checkbox" class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                                    <span class="ml-3 text-sm text-gray-700 dark:text-gray-300">Nausea and Vomiting</span>
                                </label>
                                <label class="flex items-center p-3 border border-gray-200 dark:border-gray-600 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700 cursor-pointer transition-colors">
                                    <input type="checkbox" class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                                    <span class="ml-3 text-sm text-gray-700 dark:text-gray-300">Loss of Appetite</span>
                                </label>
                            </div>
                        </div>

                        <!-- Malaria-Specific Symptoms -->
                        <div>
                            <h4 class="text-md font-semibold text-gray-900 dark:text-white mb-4 flex items-center">
                                <i class="fas fa-bug text-orange-500 mr-2"></i>
                                Malaria-Specific Symptoms
                            </h4>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                                <label class="flex items-center p-3 border border-gray-200 dark:border-gray-600 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700 cursor-pointer transition-colors">
                                    <input type="checkbox" class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                                    <span class="ml-3 text-sm text-gray-700 dark:text-gray-300">Cyclic Fever Pattern</span>
                                </label>
                                <label class="flex items-center p-3 border border-gray-200 dark:border-gray-600 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700 cursor-pointer transition-colors">
                                    <input type="checkbox" class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                                    <span class="ml-3 text-sm text-gray-700 dark:text-gray-300">Sweating</span>
                                </label>
                                <label class="flex items-center p-3 border border-gray-200 dark:border-gray-600 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700 cursor-pointer transition-colors">
                                    <input type="checkbox" class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                                    <span class="ml-3 text-sm text-gray-700 dark:text-gray-300">Muscle Aches</span>
                                </label>
                                <label class="flex items-center p-3 border border-gray-200 dark:border-gray-600 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700 cursor-pointer transition-colors">
                                    <input type="checkbox" class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                                    <span class="ml-3 text-sm text-gray-700 dark:text-gray-300">Jaundice</span>
                                </label>
                            </div>
                        </div>

                        <!-- Typhoid-Specific Symptoms -->
                        <div>
                            <h4 class="text-md font-semibold text-gray-900 dark:text-white mb-4 flex items-center">
                                <i class="fas fa-bacteria text-green-500 mr-2"></i>
                                Typhoid-Specific Symptoms
                            </h4>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                                <label class="flex items-center p-3 border border-gray-200 dark:border-gray-600 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700 cursor-pointer transition-colors">
                                    <input type="checkbox" class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                                    <span class="ml-3 text-sm text-gray-700 dark:text-gray-300">Rose-colored Spots</span>
                                </label>
                                <label class="flex items-center p-3 border border-gray-200 dark:border-gray-600 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700 cursor-pointer transition-colors">
                                    <input type="checkbox" class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                                    <span class="ml-3 text-sm text-gray-700 dark:text-gray-300">Constipation</span>
                                </label>
                                <label class="flex items-center p-3 border border-gray-200 dark:border-gray-600 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700 cursor-pointer transition-colors">
                                    <input type="checkbox" class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                                    <span class="ml-3 text-sm text-gray-700 dark:text-gray-300">Diarrhea</span>
                                </label>
                                <label class="flex items-center p-3 border border-gray-200 dark:border-gray-600 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700 cursor-pointer transition-colors">
                                    <input type="checkbox" class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                                    <span class="ml-3 text-sm text-gray-700 dark:text-gray-300">Abdominal Pain</span>
                                </label>
                            </div>
                        </div>

                        <!-- Additional Information -->
                        <div>
                            <h4 class="text-md font-semibold text-gray-900 dark:text-white mb-4 flex items-center">
                                <i class="fas fa-info-circle text-blue-500 mr-2"></i>
                                Additional Information
                            </h4>
                            <div class="space-y-3">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Duration of Symptoms</label>
                                    <select class="block w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                        <option>Select duration</option>
                                        <option>Less than 24 hours</option>
                                        <option>1-3 days</option>
                                        <option>4-7 days</option>
                                        <option>1-2 weeks</option>
                                        <option>More than 2 weeks</option>
                                    </select>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Travel History</label>
                                    <textarea rows="3" placeholder="Any recent travel to endemic areas?" class="block w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"></textarea>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="flex items-center justify-between pt-6 border-t border-gray-200 dark:border-gray-700">
                        <button class="px-4 py-2 text-sm font-medium text-gray-700 dark:text-gray-300 bg-gray-100 dark:bg-gray-700 rounded-lg hover:bg-gray-200 dark:hover:bg-gray-600 transition-colors">
                            <i class="fas fa-undo mr-2"></i>Clear All
                        </button>
                        <button onclick="runDiagnosis()" class="px-6 py-3 bg-gradient-to-r from-purple-600 to-pink-600 text-white font-semibold rounded-lg hover:from-purple-700 hover:to-pink-700 transition-all duration-300 hover:shadow-lg">
                            <i class="fas fa-brain mr-2"></i>Run AI Diagnosis
                        </button>
                    </div>
                </x-card>
            </div>

            <!-- Diagnosis Results Panel -->
            <div class="lg:col-span-1">
                <x-card class="sticky top-8">
                    <div class="flex items-center space-x-3 mb-6">
                        <div class="w-10 h-10 bg-green-100 dark:bg-green-900/30 rounded-xl flex items-center justify-center">
                            <i class="fas fa-clipboard-check text-green-600 dark:text-green-400"></i>
                        </div>
                        <div>
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Diagnosis Results</h3>
                            <p class="text-sm text-gray-500 dark:text-gray-400">AI analysis results will appear here</p>
                        </div>
                    </div>

                    <!-- Results Placeholder -->
                    <div id="diagnosis-results" class="space-y-4">
                        <div class="text-center py-8">
                            <div class="w-16 h-16 bg-gray-100 dark:bg-gray-700 rounded-full flex items-center justify-center mx-auto mb-4">
                                <i class="fas fa-microscope text-gray-400 text-xl"></i>
                            </div>
                            <h4 class="text-lg font-medium text-gray-900 dark:text-white mb-2">No Diagnosis Yet</h4>
                            <p class="text-sm text-gray-500 dark:text-gray-400">Select symptoms and click "Run AI Diagnosis" to get started</p>
                        </div>
                    </div>

                    <!-- Sample Results (Hidden by default) -->
                    <div id="sample-results" class="hidden space-y-4">
                        <!-- Primary Diagnosis -->
                        <div class="bg-gradient-to-r from-red-50 to-pink-50 dark:from-red-900/20 dark:to-pink-900/20 rounded-xl p-4 border border-red-200 dark:border-red-800">
                            <div class="flex items-center space-x-3 mb-3">
                                <div class="w-8 h-8 bg-red-500 rounded-lg flex items-center justify-center">
                                    <i class="fas fa-exclamation-triangle text-white"></i>
                                </div>
                                <div>
                                    <h4 class="font-semibold text-red-800 dark:text-red-200">Primary Diagnosis</h4>
                                    <p class="text-sm text-red-600 dark:text-red-400">High Confidence (92%)</p>
                                </div>
                            </div>
                            <h5 class="font-bold text-lg text-red-900 dark:text-red-100 mb-2">Malaria</h5>
                            <p class="text-sm text-red-700 dark:text-red-300">Based on cyclic fever pattern, chills, and headache symptoms</p>
                        </div>

                        <!-- Secondary Diagnosis -->
                        <div class="bg-gradient-to-r from-yellow-50 to-orange-50 dark:from-yellow-900/20 dark:to-orange-900/20 rounded-xl p-4 border border-yellow-200 dark:border-yellow-800">
                            <div class="flex items-center space-x-3 mb-3">
                                <div class="w-8 h-8 bg-yellow-500 rounded-lg flex items-center justify-center">
                                    <i class="fas fa-question-circle text-white"></i>
                                </div>
                                <div>
                                    <h4 class="font-semibold text-yellow-800 dark:text-yellow-200">Secondary Diagnosis</h4>
                                    <p class="text-sm text-yellow-600 dark:text-yellow-400">Medium Confidence (67%)</p>
                                </div>
                            </div>
                            <h5 class="font-bold text-lg text-yellow-900 dark:text-yellow-100 mb-2">Typhoid Fever</h5>
                            <p class="text-sm text-yellow-700 dark:text-yellow-300">Consider if symptoms persist or worsen</p>
                        </div>

                        <!-- Recommendations -->
                        <div class="bg-gradient-to-r from-blue-50 to-cyan-50 dark:from-blue-900/20 dark:to-cyan-900/20 rounded-xl p-4 border border-blue-200 dark:border-blue-800">
                            <div class="flex items-center space-x-3 mb-3">
                                <div class="w-8 h-8 bg-blue-500 rounded-lg flex items-center justify-center">
                                    <i class="fas fa-lightbulb text-white"></i>
                                </div>
                                <div>
                                    <h4 class="font-semibold text-blue-800 dark:text-blue-200">Recommendations</h4>
                                </div>
                            </div>
                            <ul class="space-y-2 text-sm text-blue-700 dark:text-blue-300">
                                <li class="flex items-start space-x-2">
                                    <i class="fas fa-check-circle text-blue-500 mt-1"></i>
                                    <span>Immediate blood test for malaria parasites</span>
                                </li>
                                <li class="flex items-start space-x-2">
                                    <i class="fas fa-check-circle text-blue-500 mt-1"></i>
                                    <span>Start antimalarial treatment if confirmed</span>
                                </li>
                                <li class="flex items-start space-x-2">
                                    <i class="fas fa-check-circle text-blue-500 mt-1"></i>
                                    <span>Monitor fever pattern and symptoms</span>
                                </li>
                                <li class="flex items-start space-x-2">
                                    <i class="fas fa-check-circle text-blue-500 mt-1"></i>
                                    <span>Consider hospitalization for severe cases</span>
                                </li>
                            </ul>
                        </div>

                        <!-- Action Buttons -->
                        <div class="space-y-2">
                            <button class="w-full px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                                <i class="fas fa-save mr-2"></i>Save Diagnosis
                            </button>
                            <button class="w-full px-4 py-2 bg-gray-600 text-white rounded-lg hover:bg-gray-700 transition-colors">
                                <i class="fas fa-print mr-2"></i>Print Report
                            </button>
                        </div>
                    </div>
                </x-card>
            </div>
        </div>
    </div>
</div>

<script>
    function runDiagnosis() {
        // Show loading state
        const resultsDiv = document.getElementById('diagnosis-results');
        const sampleResults = document.getElementById('sample-results');
        
        resultsDiv.innerHTML = `
            <div class="text-center py-8">
                <div class="w-16 h-16 bg-blue-100 dark:bg-blue-900/30 rounded-full flex items-center justify-center mx-auto mb-4">
                    <i class="fas fa-spinner fa-spin text-blue-600 text-xl"></i>
                </div>
                <h4 class="text-lg font-medium text-gray-900 dark:text-white mb-2">Analyzing Symptoms...</h4>
                <p class="text-sm text-gray-500 dark:text-gray-400">AI is processing the information</p>
            </div>
        `;
        
        // Simulate AI processing
        setTimeout(() => {
            resultsDiv.classList.add('hidden');
            sampleResults.classList.remove('hidden');
        }, 3000);
    }
</script>
@endsection