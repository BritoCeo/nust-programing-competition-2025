@extends('layouts.app')

@section('title', 'Appointments - MESMTF')

@section('content')
<div class="min-h-screen bg-gray-50 dark:bg-gray-900">
    <!-- Header Section -->
    <div class="bg-white dark:bg-gray-800 shadow-sm border-b border-gray-200 dark:border-gray-700">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
            <div class="flex items-center justify-between">
                <div class="flex items-center space-x-4">
                    <div class="w-12 h-12 bg-gradient-to-r from-green-500 to-emerald-500 rounded-xl flex items-center justify-center shadow-lg">
                        <i class="fas fa-calendar-check text-white text-xl"></i>
                    </div>
                    <div>
                        <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Appointments</h1>
                        <p class="text-sm text-gray-500 dark:text-gray-400">Schedule and manage patient appointments</p>
                    </div>
                </div>
                <div class="flex items-center space-x-3">
                    <x-button variant="outline" icon="fas fa-calendar-alt" size="sm">
                        Calendar View
                    </x-button>
                    <x-button variant="primary" icon="fas fa-plus" size="sm" onclick="openModal('book-appointment-modal')">
                        Book Appointment
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
                    <i class="fas fa-calendar-day text-blue-600 dark:text-blue-400 text-xl"></i>
                </div>
                <h3 class="text-2xl font-bold text-gray-900 dark:text-white mb-1">12</h3>
                <p class="text-sm text-gray-500 dark:text-gray-400">Today's Appointments</p>
            </x-card>
            
            <x-card class="text-center">
                <div class="w-12 h-12 bg-green-100 dark:bg-green-900/30 rounded-xl flex items-center justify-center mx-auto mb-3">
                    <i class="fas fa-check-circle text-green-600 dark:text-green-400 text-xl"></i>
                </div>
                <h3 class="text-2xl font-bold text-gray-900 dark:text-white mb-1">8</h3>
                <p class="text-sm text-gray-500 dark:text-gray-400">Completed</p>
            </x-card>
            
            <x-card class="text-center">
                <div class="w-12 h-12 bg-yellow-100 dark:bg-yellow-900/30 rounded-xl flex items-center justify-center mx-auto mb-3">
                    <i class="fas fa-clock text-yellow-600 dark:text-yellow-400 text-xl"></i>
                </div>
                <h3 class="text-2xl font-bold text-gray-900 dark:text-white mb-1">4</h3>
                <p class="text-sm text-gray-500 dark:text-gray-400">Pending</p>
            </x-card>
            
            <x-card class="text-center">
                <div class="w-12 h-12 bg-purple-100 dark:bg-purple-900/30 rounded-xl flex items-center justify-center mx-auto mb-3">
                    <i class="fas fa-calendar-week text-purple-600 dark:text-purple-400 text-xl"></i>
                </div>
                <h3 class="text-2xl font-bold text-gray-900 dark:text-white mb-1">28</h3>
                <p class="text-sm text-gray-500 dark:text-gray-400">This Week</p>
            </x-card>
        </div>

        <!-- Filters and Search -->
        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg p-6 mb-8 border border-gray-200 dark:border-gray-700">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Search Appointments</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <i class="fas fa-search text-gray-400"></i>
                        </div>
                        <input type="text" placeholder="Search by patient name, doctor, or appointment ID..." class="block w-full pl-10 pr-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white placeholder-gray-500 dark:placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    </div>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Date Range</label>
                    <select class="block w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        <option>Today</option>
                        <option>This Week</option>
                        <option>This Month</option>
                        <option>Custom Range</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Status</label>
                    <select class="block w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        <option>All Status</option>
                        <option>Scheduled</option>
                        <option>In Progress</option>
                        <option>Completed</option>
                        <option>Cancelled</option>
                    </select>
                </div>
            </div>
        </div>

        <!-- Appointments List -->
        <div class="space-y-4">
            <!-- Appointment 1 -->
            <x-card hover="true" class="group">
                <div class="flex items-center justify-between">
                    <div class="flex items-center space-x-4">
                        <div class="w-12 h-12 bg-gradient-to-r from-blue-500 to-cyan-500 rounded-xl flex items-center justify-center">
                            <i class="fas fa-user text-white"></i>
                        </div>
                        <div class="flex-1">
                            <div class="flex items-center space-x-3 mb-1">
                                <h3 class="font-semibold text-gray-900 dark:text-white">John Doe</h3>
                                <span class="px-2 py-1 bg-green-100 text-green-800 text-xs font-medium rounded-full">Scheduled</span>
                            </div>
                            <div class="flex items-center space-x-4 text-sm text-gray-500 dark:text-gray-400">
                                <span><i class="fas fa-user-md mr-1"></i>Dr. Smith</span>
                                <span><i class="fas fa-calendar mr-1"></i>Dec 15, 2023</span>
                                <span><i class="fas fa-clock mr-1"></i>10:00 AM</span>
                                <span><i class="fas fa-map-marker-alt mr-1"></i>Room 101</span>
                            </div>
                        </div>
                    </div>
                    <div class="flex items-center space-x-2">
                        <button class="text-blue-600 hover:text-blue-700 text-sm font-medium">
                            <i class="fas fa-eye mr-1"></i>View
                        </button>
                        <button class="text-gray-600 hover:text-gray-700 text-sm font-medium">
                            <i class="fas fa-edit mr-1"></i>Edit
                        </button>
                        <button class="text-green-600 hover:text-green-700 text-sm font-medium">
                            <i class="fas fa-check mr-1"></i>Start
                        </button>
                    </div>
                </div>
            </x-card>

            <!-- Appointment 2 -->
            <x-card hover="true" class="group">
                <div class="flex items-center justify-between">
                    <div class="flex items-center space-x-4">
                        <div class="w-12 h-12 bg-gradient-to-r from-green-500 to-emerald-500 rounded-xl flex items-center justify-center">
                            <i class="fas fa-user text-white"></i>
                        </div>
                        <div class="flex-1">
                            <div class="flex items-center space-x-3 mb-1">
                                <h3 class="font-semibold text-gray-900 dark:text-white">Jane Smith</h3>
                                <span class="px-2 py-1 bg-yellow-100 text-yellow-800 text-xs font-medium rounded-full">In Progress</span>
                            </div>
                            <div class="flex items-center space-x-4 text-sm text-gray-500 dark:text-gray-400">
                                <span><i class="fas fa-user-md mr-1"></i>Dr. Johnson</span>
                                <span><i class="fas fa-calendar mr-1"></i>Dec 15, 2023</span>
                                <span><i class="fas fa-clock mr-1"></i>11:30 AM</span>
                                <span><i class="fas fa-map-marker-alt mr-1"></i>Room 102</span>
                            </div>
                        </div>
                    </div>
                    <div class="flex items-center space-x-2">
                        <button class="text-blue-600 hover:text-blue-700 text-sm font-medium">
                            <i class="fas fa-eye mr-1"></i>View
                        </button>
                        <button class="text-gray-600 hover:text-gray-700 text-sm font-medium">
                            <i class="fas fa-edit mr-1"></i>Edit
                        </button>
                        <button class="text-red-600 hover:text-red-700 text-sm font-medium">
                            <i class="fas fa-times mr-1"></i>Cancel
                        </button>
                    </div>
                </div>
            </x-card>

            <!-- Appointment 3 -->
            <x-card hover="true" class="group">
                <div class="flex items-center justify-between">
                    <div class="flex items-center space-x-4">
                        <div class="w-12 h-12 bg-gradient-to-r from-purple-500 to-pink-500 rounded-xl flex items-center justify-center">
                            <i class="fas fa-user text-white"></i>
                        </div>
                        <div class="flex-1">
                            <div class="flex items-center space-x-3 mb-1">
                                <h3 class="font-semibold text-gray-900 dark:text-white">Mike Johnson</h3>
                                <span class="px-2 py-1 bg-green-100 text-green-800 text-xs font-medium rounded-full">Completed</span>
                            </div>
                            <div class="flex items-center space-x-4 text-sm text-gray-500 dark:text-gray-400">
                                <span><i class="fas fa-user-md mr-1"></i>Dr. Brown</span>
                                <span><i class="fas fa-calendar mr-1"></i>Dec 14, 2023</span>
                                <span><i class="fas fa-clock mr-1"></i>2:00 PM</span>
                                <span><i class="fas fa-map-marker-alt mr-1"></i>Room 103</span>
                            </div>
                        </div>
                    </div>
                    <div class="flex items-center space-x-2">
                        <button class="text-blue-600 hover:text-blue-700 text-sm font-medium">
                            <i class="fas fa-eye mr-1"></i>View
                        </button>
                        <button class="text-gray-600 hover:text-gray-700 text-sm font-medium">
                            <i class="fas fa-file-medical mr-1"></i>Report
                        </button>
                        <button class="text-green-600 hover:text-green-700 text-sm font-medium">
                            <i class="fas fa-redo mr-1"></i>Reschedule
                        </button>
                    </div>
                </div>
            </x-card>
        </div>

        <!-- Pagination -->
        <div class="mt-8 flex items-center justify-between">
            <div class="text-sm text-gray-700 dark:text-gray-300">
                Showing 1 to 3 of 12 appointments
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

