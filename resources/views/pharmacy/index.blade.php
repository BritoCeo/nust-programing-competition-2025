@extends('layouts.app')

@section('title', 'Pharmacy - MESMTF')

@section('content')
<div class="min-h-screen bg-gray-50 dark:bg-gray-900">
    <!-- Header Section -->
    <div class="bg-white dark:bg-gray-800 shadow-sm border-b border-gray-200 dark:border-gray-700">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
            <div class="flex items-center justify-between">
                <div class="flex items-center space-x-4">
                    <div class="w-12 h-12 bg-gradient-to-r from-teal-500 to-cyan-500 rounded-xl flex items-center justify-center shadow-lg">
                        <i class="fas fa-pills text-white text-xl"></i>
                    </div>
                    <div>
                        <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Pharmacy Management</h1>
                        <p class="text-sm text-gray-500 dark:text-gray-400">Manage drug inventory and prescriptions</p>
                    </div>
                </div>
                <div class="flex items-center space-x-3">
                    <x-button variant="outline" icon="fas fa-chart-bar" size="sm">
                        Analytics
                    </x-button>
                    <x-button variant="primary" icon="fas fa-plus" size="sm" onclick="openModal('add-drug-modal')">
                        Add Drug
                    </x-button>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <!-- Quick Stats -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
            <x-card class="text-center">
                <div class="w-12 h-12 bg-blue-100 dark:bg-blue-900/30 rounded-xl flex items-center justify-center mx-auto mb-3">
                    <i class="fas fa-pills text-blue-600 dark:text-blue-400 text-xl"></i>
                </div>
                <h3 class="text-2xl font-bold text-gray-900 dark:text-white mb-1">156</h3>
                <p class="text-sm text-gray-500 dark:text-gray-400">Total Drugs</p>
            </x-card>
            
            <x-card class="text-center">
                <div class="w-12 h-12 bg-green-100 dark:bg-green-900/30 rounded-xl flex items-center justify-center mx-auto mb-3">
                    <i class="fas fa-check-circle text-green-600 dark:text-green-400 text-xl"></i>
                </div>
                <h3 class="text-2xl font-bold text-gray-900 dark:text-white mb-1">142</h3>
                <p class="text-sm text-gray-500 dark:text-gray-400">In Stock</p>
            </x-card>
            
            <x-card class="text-center">
                <div class="w-12 h-12 bg-yellow-100 dark:bg-yellow-900/30 rounded-xl flex items-center justify-center mx-auto mb-3">
                    <i class="fas fa-exclamation-triangle text-yellow-600 dark:text-yellow-400 text-xl"></i>
                </div>
                <h3 class="text-2xl font-bold text-gray-900 dark:text-white mb-1">8</h3>
                <p class="text-sm text-gray-500 dark:text-gray-400">Low Stock</p>
            </x-card>
            
            <x-card class="text-center">
                <div class="w-12 h-12 bg-red-100 dark:bg-red-900/30 rounded-xl flex items-center justify-center mx-auto mb-3">
                    <i class="fas fa-times-circle text-red-600 dark:text-red-400 text-xl"></i>
                </div>
                <h3 class="text-2xl font-bold text-gray-900 dark:text-white mb-1">6</h3>
                <p class="text-sm text-gray-500 dark:text-gray-400">Out of Stock</p>
            </x-card>
        </div>

        <!-- Filters and Search -->
        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg p-6 mb-8 border border-gray-200 dark:border-gray-700">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Search Drugs</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <i class="fas fa-search text-gray-400"></i>
                        </div>
                        <input type="text" placeholder="Search by drug name, generic name, or manufacturer..." class="block w-full pl-10 pr-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white placeholder-gray-500 dark:placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    </div>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Category</label>
                    <select class="block w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        <option>All Categories</option>
                        <option>Antimalarials</option>
                        <option>Antibiotics</option>
                        <option>Analgesics</option>
                        <option>Antipyretics</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Stock Status</label>
                    <select class="block w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        <option>All Status</option>
                        <option>In Stock</option>
                        <option>Low Stock</option>
                        <option>Out of Stock</option>
                    </select>
                </div>
            </div>
        </div>

        <!-- Drugs Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            <!-- Drug 1 -->
            <x-card hover="true" class="group">
                <div class="flex items-start justify-between mb-4">
                    <div class="flex items-center space-x-3">
                        <div class="w-12 h-12 bg-gradient-to-r from-green-500 to-emerald-500 rounded-xl flex items-center justify-center">
                            <i class="fas fa-pills text-white"></i>
                        </div>
                        <div>
                            <h3 class="font-semibold text-gray-900 dark:text-white">Chloroquine</h3>
                            <p class="text-sm text-gray-500 dark:text-gray-400">Antimalarial</p>
                        </div>
                    </div>
                    <div class="flex items-center space-x-2">
                        <span class="px-2 py-1 bg-green-100 text-green-800 text-xs font-medium rounded-full">In Stock</span>
                        <button class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300">
                            <i class="fas fa-ellipsis-v"></i>
                        </button>
                    </div>
                </div>

                <div class="space-y-3 mb-4">
                    <div class="flex items-center justify-between">
                        <span class="text-sm text-gray-600 dark:text-gray-400">Stock</span>
                        <span class="text-sm font-medium text-gray-900 dark:text-white">250 tablets</span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-sm text-gray-600 dark:text-gray-400">Expiry</span>
                        <span class="text-sm font-medium text-gray-900 dark:text-white">Dec 2024</span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-sm text-gray-600 dark:text-gray-400">Price</span>
                        <span class="text-sm font-medium text-gray-900 dark:text-white">$0.50/tablet</span>
                    </div>
                </div>

                <div class="flex items-center justify-between pt-4 border-t border-gray-200 dark:border-gray-700">
                    <div class="flex items-center space-x-2">
                        <button class="text-blue-600 hover:text-blue-700 text-sm font-medium">
                            <i class="fas fa-eye mr-1"></i>View
                        </button>
                        <button class="text-gray-600 hover:text-gray-700 text-sm font-medium">
                            <i class="fas fa-edit mr-1"></i>Edit
                        </button>
                    </div>
                    <div class="text-xs text-gray-500">
                        Last updated 2 hours ago
                    </div>
                </div>
            </x-card>

            <!-- Drug 2 -->
            <x-card hover="true" class="group">
                <div class="flex items-start justify-between mb-4">
                    <div class="flex items-center space-x-3">
                        <div class="w-12 h-12 bg-gradient-to-r from-blue-500 to-cyan-500 rounded-xl flex items-center justify-center">
                            <i class="fas fa-pills text-white"></i>
                        </div>
                        <div>
                            <h3 class="font-semibold text-gray-900 dark:text-white">Artemether</h3>
                            <p class="text-sm text-gray-500 dark:text-gray-400">Antimalarial</p>
                        </div>
                    </div>
                    <div class="flex items-center space-x-2">
                        <span class="px-2 py-1 bg-yellow-100 text-yellow-800 text-xs font-medium rounded-full">Low Stock</span>
                        <button class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300">
                            <i class="fas fa-ellipsis-v"></i>
                        </button>
                    </div>
                </div>

                <div class="space-y-3 mb-4">
                    <div class="flex items-center justify-between">
                        <span class="text-sm text-gray-600 dark:text-gray-400">Stock</span>
                        <span class="text-sm font-medium text-gray-900 dark:text-white">15 vials</span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-sm text-gray-600 dark:text-gray-400">Expiry</span>
                        <span class="text-sm font-medium text-gray-900 dark:text-white">Mar 2024</span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-sm text-gray-600 dark:text-gray-400">Price</span>
                        <span class="text-sm font-medium text-gray-900 dark:text-white">$2.50/vial</span>
                    </div>
                </div>

                <div class="flex items-center justify-between pt-4 border-t border-gray-200 dark:border-gray-700">
                    <div class="flex items-center space-x-2">
                        <button class="text-blue-600 hover:text-blue-700 text-sm font-medium">
                            <i class="fas fa-eye mr-1"></i>View
                        </button>
                        <button class="text-gray-600 hover:text-gray-700 text-sm font-medium">
                            <i class="fas fa-edit mr-1"></i>Edit
                        </button>
                    </div>
                    <div class="text-xs text-gray-500">
                        Last updated 1 day ago
                    </div>
                </div>
            </x-card>

            <!-- Drug 3 -->
            <x-card hover="true" class="group">
                <div class="flex items-start justify-between mb-4">
                    <div class="flex items-center space-x-3">
                        <div class="w-12 h-12 bg-gradient-to-r from-purple-500 to-pink-500 rounded-xl flex items-center justify-center">
                            <i class="fas fa-pills text-white"></i>
                        </div>
                        <div>
                            <h3 class="font-semibold text-gray-900 dark:text-white">Ciprofloxacin</h3>
                            <p class="text-sm text-gray-500 dark:text-gray-400">Antibiotic</p>
                        </div>
                    </div>
                    <div class="flex items-center space-x-2">
                        <span class="px-2 py-1 bg-red-100 text-red-800 text-xs font-medium rounded-full">Out of Stock</span>
                        <button class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300">
                            <i class="fas fa-ellipsis-v"></i>
                        </button>
                    </div>
                </div>

                <div class="space-y-3 mb-4">
                    <div class="flex items-center justify-between">
                        <span class="text-sm text-gray-600 dark:text-gray-400">Stock</span>
                        <span class="text-sm font-medium text-gray-900 dark:text-white">0 tablets</span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-sm text-gray-600 dark:text-gray-400">Expiry</span>
                        <span class="text-sm font-medium text-gray-900 dark:text-white">Jan 2024</span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-sm text-gray-600 dark:text-gray-400">Price</span>
                        <span class="text-sm font-medium text-gray-900 dark:text-white">$1.20/tablet</span>
                    </div>
                </div>

                <div class="flex items-center justify-between pt-4 border-t border-gray-200 dark:border-gray-700">
                    <div class="flex items-center space-x-2">
                        <button class="text-blue-600 hover:text-blue-700 text-sm font-medium">
                            <i class="fas fa-eye mr-1"></i>View
                        </button>
                        <button class="text-gray-600 hover:text-gray-700 text-sm font-medium">
                            <i class="fas fa-edit mr-1"></i>Edit
                        </button>
                    </div>
                    <div class="text-xs text-gray-500">
                        Last updated 3 days ago
                    </div>
                </div>
            </x-card>
        </div>

        <!-- Pagination -->
        <div class="mt-8 flex items-center justify-between">
            <div class="text-sm text-gray-700 dark:text-gray-300">
                Showing 1 to 3 of 12 drugs
            </div>
            <div class="flex items-center space-x-2">
                <button class="px-3 py-2 text-sm font-medium text-gray-500 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-600 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700">
                    Previous
                </button>
                <button class="px-3 py-2 text-sm font-medium text-white bg-blue-600 border border-transparent rounded-lg">
                    1
                </button>
                <button class="px-3 py-2 text-sm font-medium text-gray-500 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-600 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700">
                    2
                </button>
                <button class="px-3 py-2 text-sm font-medium text-gray-500 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-600 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700">
                    3
                </button>
                <button class="px-3 py-2 text-sm font-medium text-gray-500 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-600 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700">
                    Next
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Add Drug Modal -->
<x-modal id="add-drug-modal" size="lg">
    <div class="bg-white dark:bg-gray-800 rounded-2xl">
        <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
            <div class="flex items-center justify-between">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Add New Drug</h3>
                <button onclick="closeModal('add-drug-modal')" class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300">
                    <i class="fas fa-times"></i>
                </button>
            </div>
        </div>
        
        <div class="px-6 py-6">
            <form class="space-y-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Drug Name</label>
                        <input type="text" class="block w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Generic Name</label>
                        <input type="text" class="block w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Category</label>
                        <select class="block w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                            <option>Select Category</option>
                            <option>Antimalarials</option>
                            <option>Antibiotics</option>
                            <option>Analgesics</option>
                            <option>Antipyretics</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Manufacturer</label>
                        <input type="text" class="block w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Dosage Form</label>
                        <select class="block w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                            <option>Select Form</option>
                            <option>Tablet</option>
                            <option>Capsule</option>
                            <option>Injection</option>
                            <option>Syrup</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Strength</label>
                        <input type="text" placeholder="e.g., 250mg" class="block w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Current Stock</label>
                        <input type="number" class="block w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Unit Price</label>
                        <input type="number" step="0.01" class="block w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Expiry Date</label>
                        <input type="date" class="block w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Minimum Stock Level</label>
                        <input type="number" class="block w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    </div>
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Description</label>
                    <textarea rows="3" class="block w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent" placeholder="Enter drug description..."></textarea>
                </div>
            </form>
        </div>
        
        <div class="px-6 py-4 border-t border-gray-200 dark:border-gray-700 flex items-center justify-end space-x-3">
            <button onclick="closeModal('add-drug-modal')" class="px-4 py-2 text-sm font-medium text-gray-700 dark:text-gray-300 bg-gray-100 dark:bg-gray-700 rounded-lg hover:bg-gray-200 dark:hover:bg-gray-600">
                Cancel
            </button>
            <button class="px-4 py-2 text-sm font-medium text-white bg-blue-600 rounded-lg hover:bg-blue-700">
                Add Drug
            </button>
        </div>
    </div>
</x-modal>
@endsection