@extends('layouts.app')

@section('title', 'Reports & Analytics - MESMTF')

@section('content')
<div class="min-h-screen bg-gray-50 dark:bg-gray-900">
    <!-- Header Section -->
    <div class="bg-white dark:bg-gray-800 shadow-sm border-b border-gray-200 dark:border-gray-700">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
            <div class="flex items-center justify-between">
                <div class="flex items-center space-x-4">
                    <div class="w-12 h-12 bg-gradient-to-r from-indigo-500 to-purple-500 rounded-xl flex items-center justify-center shadow-lg">
                        <i class="fas fa-chart-bar text-white text-xl"></i>
                    </div>
                    <div>
                        <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Reports & Analytics</h1>
                        <p class="text-sm text-gray-500 dark:text-gray-400">Generate comprehensive medical reports and analytics</p>
                    </div>
                </div>
                <div class="flex items-center space-x-3">
                    <x-button variant="outline" icon="fas fa-download" size="sm">
                        Export All
                    </x-button>
                    <x-button variant="primary" icon="fas fa-plus" size="sm" onclick="openModal('generate-report-modal')">
                        Generate Report
                    </x-button>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <!-- Key Metrics -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
            <x-card class="text-center">
                <div class="w-12 h-12 bg-blue-100 dark:bg-blue-900/30 rounded-xl flex items-center justify-center mx-auto mb-3">
                    <i class="fas fa-users text-blue-600 dark:text-blue-400 text-xl"></i>
                </div>
                <h3 class="text-2xl font-bold text-gray-900 dark:text-white mb-1">1,247</h3>
                <p class="text-sm text-gray-500 dark:text-gray-400">Total Patients</p>
                <div class="flex items-center justify-center mt-2">
                    <i class="fas fa-arrow-up text-green-500 text-xs mr-1"></i>
                    <span class="text-xs text-green-600">+12%</span>
                </div>
            </x-card>
            
            <x-card class="text-center">
                <div class="w-12 h-12 bg-green-100 dark:bg-green-900/30 rounded-xl flex items-center justify-center mx-auto mb-3">
                    <i class="fas fa-stethoscope text-green-600 dark:text-green-400 text-xl"></i>
                </div>
                <h3 class="text-2xl font-bold text-gray-900 dark:text-white mb-1">892</h3>
                <p class="text-sm text-gray-500 dark:text-gray-400">Diagnoses Made</p>
                <div class="flex items-center justify-center mt-2">
                    <i class="fas fa-arrow-up text-green-500 text-xs mr-1"></i>
                    <span class="text-xs text-green-600">+8%</span>
                </div>
            </x-card>
            
            <x-card class="text-center">
                <div class="w-12 h-12 bg-yellow-100 dark:bg-yellow-900/30 rounded-xl flex items-center justify-center mx-auto mb-3">
                    <i class="fas fa-calendar-check text-yellow-600 dark:text-yellow-400 text-xl"></i>
                </div>
                <h3 class="text-2xl font-bold text-gray-900 dark:text-white mb-1">156</h3>
                <p class="text-sm text-gray-500 dark:text-gray-400">Appointments Today</p>
                <div class="flex items-center justify-center mt-2">
                    <i class="fas fa-arrow-down text-red-500 text-xs mr-1"></i>
                    <span class="text-xs text-red-600">-3%</span>
                </div>
            </x-card>
            
            <x-card class="text-center">
                <div class="w-12 h-12 bg-purple-100 dark:bg-purple-900/30 rounded-xl flex items-center justify-center mx-auto mb-3">
                    <i class="fas fa-pills text-purple-600 dark:text-purple-400 text-xl"></i>
                </div>
                <h3 class="text-2xl font-bold text-gray-900 dark:text-white mb-1">2,341</h3>
                <p class="text-sm text-gray-500 dark:text-gray-400">Prescriptions</p>
                <div class="flex items-center justify-center mt-2">
                    <i class="fas fa-arrow-up text-green-500 text-xs mr-1"></i>
                    <span class="text-xs text-green-600">+15%</span>
                </div>
            </x-card>
        </div>

        <!-- Report Categories -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-8">
            <!-- Medical Reports -->
            <x-card>
                <div class="flex items-center space-x-3 mb-6">
                    <div class="w-10 h-10 bg-blue-100 dark:bg-blue-900/30 rounded-xl flex items-center justify-center">
                        <i class="fas fa-file-medical text-blue-600 dark:text-blue-400"></i>
                    </div>
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Medical Reports</h3>
                        <p class="text-sm text-gray-500 dark:text-gray-400">Patient and diagnosis reports</p>
                    </div>
                </div>
                
                <div class="space-y-3">
                    <div class="flex items-center justify-between p-3 bg-gray-50 dark:bg-gray-700 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-600 cursor-pointer transition-colors">
                        <div class="flex items-center space-x-3">
                            <i class="fas fa-user-injured text-blue-500"></i>
                            <span class="text-sm font-medium text-gray-900 dark:text-white">Patient Demographics</span>
                        </div>
                        <i class="fas fa-chevron-right text-gray-400"></i>
                    </div>
                    
                    <div class="flex items-center justify-between p-3 bg-gray-50 dark:bg-gray-700 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-600 cursor-pointer transition-colors">
                        <div class="flex items-center space-x-3">
                            <i class="fas fa-diagnoses text-green-500"></i>
                            <span class="text-sm font-medium text-gray-900 dark:text-white">Diagnosis Summary</span>
                        </div>
                        <i class="fas fa-chevron-right text-gray-400"></i>
                    </div>
                    
                    <div class="flex items-center justify-between p-3 bg-gray-50 dark:bg-gray-700 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-600 cursor-pointer transition-colors">
                        <div class="flex items-center space-x-3">
                            <i class="fas fa-chart-line text-purple-500"></i>
                            <span class="text-sm font-medium text-gray-900 dark:text-white">Disease Trends</span>
                        </div>
                        <i class="fas fa-chevron-right text-gray-400"></i>
                    </div>
                    
                    <div class="flex items-center justify-between p-3 bg-gray-50 dark:bg-gray-700 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-600 cursor-pointer transition-colors">
                        <div class="flex items-center space-x-3">
                            <i class="fas fa-brain text-orange-500"></i>
                            <span class="text-sm font-medium text-gray-900 dark:text-white">AI Diagnosis Accuracy</span>
                        </div>
                        <i class="fas fa-chevron-right text-gray-400"></i>
                    </div>
                </div>
            </x-card>

            <!-- Operational Reports -->
            <x-card>
                <div class="flex items-center space-x-3 mb-6">
                    <div class="w-10 h-10 bg-green-100 dark:bg-green-900/30 rounded-xl flex items-center justify-center">
                        <i class="fas fa-cogs text-green-600 dark:text-green-400"></i>
                    </div>
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Operational Reports</h3>
                        <p class="text-sm text-gray-500 dark:text-gray-400">System and performance reports</p>
                    </div>
                </div>
                
                <div class="space-y-3">
                    <div class="flex items-center justify-between p-3 bg-gray-50 dark:bg-gray-700 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-600 cursor-pointer transition-colors">
                        <div class="flex items-center space-x-3">
                            <i class="fas fa-calendar-alt text-blue-500"></i>
                            <span class="text-sm font-medium text-gray-900 dark:text-white">Appointment Statistics</span>
                        </div>
                        <i class="fas fa-chevron-right text-gray-400"></i>
                    </div>
                    
                    <div class="flex items-center justify-between p-3 bg-gray-50 dark:bg-gray-700 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-600 cursor-pointer transition-colors">
                        <div class="flex items-center space-x-3">
                            <i class="fas fa-pills text-green-500"></i>
                            <span class="text-sm font-medium text-gray-900 dark:text-white">Pharmacy Inventory</span>
                        </div>
                        <i class="fas fa-chevron-right text-gray-400"></i>
                    </div>
                    
                    <div class="flex items-center justify-between p-3 bg-gray-50 dark:bg-gray-700 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-600 cursor-pointer transition-colors">
                        <div class="flex items-center space-x-3">
                            <i class="fas fa-user-md text-purple-500"></i>
                            <span class="text-sm font-medium text-gray-900 dark:text-white">Staff Performance</span>
                        </div>
                        <i class="fas fa-chevron-right text-gray-400"></i>
                    </div>
                    
                    <div class="flex items-center justify-between p-3 bg-gray-50 dark:bg-gray-700 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-600 cursor-pointer transition-colors">
                        <div class="flex items-center space-x-3">
                            <i class="fas fa-chart-pie text-orange-500"></i>
                            <span class="text-sm font-medium text-gray-900 dark:text-white">System Usage</span>
                        </div>
                        <i class="fas fa-chevron-right text-gray-400"></i>
                    </div>
                </div>
            </x-card>
        </div>

        <!-- Recent Reports -->
        <x-card>
            <div class="flex items-center justify-between mb-6">
                <div class="flex items-center space-x-3">
                    <div class="w-10 h-10 bg-indigo-100 dark:bg-indigo-900/30 rounded-xl flex items-center justify-center">
                        <i class="fas fa-history text-indigo-600 dark:text-indigo-400"></i>
                    </div>
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Recent Reports</h3>
                        <p class="text-sm text-gray-500 dark:text-gray-400">Recently generated reports</p>
                    </div>
                </div>
                <button class="text-sm text-blue-600 dark:text-blue-400 hover:text-blue-700 dark:hover:text-blue-300">
                    View All
                </button>
            </div>
            
            <div class="space-y-4">
                <!-- Report 1 -->
                <div class="flex items-center justify-between p-4 bg-gray-50 dark:bg-gray-700 rounded-lg">
                    <div class="flex items-center space-x-4">
                        <div class="w-10 h-10 bg-blue-100 dark:bg-blue-900/30 rounded-lg flex items-center justify-center">
                            <i class="fas fa-file-pdf text-blue-600 dark:text-blue-400"></i>
                        </div>
                        <div>
                            <h4 class="font-medium text-gray-900 dark:text-white">Monthly Diagnosis Report</h4>
                            <p class="text-sm text-gray-500 dark:text-gray-400">Generated on Dec 15, 2023</p>
                        </div>
                    </div>
                    <div class="flex items-center space-x-2">
                        <button class="text-blue-600 hover:text-blue-700 text-sm font-medium">
                            <i class="fas fa-download mr-1"></i>Download
                        </button>
                        <button class="text-gray-600 hover:text-gray-700 text-sm font-medium">
                            <i class="fas fa-eye mr-1"></i>View
                        </button>
                    </div>
                </div>
                
                <!-- Report 2 -->
                <div class="flex items-center justify-between p-4 bg-gray-50 dark:bg-gray-700 rounded-lg">
                    <div class="flex items-center space-x-4">
                        <div class="w-10 h-10 bg-green-100 dark:bg-green-900/30 rounded-lg flex items-center justify-center">
                            <i class="fas fa-file-excel text-green-600 dark:text-green-400"></i>
                        </div>
                        <div>
                            <h4 class="font-medium text-gray-900 dark:text-white">Patient Demographics Analysis</h4>
                            <p class="text-sm text-gray-500 dark:text-gray-400">Generated on Dec 14, 2023</p>
                        </div>
                    </div>
                    <div class="flex items-center space-x-2">
                        <button class="text-blue-600 hover:text-blue-700 text-sm font-medium">
                            <i class="fas fa-download mr-1"></i>Download
                        </button>
                        <button class="text-gray-600 hover:text-gray-700 text-sm font-medium">
                            <i class="fas fa-eye mr-1"></i>View
                        </button>
                    </div>
                </div>
                
                <!-- Report 3 -->
                <div class="flex items-center justify-between p-4 bg-gray-50 dark:bg-gray-700 rounded-lg">
                    <div class="flex items-center space-x-4">
                        <div class="w-10 h-10 bg-purple-100 dark:bg-purple-900/30 rounded-lg flex items-center justify-center">
                            <i class="fas fa-file-chart-line text-purple-600 dark:text-purple-400"></i>
                        </div>
                        <div>
                            <h4 class="font-medium text-gray-900 dark:text-white">AI System Performance</h4>
                            <p class="text-sm text-gray-500 dark:text-gray-400">Generated on Dec 13, 2023</p>
                        </div>
                    </div>
                    <div class="flex items-center space-x-2">
                        <button class="text-blue-600 hover:text-blue-700 text-sm font-medium">
                            <i class="fas fa-download mr-1"></i>Download
                        </button>
                        <button class="text-gray-600 hover:text-gray-700 text-sm font-medium">
                            <i class="fas fa-eye mr-1"></i>View
                        </button>
                    </div>
                </div>
            </div>
        </x-card>
    </div>
