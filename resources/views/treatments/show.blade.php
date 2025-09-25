<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                <i class="fas fa-pills mr-2"></i>
                {{ __('Treatment Details') }}
            </h2>
            <div class="flex items-center space-x-4">
                <a href="{{ route('treatments.index') }}" 
                   class="inline-flex items-center px-4 py-2 bg-gray-500 border border-transparent rounded-lg font-semibold text-sm text-white hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition-all duration-200">
                    <i class="fas fa-arrow-left mr-2"></i>
                    Back to Treatments
                </a>
                @can('edit treatments')
                <a href="{{ route('treatments.edit', $treatment) }}" 
                   class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-lg font-semibold text-sm text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-all duration-200">
                    <i class="fas fa-edit mr-2"></i>
                    Edit Treatment
                </a>
                @endcan
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-6xl mx-auto sm:px-6 lg:px-8">
            <!-- Treatment Header -->
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6">
                    <div class="flex items-center justify-between mb-4">
                        <div>
                            <h3 class="text-2xl font-bold text-gray-900 dark:text-white">
                                {{ $treatment->treatment_name }}
                            </h3>
                            <p class="text-gray-600 dark:text-gray-400">
                                {{ ucfirst($treatment->treatment_type) }} • Started {{ $treatment->start_date->format('M d, Y') }}
                            </p>
                        </div>
                        <div class="text-right">
                            <span class="inline-flex px-3 py-1 text-sm font-semibold rounded-full
                                {{ $treatment->status === 'active' ? 'bg-green-100 text-green-800' : 
                                   ($treatment->status === 'completed' ? 'bg-blue-100 text-blue-800' : 
                                   ($treatment->status === 'discontinued' ? 'bg-red-100 text-red-800' : 'bg-yellow-100 text-yellow-800')) }}">
                                {{ ucfirst($treatment->status) }}
                            </span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Patient and Doctor Information -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
                <!-- Patient Information -->
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <h4 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4">
                            <i class="fas fa-user mr-2"></i>Patient Information
                        </h4>
                        <div class="space-y-3">
                            <div>
                                <span class="text-sm font-medium text-gray-500 dark:text-gray-400">Name:</span>
                                <span class="ml-2 text-gray-900 dark:text-gray-100">{{ $treatment->patient->name }}</span>
                            </div>
                            <div>
                                <span class="text-sm font-medium text-gray-500 dark:text-gray-400">Email:</span>
                                <span class="ml-2 text-gray-900 dark:text-gray-100">{{ $treatment->patient->email }}</span>
                            </div>
                            <div>
                                <span class="text-sm font-medium text-gray-500 dark:text-gray-400">Phone:</span>
                                <span class="ml-2 text-gray-900 dark:text-gray-100">{{ $treatment->patient->phone ?? 'Not provided' }}</span>
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
                                <span class="ml-2 text-gray-900 dark:text-gray-100">{{ $treatment->doctor->name }}</span>
                            </div>
                            <div>
                                <span class="text-sm font-medium text-gray-500 dark:text-gray-400">Specialization:</span>
                                <span class="ml-2 text-gray-900 dark:text-gray-100">{{ $treatment->doctor->specialization ?? 'Not specified' }}</span>
                            </div>
                            <div>
                                <span class="text-sm font-medium text-gray-500 dark:text-gray-400">License:</span>
                                <span class="ml-2 text-gray-900 dark:text-gray-100">{{ $treatment->doctor->medical_license_number ?? 'Not provided' }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Treatment Description -->
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6">
                    <h4 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4">
                        <i class="fas fa-clipboard-list mr-2"></i>Treatment Description
                    </h4>
                    <p class="text-gray-900 dark:text-gray-100">{{ $treatment->description }}</p>
                </div>
            </div>

            <!-- Treatment Schedule -->
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6">
                    <h4 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4">
                        <i class="fas fa-calendar-alt mr-2"></i>Treatment Schedule
                    </h4>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <div>
                            <span class="text-sm font-medium text-gray-500 dark:text-gray-400">Start Date:</span>
                            <p class="text-gray-900 dark:text-gray-100">{{ $treatment->start_date->format('M d, Y') }}</p>
                        </div>
                        <div>
                            <span class="text-sm font-medium text-gray-500 dark:text-gray-400">End Date:</span>
                            <p class="text-gray-900 dark:text-gray-100">{{ $treatment->end_date ? $treatment->end_date->format('M d, Y') : 'Ongoing' }}</p>
                        </div>
                        <div>
                            <span class="text-sm font-medium text-gray-500 dark:text-gray-400">Frequency:</span>
                            <p class="text-gray-900 dark:text-gray-100">{{ $treatment->frequency ?? 'Not specified' }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Medications -->
            @if($treatment->medications && count($treatment->medications) > 0)
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6">
                    <h4 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4">
                        <i class="fas fa-pills mr-2"></i>Medications
                    </h4>
                    <div class="space-y-4">
                        @foreach($treatment->medications as $medication)
                        <div class="border border-gray-200 dark:border-gray-600 rounded-lg p-4">
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                <div>
                                    <span class="text-sm font-medium text-gray-500 dark:text-gray-400">Medication:</span>
                                    <p class="text-gray-900 dark:text-gray-100 font-medium">{{ $medication['name'] }}</p>
                                </div>
                                <div>
                                    <span class="text-sm font-medium text-gray-500 dark:text-gray-400">Dosage:</span>
                                    <p class="text-gray-900 dark:text-gray-100">{{ $medication['dosage'] }}</p>
                                </div>
                                <div>
                                    <span class="text-sm font-medium text-gray-500 dark:text-gray-400">Instructions:</span>
                                    <p class="text-gray-900 dark:text-gray-100">{{ $medication['instructions'] }}</p>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
            @endif

            <!-- Instructions and Notes -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
                @if($treatment->instructions)
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <h4 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4">
                            <i class="fas fa-clipboard-check mr-2"></i>Patient Instructions
                        </h4>
                        <p class="text-gray-900 dark:text-gray-100">{{ $treatment->instructions }}</p>
                    </div>
                </div>
                @endif
                
                @if($treatment->notes)
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <h4 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4">
                            <i class="fas fa-sticky-note mr-2"></i>Clinical Notes
                        </h4>
                        <p class="text-gray-900 dark:text-gray-100">{{ $treatment->notes }}</p>
                    </div>
                </div>
                @endif
            </div>

            <!-- Follow-up Information -->
            @if($treatment->follow_up_date || $treatment->follow_up_notes)
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6">
                    <h4 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4">
                        <i class="fas fa-calendar-check mr-2"></i>Follow-up Information
                    </h4>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        @if($treatment->follow_up_date)
                        <div>
                            <span class="text-sm font-medium text-gray-500 dark:text-gray-400">Next Follow-up:</span>
                            <p class="text-gray-900 dark:text-gray-100">{{ $treatment->follow_up_date->format('M d, Y') }}</p>
                        </div>
                        @endif
                        
                        @if($treatment->follow_up_notes)
                        <div>
                            <span class="text-sm font-medium text-gray-500 dark:text-gray-400">Follow-up Notes:</span>
                            <p class="text-gray-900 dark:text-gray-100">{{ $treatment->follow_up_notes }}</p>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
            @endif

            <!-- Action Buttons -->
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <div class="flex flex-wrap gap-3">
                        @can('edit treatments')
                        <a href="{{ route('treatments.edit', $treatment) }}" 
                           class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 focus:bg-blue-700 active:bg-blue-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                            <i class="fas fa-edit mr-2"></i>Edit Treatment
                        </a>
                        @endcan
                        
                        @can('delete treatments')
                        <form method="POST" action="{{ route('treatments.destroy', $treatment) }}" class="inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" 
                                    class="inline-flex items-center px-4 py-2 bg-red-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-red-700 focus:bg-red-700 active:bg-red-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150"
                                    onclick="return confirm('Are you sure you want to delete this treatment?')">
                                <i class="fas fa-trash mr-2"></i>Delete Treatment
                            </button>
                        </form>
                        @endcan
                        
                        @if($treatment->status === 'active')
                        <form method="POST" action="{{ route('treatments.update', $treatment) }}" class="inline">
                            @csrf
                            @method('PATCH')
                            <input type="hidden" name="status" value="completed">
                            <button type="submit" 
                                    class="inline-flex items-center px-4 py-2 bg-green-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-green-700 focus:bg-green-700 active:bg-green-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                <i class="fas fa-check mr-2"></i>Mark as Completed
                            </button>
                        </form>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
