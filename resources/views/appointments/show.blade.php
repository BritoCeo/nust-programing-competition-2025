<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                <i class="fas fa-calendar-check mr-2"></i>
                {{ __('Appointment Details') }}
            </h2>
            <div class="flex items-center space-x-4">
                <a href="{{ route('appointments.index') }}" 
                   class="inline-flex items-center px-4 py-2 bg-gray-500 border border-transparent rounded-lg font-semibold text-sm text-white hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition-all duration-200">
                    <i class="fas fa-arrow-left mr-2"></i>
                    Back to Appointments
                </a>
                @can('edit appointments')
                <a href="{{ route('appointments.edit', $appointment) }}" 
                   class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-lg font-semibold text-sm text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-all duration-200">
                    <i class="fas fa-edit mr-2"></i>
                    Edit Appointment
                </a>
                @endcan
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <!-- Appointment Header -->
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6">
                    <div class="flex items-center justify-between mb-4">
                        <div>
                            <h3 class="text-2xl font-bold text-gray-900 dark:text-white">
                                Appointment #{{ $appointment->appointment_number }}
                            </h3>
                            <p class="text-gray-600 dark:text-gray-400">
                                {{ $appointment->appointment_date->format('l, F j, Y') }} at {{ $appointment->appointment_time->format('g:i A') }}
                            </p>
                        </div>
                        <div class="text-right">
                            <span class="inline-flex px-3 py-1 text-sm font-semibold rounded-full
                                {{ $appointment->status === 'scheduled' ? 'bg-yellow-100 text-yellow-800' : 
                                   ($appointment->status === 'confirmed' ? 'bg-blue-100 text-blue-800' : 
                                   ($appointment->status === 'in_progress' ? 'bg-purple-100 text-purple-800' : 
                                   ($appointment->status === 'completed' ? 'bg-green-100 text-green-800' : 
                                   ($appointment->status === 'cancelled' ? 'bg-red-100 text-red-800' : 'bg-gray-100 text-gray-800')))) }}">
                                {{ ucfirst(str_replace('_', ' ', $appointment->status)) }}
                            </span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Appointment Details -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <!-- Patient Information -->
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <h4 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4">
                            <i class="fas fa-user mr-2"></i>Patient Information
                        </h4>
                        <div class="space-y-3">
                            <div>
                                <span class="text-sm font-medium text-gray-500 dark:text-gray-400">Name:</span>
                                <span class="ml-2 text-gray-900 dark:text-gray-100">{{ $appointment->patient->name }}</span>
                            </div>
                            <div>
                                <span class="text-sm font-medium text-gray-500 dark:text-gray-400">Email:</span>
                                <span class="ml-2 text-gray-900 dark:text-gray-100">{{ $appointment->patient->email }}</span>
                            </div>
                            <div>
                                <span class="text-sm font-medium text-gray-500 dark:text-gray-400">Phone:</span>
                                <span class="ml-2 text-gray-900 dark:text-gray-100">{{ $appointment->patient->phone ?? 'Not provided' }}</span>
                            </div>
                            <div>
                                <span class="text-sm font-medium text-gray-500 dark:text-gray-400">Date of Birth:</span>
                                <span class="ml-2 text-gray-900 dark:text-gray-100">
                                    {{ $appointment->patient->date_of_birth ? $appointment->patient->date_of_birth->format('M d, Y') : 'Not provided' }}
                                </span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Doctor Information -->
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <h4 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4">
                            <i class="fas fa-user-md mr-2"></i>Doctor Information
                        </h4>
                        <div class="space-y-3">
                            <div>
                                <span class="text-sm font-medium text-gray-500 dark:text-gray-400">Name:</span>
                                <span class="ml-2 text-gray-900 dark:text-gray-100">{{ $appointment->doctor->name }}</span>
                            </div>
                            <div>
                                <span class="text-sm font-medium text-gray-500 dark:text-gray-400">Specialization:</span>
                                <span class="ml-2 text-gray-900 dark:text-gray-100">{{ $appointment->doctor->specialization ?? 'Not specified' }}</span>
                            </div>
                            <div>
                                <span class="text-sm font-medium text-gray-500 dark:text-gray-400">License:</span>
                                <span class="ml-2 text-gray-900 dark:text-gray-100">{{ $appointment->doctor->medical_license_number ?? 'Not provided' }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Appointment Details -->
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg mt-6">
                <div class="p-6">
                    <h4 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4">
                        <i class="fas fa-calendar-alt mr-2"></i>Appointment Details
                    </h4>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="space-y-3">
                            <div>
                                <span class="text-sm font-medium text-gray-500 dark:text-gray-400">Type:</span>
                                <span class="ml-2 text-gray-900 dark:text-gray-100">{{ ucfirst(str_replace('_', ' ', $appointment->type)) }}</span>
                            </div>
                            <div>
                                <span class="text-sm font-medium text-gray-500 dark:text-gray-400">Duration:</span>
                                <span class="ml-2 text-gray-900 dark:text-gray-100">{{ $appointment->duration_minutes ?? 30 }} minutes</span>
                            </div>
                            <div>
                                <span class="text-sm font-medium text-gray-500 dark:text-gray-400">Priority:</span>
                                <span class="ml-2 text-gray-900 dark:text-gray-100">{{ ucfirst($appointment->priority ?? 'Normal') }}</span>
                            </div>
                        </div>
                        <div class="space-y-3">
                            <div>
                                <span class="text-sm font-medium text-gray-500 dark:text-gray-400">Created:</span>
                                <span class="ml-2 text-gray-900 dark:text-gray-100">{{ $appointment->created_at->format('M d, Y g:i A') }}</span>
                            </div>
                            <div>
                                <span class="text-sm font-medium text-gray-500 dark:text-gray-400">Created By:</span>
                                <span class="ml-2 text-gray-900 dark:text-gray-100">{{ $appointment->creator->name }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Reason and Notes -->
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg mt-6">
                <div class="p-6">
                    <h4 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4">
                        <i class="fas fa-comment-medical mr-2"></i>Reason & Notes
                    </h4>
                    <div class="space-y-4">
                        <div>
                            <span class="text-sm font-medium text-gray-500 dark:text-gray-400">Reason:</span>
                            <p class="mt-1 text-gray-900 dark:text-gray-100">{{ $appointment->reason }}</p>
                        </div>
                        @if($appointment->notes)
                        <div>
                            <span class="text-sm font-medium text-gray-500 dark:text-gray-400">Notes:</span>
                            <p class="mt-1 text-gray-900 dark:text-gray-100">{{ $appointment->notes }}</p>
                        </div>
                        @endif
                        @if($appointment->special_requirements)
                        <div>
                            <span class="text-sm font-medium text-gray-500 dark:text-gray-400">Special Requirements:</span>
                            <p class="mt-1 text-gray-900 dark:text-gray-100">{{ $appointment->special_requirements }}</p>
                        </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Cancellation Information -->
            @if($appointment->status === 'cancelled' && $appointment->cancellation_reason)
            <div class="bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 overflow-hidden shadow-sm sm:rounded-lg mt-6">
                <div class="p-6">
                    <h4 class="text-lg font-semibold text-red-900 dark:text-red-100 mb-4">
                        <i class="fas fa-times-circle mr-2"></i>Cancellation Information
                    </h4>
                    <div>
                        <span class="text-sm font-medium text-red-700 dark:text-red-300">Reason:</span>
                        <p class="mt-1 text-red-900 dark:text-red-100">{{ $appointment->cancellation_reason }}</p>
                    </div>
                </div>
            </div>
            @endif

            <!-- Action Buttons -->
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg mt-6">
                <div class="p-6">
                    <div class="flex flex-wrap gap-3">
                        @can('edit appointments')
                        <a href="{{ route('appointments.edit', $appointment) }}" 
                           class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 focus:bg-blue-700 active:bg-blue-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                            <i class="fas fa-edit mr-2"></i>Edit Appointment
                        </a>
                        @endcan
                        
                        @can('cancel appointments')
                        @if($appointment->status !== 'cancelled' && $appointment->status !== 'completed')
                        <form method="POST" action="{{ route('appointments.cancel', $appointment) }}" class="inline">
                            @csrf
                            <button type="submit" 
                                    class="inline-flex items-center px-4 py-2 bg-red-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-red-700 focus:bg-red-700 active:bg-red-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150"
                                    onclick="return confirm('Are you sure you want to cancel this appointment?')">
                                <i class="fas fa-times mr-2"></i>Cancel Appointment
                            </button>
                        </form>
                        @endif
                        @endcan
                        
                        @if($appointment->status === 'scheduled')
                        <form method="POST" action="{{ route('appointments.update', $appointment) }}" class="inline">
                            @csrf
                            @method('PATCH')
                            <input type="hidden" name="status" value="confirmed">
                            <button type="submit" 
                                    class="inline-flex items-center px-4 py-2 bg-green-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-green-700 focus:bg-green-700 active:bg-green-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                <i class="fas fa-check mr-2"></i>Confirm Appointment
                            </button>
                        </form>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