<!-- Book Appointment Modal -->
<x-modal id="book-appointment-modal" size="lg">
    <div class="bg-white dark:bg-gray-800 rounded-2xl">
        <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
            <div class="flex items-center justify-between">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Book New Appointment</h3>
                <button onclick="closeModal('book-appointment-modal')" class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300">
                    <i class="fas fa-times"></i>
                </button>
            </div>
        </div>
        
        <div class="px-6 py-6">
            <form class="space-y-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Patient</label>
                        <select class="block w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                            <option>Select Patient</option>
                            <option>John Doe</option>
                            <option>Jane Smith</option>
                            <option>Mike Johnson</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Doctor</label>
                        <select class="block w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                            <option>Select Doctor</option>
                            <option>Dr. Smith</option>
                            <option>Dr. Johnson</option>
                            <option>Dr. Brown</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Date</label>
                        <input type="date" class="block w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Time</label>
                        <select class="block w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                            <option>Select Time</option>
                            <option>09:00 AM</option>
                            <option>10:00 AM</option>
                            <option>11:00 AM</option>
                            <option>02:00 PM</option>
                            <option>03:00 PM</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Duration</label>
                        <select class="block w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                            <option>30 minutes</option>
                            <option>45 minutes</option>
                            <option>60 minutes</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Room</label>
                        <select class="block w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                            <option>Select Room</option>
                            <option>Room 101</option>
                            <option>Room 102</option>
                            <option>Room 103</option>
                        </select>
                    </div>
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Reason for Visit</label>
                    <textarea rows="3" class="block w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent" placeholder="Describe the reason for the appointment..."></textarea>
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Priority</label>
                    <div class="flex space-x-4">
                        <label class="flex items-center">
                            <input type="radio" name="priority" value="low" class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300">
                            <span class="ml-2 text-sm text-gray-700 dark:text-gray-300">Low</span>
                        </label>
                        <label class="flex items-center">
                            <input type="radio" name="priority" value="medium" class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300">
                            <span class="ml-2 text-sm text-gray-700 dark:text-gray-300">Medium</span>
                        </label>
                        <label class="flex items-center">
                            <input type="radio" name="priority" value="high" class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300">
                            <span class="ml-2 text-sm text-gray-700 dark:text-gray-300">High</span>
                        </label>
                    </div>
                </div>
            </form>
        </div>
        
        <div class="px-6 py-4 border-t border-gray-200 dark:border-gray-700 flex items-center justify-end space-x-3">
            <button onclick="closeModal('book-appointment-modal')" class="px-4 py-2 text-sm font-medium text-gray-700 dark:text-gray-300 bg-gray-100 dark:bg-gray-700 rounded-lg hover:bg-gray-200 dark:hover:bg-gray-600">
                Cancel
            </button>
            <button class="px-4 py-2 text-sm font-medium text-white bg-blue-600 rounded-lg hover:bg-blue-700">
                Book Appointment
            </button>
        </div>
    </div>
</x-modal>
@endsection