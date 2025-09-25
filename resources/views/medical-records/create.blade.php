<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                <i class="fas fa-plus-circle mr-2"></i>
                {{ __('Create Medical Record') }}
            </h2>
            <div class="flex items-center space-x-4">
                <a href="{{ route('medical-records.index') }}" 
                   class="inline-flex items-center px-4 py-2 bg-gray-500 border border-transparent rounded-lg font-semibold text-sm text-white hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition-all duration-200">
                    <i class="fas fa-arrow-left mr-2"></i>
                    Back to Records
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <x-forms.medical-form 
                method="POST"
                action="{{ route('medical-records.store') }}"
                title="New Medical Record"
                submit-text="Create Record"
                :cancel-url="route('medical-records.index')"
            >
                <!-- Patient Selection -->
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
                            name="visit_date" 
                            label="Visit Date"
                            type="date"
                            required
                            value="{{ old('visit_date', now()->format('Y-m-d')) }}"
                            icon="fas fa-calendar"
                        />
                    </div>
                </div>

                <!-- Chief Complaint -->
                <div>
                    <x-forms.medical-input 
                        name="chief_complaint" 
                        label="Chief Complaint"
                        type="textarea"
                        required
                        rows="3"
                        placeholder="Primary reason for the visit..."
                        value="{{ old('chief_complaint') }}"
                        icon="fas fa-comment-medical"
                    />
                </div>

                <!-- Medical History Section -->
                <div class="border-t border-gray-200 dark:border-gray-700 pt-6">
                    <h4 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4">
                        <i class="fas fa-history mr-2"></i>
                        Medical History
                    </h4>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <x-forms.medical-input 
                                name="history_of_present_illness" 
                                label="History of Present Illness"
                                type="textarea"
                                rows="4"
                                placeholder="Detailed description of current symptoms..."
                                value="{{ old('history_of_present_illness') }}"
                            />
                        </div>
                        
                        <div>
                            <x-forms.medical-input 
                                name="past_medical_history" 
                                label="Past Medical History"
                                type="textarea"
                                rows="4"
                                placeholder="Previous medical conditions, surgeries, etc..."
                                value="{{ old('past_medical_history') }}"
                            />
                        </div>
                        
                        <div>
                            <x-forms.medical-input 
                                name="family_history" 
                                label="Family History"
                                type="textarea"
                                rows="3"
                                placeholder="Relevant family medical history..."
                                value="{{ old('family_history') }}"
                            />
                        </div>
                        
                        <div>
                            <x-forms.medical-input 
                                name="social_history" 
                                label="Social History"
                                type="textarea"
                                rows="3"
                                placeholder="Lifestyle factors, occupation, habits..."
                                value="{{ old('social_history') }}"
                            />
                        </div>
                    </div>
                </div>

                <!-- Vital Signs Section -->
                <div class="border-t border-gray-200 dark:border-gray-700 pt-6">
                    <h4 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4">
                        <i class="fas fa-heartbeat mr-2"></i>
                        Vital Signs
                    </h4>
                    
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <x-forms.medical-input 
                            name="vital_signs[blood_pressure]" 
                            label="Blood Pressure"
                            placeholder="120/80"
                            value="{{ old('vital_signs.blood_pressure') }}"
                            icon="fas fa-tint"
                        />
                        
                        <x-forms.medical-input 
                            name="vital_signs[temperature]" 
                            label="Temperature (°C)"
                            placeholder="36.5"
                            value="{{ old('vital_signs.temperature') }}"
                            icon="fas fa-thermometer-half"
                        />
                        
                        <x-forms.medical-input 
                            name="vital_signs[pulse]" 
                            label="Pulse (BPM)"
                            placeholder="72"
                            value="{{ old('vital_signs.pulse') }}"
                            icon="fas fa-heart"
                        />
                        
                        <x-forms.medical-input 
                            name="vital_signs[respiratory_rate]" 
                            label="Respiratory Rate"
                            placeholder="16"
                            value="{{ old('vital_signs.respiratory_rate') }}"
                            icon="fas fa-lungs"
                        />
                        
                        <x-forms.medical-input 
                            name="vital_signs[weight]" 
                            label="Weight (kg)"
                            placeholder="70"
                            value="{{ old('vital_signs.weight') }}"
                            icon="fas fa-weight"
                        />
                        
                        <x-forms.medical-input 
                            name="vital_signs[height]" 
                            label="Height (cm)"
                            placeholder="170"
                            value="{{ old('vital_signs.height') }}"
                            icon="fas fa-ruler-vertical"
                        />
                    </div>
                </div>

                <!-- Physical Examination -->
                <div class="border-t border-gray-200 dark:border-gray-700 pt-6">
                    <h4 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4">
                        <i class="fas fa-stethoscope mr-2"></i>
                        Physical Examination
                    </h4>
                    
                    <x-forms.medical-input 
                        name="physical_examination" 
                        label="Physical Examination Findings"
                        type="textarea"
                        rows="4"
                        placeholder="Detailed physical examination findings..."
                        value="{{ old('physical_examination') }}"
                    />
                </div>

                <!-- Assessment and Plan -->
                <div class="border-t border-gray-200 dark:border-gray-700 pt-6">
                    <h4 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4">
                        <i class="fas fa-clipboard-check mr-2"></i>
                        Assessment & Plan
                    </h4>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <x-forms.medical-input 
                                name="assessment" 
                                label="Assessment"
                                type="textarea"
                                rows="4"
                                placeholder="Clinical assessment and diagnosis..."
                                value="{{ old('assessment') }}"
                            />
                        </div>
                        
                        <div>
                            <x-forms.medical-input 
                                name="plan" 
                                label="Treatment Plan"
                                type="textarea"
                                rows="4"
                                placeholder="Recommended treatment and follow-up..."
                                value="{{ old('plan') }}"
                            />
                        </div>
                    </div>
                </div>

                <!-- File Attachments -->
                <div class="border-t border-gray-200 dark:border-gray-700 pt-6">
                    <h4 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4">
                        <i class="fas fa-paperclip mr-2"></i>
                        Attachments
                    </h4>
                    
                    <div class="border-2 border-dashed border-gray-300 dark:border-gray-600 rounded-lg p-6">
                        <div class="text-center">
                            <i class="fas fa-cloud-upload-alt text-4xl text-gray-400 mb-4"></i>
                            <p class="text-sm text-gray-600 dark:text-gray-400 mb-2">
                                Upload medical documents, images, or reports
                            </p>
                            <p class="text-xs text-gray-500 dark:text-gray-500">
                                Supported formats: PDF, JPG, PNG, DOC, DOCX (Max 10MB each)
                            </p>
                            <input type="file" 
                                   name="attachments[]" 
                                   multiple 
                                   accept=".pdf,.jpg,.jpeg,.png,.doc,.docx"
                                   class="mt-4 block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-medical-50 file:text-medical-700 hover:file:bg-medical-100">
                        </div>
                    </div>
                </div>
            </x-forms.medical-form>
        </div>
    </div>
</x-app-layout>