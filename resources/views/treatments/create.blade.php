<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                <i class="fas fa-plus-circle mr-2"></i>
                {{ __('Create Treatment') }}
            </h2>
            <div class="flex items-center space-x-4">
                <a href="{{ route('treatments.index') }}" 
                   class="inline-flex items-center px-4 py-2 bg-gray-500 border border-transparent rounded-lg font-semibold text-sm text-white hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition-all duration-200">
                    <i class="fas fa-arrow-left mr-2"></i>
                    Back to Treatments
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <x-forms.medical-form 
                method="POST"
                action="{{ route('treatments.store') }}"
                title="New Treatment Plan"
                submit-text="Create Treatment"
                :cancel-url="route('treatments.index')"
            >
                <!-- Patient and Doctor Selection -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <x-forms.medical-input 
                            name="patient_id" 
                            label="Select Patient"
                            type="select"
                            required
                            icon="fas fa-user"
                        >
                            <option value="">Choose a patient...</option>
                            @foreach($patients as $patient)
                                <option value="{{ $patient->id }}" {{ old('patient_id') == $patient->id ? 'selected' : '' }}>
                                    {{ $patient->name }} ({{ $patient->email }})
                                </option>
                            @endforeach
                        </x-forms.medical-input>
                    </div>
                    
                    <div>
                        <x-forms.medical-input 
                            name="doctor_id" 
                            label="Assigned Doctor"
                            type="select"
                            required
                            icon="fas fa-user-md"
                        >
                            <option value="">Choose a doctor...</option>
                            @foreach($doctors as $doctor)
                                <option value="{{ $doctor->id }}" {{ old('doctor_id') == $doctor->id ? 'selected' : '' }}>
                                    {{ $doctor->name }} ({{ $doctor->specialization }})
                                </option>
                            @endforeach
                        </x-forms.medical-input>
                    </div>
                </div>

                <!-- Treatment Details -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <x-forms.medical-input 
                            name="treatment_name" 
                            label="Treatment Name"
                            required
                            placeholder="e.g., Malaria Treatment Protocol"
                            value="{{ old('treatment_name') }}"
                            icon="fas fa-pills"
                        />
                    </div>
                    
                    <div>
                        <x-forms.medical-input 
                            name="treatment_type" 
                            label="Treatment Type"
                            type="select"
                            required
                            icon="fas fa-tags"
                        >
                            <option value="">Select type...</option>
                            <option value="medication" {{ old('treatment_type') == 'medication' ? 'selected' : '' }}>Medication</option>
                            <option value="therapy" {{ old('treatment_type') == 'therapy' ? 'selected' : '' }}>Therapy</option>
                            <option value="surgery" {{ old('treatment_type') == 'surgery' ? 'selected' : '' }}>Surgery</option>
                            <option value="lifestyle" {{ old('treatment_type') == 'lifestyle' ? 'selected' : '' }}>Lifestyle Change</option>
                            <option value="monitoring" {{ old('treatment_type') == 'monitoring' ? 'selected' : '' }}>Monitoring</option>
                        </x-forms.medical-input>
                    </div>
                </div>

                <!-- Treatment Description -->
                <div>
                    <x-forms.medical-input 
                        name="description" 
                        label="Treatment Description"
                        type="textarea"
                        required
                        rows="4"
                        placeholder="Detailed description of the treatment plan..."
                        value="{{ old('description') }}"
                        icon="fas fa-clipboard-list"
                    />
                </div>

                <!-- Treatment Schedule -->
                <div class="border-t border-gray-200 dark:border-gray-700 pt-6">
                    <h4 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4">
                        <i class="fas fa-calendar-alt mr-2"></i>
                        Treatment Schedule
                    </h4>
                    
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <x-forms.medical-input 
                            name="start_date" 
                            label="Start Date"
                            type="date"
                            required
                            value="{{ old('start_date', now()->format('Y-m-d')) }}"
                            icon="fas fa-calendar"
                        />
                        
                        <x-forms.medical-input 
                            name="end_date" 
                            label="End Date"
                            type="date"
                            value="{{ old('end_date') }}"
                            icon="fas fa-calendar"
                        />
                        
                        <x-forms.medical-input 
                            name="frequency" 
                            label="Frequency"
                            placeholder="e.g., Twice daily, Every 8 hours"
                            value="{{ old('frequency') }}"
                            icon="fas fa-clock"
                        />
                    </div>
                </div>

                <!-- Medications -->
                <div class="border-t border-gray-200 dark:border-gray-700 pt-6">
                    <h4 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4">
                        <i class="fas fa-pills mr-2"></i>
                        Medications
                    </h4>
                    
                    <div class="space-y-4">
                        <div class="border border-gray-300 dark:border-gray-600 rounded-lg p-4">
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                <x-forms.medical-input 
                                    name="medications[0][name]" 
                                    label="Medication Name"
                                    placeholder="e.g., Artemether-Lumefantrine"
                                    icon="fas fa-pills"
                                />
                                
                                <x-forms.medical-input 
                                    name="medications[0][dosage]" 
                                    label="Dosage"
                                    placeholder="e.g., 20mg/120mg"
                                    icon="fas fa-weight"
                                />
                                
                                <x-forms.medical-input 
                                    name="medications[0][instructions]" 
                                    label="Instructions"
                                    placeholder="e.g., Take with food"
                                    icon="fas fa-info-circle"
                                />
                            </div>
                        </div>
                    </div>
                    
                    <button type="button" id="add-medication" class="mt-2 text-sm text-indigo-600 hover:text-indigo-800 dark:text-indigo-400 dark:hover:text-indigo-300">
                        <i class="fas fa-plus mr-1"></i>Add Another Medication
                    </button>
                </div>

                <!-- Instructions -->
                <div class="border-t border-gray-200 dark:border-gray-700 pt-6">
                    <h4 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4">
                        <i class="fas fa-clipboard-check mr-2"></i>
                        Instructions & Notes
                    </h4>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <x-forms.medical-input 
                                name="instructions" 
                                label="Patient Instructions"
                                type="textarea"
                                rows="4"
                                placeholder="Specific instructions for the patient..."
                                value="{{ old('instructions') }}"
                            />
                        </div>
                        
                        <div>
                            <x-forms.medical-input 
                                name="notes" 
                                label="Clinical Notes"
                                type="textarea"
                                rows="4"
                                placeholder="Additional clinical notes..."
                                value="{{ old('notes') }}"
                            />
                        </div>
                    </div>
                </div>

                <!-- Follow-up -->
                <div class="border-t border-gray-200 dark:border-gray-700 pt-6">
                    <h4 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4">
                        <i class="fas fa-calendar-check mr-2"></i>
                        Follow-up Schedule
                    </h4>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <x-forms.medical-input 
                            name="follow_up_date" 
                            label="Next Follow-up Date"
                            type="date"
                            value="{{ old('follow_up_date') }}"
                            icon="fas fa-calendar"
                        />
                        
                        <x-forms.medical-input 
                            name="follow_up_notes" 
                            label="Follow-up Notes"
                            placeholder="What to monitor, when to return..."
                            value="{{ old('follow_up_notes') }}"
                            icon="fas fa-stethoscope"
                        />
                    </div>
                </div>
            </x-forms.medical-form>
        </div>
    </div>

    @push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            let medicationCount = 1;
            const addMedicationBtn = document.getElementById('add-medication');
            
            addMedicationBtn.addEventListener('click', function() {
                const container = this.previousElementSibling;
                const newMedication = document.createElement('div');
                newMedication.className = 'border border-gray-300 dark:border-gray-600 rounded-lg p-4 mt-4';
                newMedication.innerHTML = `
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Medication Name</label>
                            <input type="text" name="medications[${medicationCount}][name]" 
                                   placeholder="e.g., Paracetamol" 
                                   class="mt-1 block w-full border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 focus:ring-indigo-500 focus:border-indigo-500 rounded-md shadow-sm">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Dosage</label>
                            <input type="text" name="medications[${medicationCount}][dosage]" 
                                   placeholder="e.g., 500mg" 
                                   class="mt-1 block w-full border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 focus:ring-indigo-500 focus:border-indigo-500 rounded-md shadow-sm">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Instructions</label>
                            <input type="text" name="medications[${medicationCount}][instructions]" 
                                   placeholder="e.g., Take with water" 
                                   class="mt-1 block w-full border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 focus:ring-indigo-500 focus:border-indigo-500 rounded-md shadow-sm">
                        </div>
                    </div>
                    <button type="button" class="mt-2 text-sm text-red-600 hover:text-red-800 dark:text-red-400 dark:hover:text-red-300 remove-medication">
                        <i class="fas fa-trash mr-1"></i>Remove
                    </button>
                `;
                
                container.appendChild(newMedication);
                medicationCount++;
                
                // Add remove functionality
                newMedication.querySelector('.remove-medication').addEventListener('click', function() {
                    newMedication.remove();
                });
            });
        });
    </script>
    @endpush
</x-app-layout>