</div>

<!-- Generate Report Modal -->
<x-modal id="generate-report-modal" size="lg">
    <div class="bg-white dark:bg-gray-800 rounded-2xl">
        <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
            <div class="flex items-center justify-between">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Generate New Report</h3>
                <button onclick="closeModal('generate-report-modal')" class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300">
                    <i class="fas fa-times"></i>
                </button>
            </div>
        </div>
        
        <div class="px-6 py-6">
            <form class="space-y-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Report Type</label>
                    <select class="block w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        <option>Select Report Type</option>
                        <option>Patient Demographics</option>
                        <option>Diagnosis Summary</option>
                        <option>Disease Trends</option>
                        <option>AI Diagnosis Accuracy</option>
                        <option>Appointment Statistics</option>
                        <option>Pharmacy Inventory</option>
                        <option>Staff Performance</option>
                        <option>System Usage</option>
                    </select>
                </div>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Start Date</label>
                        <input type="date" class="block w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">End Date</label>
                        <input type="date" class="block w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    </div>
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Format</label>
                    <div class="flex space-x-4">
                        <label class="flex items-center">
                            <input type="radio" name="format" value="pdf" class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300">
                            <span class="ml-2 text-sm text-gray-700 dark:text-gray-300">PDF</span>
                        </label>
                        <label class="flex items-center">
                            <input type="radio" name="format" value="excel" class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300">
                            <span class="ml-2 text-sm text-gray-700 dark:text-gray-300">Excel</span>
                        </label>
                        <label class="flex items-center">
                            <input type="radio" name="format" value="csv" class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300">
                            <span class="ml-2 text-sm text-gray-700 dark:text-gray-300">CSV</span>
                        </label>
                    </div>
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Additional Filters</label>
                    <div class="space-y-2">
                        <label class="flex items-center">
                            <input type="checkbox" class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                            <span class="ml-2 text-sm text-gray-700 dark:text-gray-300">Include charts and graphs</span>
                        </label>
                        <label class="flex items-center">
                            <input type="checkbox" class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                            <span class="ml-2 text-sm text-gray-700 dark:text-gray-300">Include detailed breakdowns</span>
                        </label>
                        <label class="flex items-center">
                            <input type="checkbox" class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                            <span class="ml-2 text-sm text-gray-700 dark:text-gray-300">Email report when ready</span>
                        </label>
                    </div>
                </div>
            </form>
        </div>
        
        <div class="px-6 py-4 border-t border-gray-200 dark:border-gray-700 flex items-center justify-end space-x-3">
            <button onclick="closeModal('generate-report-modal')" class="px-4 py-2 text-sm font-medium text-gray-700 dark:text-gray-300 bg-gray-100 dark:bg-gray-700 rounded-lg hover:bg-gray-200 dark:hover:bg-gray-600">
                Cancel
            </button>
            <button class="px-4 py-2 text-sm font-medium text-white bg-blue-600 rounded-lg hover:bg-blue-700">
                Generate Report
            </button>
        </div>
    </div>
</x-modal>
@endsection