<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                <i class="fas fa-file-medical mr-2"></i>
                {{ __('Medical Record Details') }}
            </h2>
            <div class="flex items-center space-x-4">
                <a href="{{ route('medical-records.index') }}" 
                   class="inline-flex items-center px-4 py-2 bg-gray-500 border border-transparent rounded-lg font-semibold text-sm text-white hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition-all duration-200">
                    <i class="fas fa-arrow-left mr-2"></i>
                    Back to Records
                </a>
                @can('edit medical records')
                <a href="{{ route('medical-records.edit', $medicalRecord) }}" 
                   class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-lg font-semibold text-sm text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-all duration-200">
                    <i class="fas fa-edit mr-2"></i>
                    Edit Record
                </a>
                @endcan
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-6xl mx-auto sm:px-6 lg:px-8">
            <!-- Record Header -->
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6">
                    <div class="flex items-center justify-between mb-4">
                        <div>
                            <h3 class="text-2xl font-bold text-gray-900 dark:text-white">
                                Record #{{ $medicalRecord->record_number }}
                            </h3>
                            <p class="text-gray-600 dark:text-gray-400">
                                Visit Date: {{ $medicalRecord->visit_date->format('l, F j, Y') }}
                            </p>
                        </div>
                        <div class="text-right">
                            <span class="inline-flex px-3 py-1 text-sm font-semibold rounded-full
                                {{ $medicalRecord->status === 'active' ? 'bg-green-100 text-green-800' : 
                                   ($medicalRecord->status === 'archived' ? 'bg-yellow-100 text-yellow-800' : 'bg-red-100 text-red-800') }}">
                                {{ ucfirst($medicalRecord->status) }}
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
                                <span class="ml-2 text-gray-900 dark:text-gray-100">{{ $medicalRecord->patient->name }}</span>
                            </div>
                            <div>
                                <span class="text-sm font-medium text-gray-500 dark:text-gray-400">Email:</span>
                                <span class="ml-2 text-gray-900 dark:text-gray-100">{{ $medicalRecord->patient->email }}</span>
                            </div>
                            <div>
                                <span class="text-sm font-medium text-gray-500 dark:text-gray-400">Phone:</span>
                                <span class="ml-2 text-gray-900 dark:text-gray-100">{{ $medicalRecord->patient->phone ?? 'Not provided' }}</span>
                            </div>
                            <div>
                                <span class="text-sm font-medium text-gray-500 dark:text-gray-400">Date of Birth:</span>
                                <span class="ml-2 text-gray-900 dark:text-gray-100">
                                    {{ $medicalRecord->patient->date_of_birth ? $medicalRecord->patient->date_of_birth->format('M d, Y') : 'Not provided' }}
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
                                <span class="ml-2 text-gray-900 dark:text-gray-100">{{ $medicalRecord->doctor->name }}</span>
                            </div>
                            <div>
                                <span class="text-sm font-medium text-gray-500 dark:text-gray-400">Specialization:</span>
                                <span class="ml-2 text-gray-900 dark:text-gray-100">{{ $medicalRecord->doctor->specialization ?? 'Not specified' }}</span>
                            </div>
                            <div>
                                <span class="text-sm font-medium text-gray-500 dark:text-gray-400">License:</span>
                                <span class="ml-2 text-gray-900 dark:text-gray-100">{{ $medicalRecord->doctor->medical_license_number ?? 'Not provided' }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Chief Complaint -->
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6">
                    <h4 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4">
                        <i class="fas fa-comment-medical mr-2"></i>Chief Complaint
                    </h4>
                    <p class="text-gray-900 dark:text-gray-100">{{ $medicalRecord->chief_complaint }}</p>
                </div>
            </div>

            <!-- Medical History -->
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6">
                    <h4 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4">
                        <i class="fas fa-history mr-2"></i>Medical History
                    </h4>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        @if($medicalRecord->history_of_present_illness)
                        <div>
                            <h5 class="font-medium text-gray-900 dark:text-gray-100 mb-2">History of Present Illness</h5>
                            <p class="text-gray-700 dark:text-gray-300">{{ $medicalRecord->history_of_present_illness }}</p>
                        </div>
                        @endif
                        
                        @if($medicalRecord->past_medical_history)
                        <div>
                            <h5 class="font-medium text-gray-900 dark:text-gray-100 mb-2">Past Medical History</h5>
                            <p class="text-gray-700 dark:text-gray-300">{{ $medicalRecord->past_medical_history }}</p>
                        </div>
                        @endif
                        
                        @if($medicalRecord->family_history)
                        <div>
                            <h5 class="font-medium text-gray-900 dark:text-gray-100 mb-2">Family History</h5>
                            <p class="text-gray-700 dark:text-gray-300">{{ $medicalRecord->family_history }}</p>
                        </div>
                        @endif
                        
                        @if($medicalRecord->social_history)
                        <div>
                            <h5 class="font-medium text-gray-900 dark:text-gray-100 mb-2">Social History</h5>
                            <p class="text-gray-700 dark:text-gray-300">{{ $medicalRecord->social_history }}</p>
                        </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Vital Signs -->
            @if($medicalRecord->vital_signs)
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6">
                    <h4 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4">
                        <i class="fas fa-heartbeat mr-2"></i>Vital Signs
                    </h4>
                    <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-4">
                        @if(isset($medicalRecord->vital_signs['blood_pressure']))
                        <div class="text-center">
                            <div class="text-2xl font-bold text-blue-600 dark:text-blue-400">{{ $medicalRecord->vital_signs['blood_pressure'] }}</div>
                            <div class="text-sm text-gray-500 dark:text-gray-400">Blood Pressure</div>
                        </div>
                        @endif
                        
                        @if(isset($medicalRecord->vital_signs['temperature']))
                        <div class="text-center">
                            <div class="text-2xl font-bold text-red-600 dark:text-red-400">{{ $medicalRecord->vital_signs['temperature'] }}°C</div>
                            <div class="text-sm text-gray-500 dark:text-gray-400">Temperature</div>
                        </div>
                        @endif
                        
                        @if(isset($medicalRecord->vital_signs['pulse']))
                        <div class="text-center">
                            <div class="text-2xl font-bold text-green-600 dark:text-green-400">{{ $medicalRecord->vital_signs['pulse'] }} BPM</div>
                            <div class="text-sm text-gray-500 dark:text-gray-400">Pulse</div>
                        </div>
                        @endif
                        
                        @if(isset($medicalRecord->vital_signs['respiratory_rate']))
                        <div class="text-center">
                            <div class="text-2xl font-bold text-purple-600 dark:text-purple-400">{{ $medicalRecord->vital_signs['respiratory_rate'] }}</div>
                            <div class="text-sm text-gray-500 dark:text-gray-400">Respiratory Rate</div>
                        </div>
                        @endif
                        
                        @if(isset($medicalRecord->vital_signs['weight']))
                        <div class="text-center">
                            <div class="text-2xl font-bold text-orange-600 dark:text-orange-400">{{ $medicalRecord->vital_signs['weight'] }} kg</div>
                            <div class="text-sm text-gray-500 dark:text-gray-400">Weight</div>
                        </div>
                        @endif
                        
                        @if(isset($medicalRecord->vital_signs['height']))
                        <div class="text-center">
                            <div class="text-2xl font-bold text-indigo-600 dark:text-indigo-400">{{ $medicalRecord->vital_signs['height'] }} cm</div>
                            <div class="text-sm text-gray-500 dark:text-gray-400">Height</div>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
            @endif

            <!-- Physical Examination -->
            @if($medicalRecord->physical_examination)
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6">
                    <h4 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4">
                        <i class="fas fa-stethoscope mr-2"></i>Physical Examination
                    </h4>
                    <p class="text-gray-900 dark:text-gray-100">{{ $medicalRecord->physical_examination }}</p>
                </div>
            </div>
            @endif

            <!-- Assessment and Plan -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
                @if($medicalRecord->assessment)
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <h4 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4">
                            <i class="fas fa-clipboard-check mr-2"></i>Assessment
                        </h4>
                        <p class="text-gray-900 dark:text-gray-100">{{ $medicalRecord->assessment }}</p>
                    </div>
                </div>
                @endif
                
                @if($medicalRecord->plan)
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <h4 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4">
                            <i class="fas fa-tasks mr-2"></i>Treatment Plan
                        </h4>
                        <p class="text-gray-900 dark:text-gray-100">{{ $medicalRecord->plan }}</p>
                    </div>
                </div>
                @endif
            </div>

            <!-- Attachments -->
            @if($medicalRecord->attachments && count($medicalRecord->attachments) > 0)
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6">
                    <h4 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4">
                        <i class="fas fa-paperclip mr-2"></i>Attachments
                    </h4>
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                        @foreach($medicalRecord->attachments as $index => $attachment)
                        <div class="border border-gray-200 dark:border-gray-600 rounded-lg p-4">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center">
                                    <i class="fas fa-file text-gray-400 mr-2"></i>
                                    <span class="text-sm text-gray-900 dark:text-gray-100 truncate">
                                        {{ basename($attachment) }}
                                    </span>
                                </div>
                                <a href="{{ route('medical-records.download', ['medicalRecord' => $medicalRecord, 'index' => $index]) }}" 
                                   class="text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-300">
                                    <i class="fas fa-download"></i>
                                </a>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
            @endif

            <!-- Action Buttons -->
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <div class="flex flex-wrap gap-3">
                        @can('edit medical records')
                        <a href="{{ route('medical-records.edit', $medicalRecord) }}" 
                           class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 focus:bg-blue-700 active:bg-blue-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                            <i class="fas fa-edit mr-2"></i>Edit Record
                        </a>
                        @endcan
                        
                        @can('delete medical records')
                        <form method="POST" action="{{ route('medical-records.destroy', $medicalRecord) }}" class="inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" 
                                    class="inline-flex items-center px-4 py-2 bg-red-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-red-700 focus:bg-red-700 active:bg-red-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150"
                                    onclick="return confirm('Are you sure you want to delete this medical record?')">
                                <i class="fas fa-trash mr-2"></i>Delete Record
                            </button>
                        </form>
                        @endcan
                        
                        <button onclick="window.print()" 
                                class="inline-flex items-center px-4 py-2 bg-gray-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                            <i class="fas fa-print mr-2"></i>Print Record
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
