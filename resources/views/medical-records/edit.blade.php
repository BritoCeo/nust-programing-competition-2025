<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                <i class="fas fa-edit mr-2"></i>
                {{ __('Edit Medical Record') }}
            </h2>
            <div class="flex items-center space-x-4">
                <a href="{{ route('medical-records.show', $medicalRecord) }}" 
                   class="inline-flex items-center px-4 py-2 bg-gray-500 border border-transparent rounded-lg font-semibold text-sm text-white hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition-all duration-200">
                    <i class="fas fa-arrow-left mr-2"></i>
                    Back to Record
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <x-forms.medical-form 
                method="POST"
                action="{{ route('medical-records.update', $medicalRecord) }}"
                title="Edit Medical Record"
                submit-text="Update Record"
                :cancel-url="route('medical-records.show', $medicalRecord)"
            >
                @method('PUT')
                
                <!-- Patient and Visit Date -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <x-forms.medical-input 
                            name="patient_id" 
                            label="Patient"
                            type="select"
                            required
                            icon="fas fa-user"
                        >
                            <option value="">Choose a patient...</option>
                            @foreach($patients as $patient)
                                <option value="{{ $patient->id }}" {{ old('patient_id', $medicalRecord->patient_id) == $patient->id ? 'selected' : '' }}>
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
                            value="{{ old('visit_date', $medicalRecord->visit_date->format('Y-m-d')) }}"
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
                        value="{{ old('chief_complaint', $medicalRecord->chief_complaint) }}"
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
                                value="{{ old('history_of_present_illness', $medicalRecord->history_of_present_illness) }}"
                            />
                        </div>
                        
                        <div>
                            <x-forms.medical-input 
                                name="past_medical_history" 
                                label="Past Medical History"
                                type="textarea"
                                rows="4"
                                placeholder="Previous medical conditions, surgeries, etc..."
                                value="{{ old('past_medical_history', $medicalRecord->past_medical_history) }}"
                            />
                        </div>
                        
                        <div>
                            <x-forms.medical-input 
                                name="family_history" 
                                label="Family History"
                                type="textarea"
                                rows="3"
                                placeholder="Relevant family medical history..."
                                value="{{ old('family_history', $medicalRecord->family_history) }}"
                            />
                        </div>
                        
                        <div>
                            <x-forms.medical-input 
                                name="social_history" 
                                label="Social History"
                                type="textarea"
                                rows="3"
                                placeholder="Lifestyle factors, occupation, habits..."
                                value="{{ old('social_history', $medicalRecord->social_history) }}"
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
                            value="{{ old('vital_signs.blood_pressure', $medicalRecord->vital_signs['blood_pressure'] ?? '') }}"
                            icon="fas fa-tint"
                        />
                        
                        <x-forms.medical-input 
                            name="vital_signs[temperature]" 
                            label="Temperature (°C)"
                            placeholder="36.5"
                            value="{{ old('vital_signs.temperature', $medicalRecord->vital_signs['temperature'] ?? '') }}"
                            icon="fas fa-thermometer-half"
                        />
                        
                        <x-forms.medical-input 
                            name="vital_signs[pulse]" 
                            label="Pulse (BPM)"
                            placeholder="72"
                            value="{{ old('vital_signs.pulse', $medicalRecord->vital_signs['pulse'] ?? '') }}"
                            icon="fas fa-heart"
                        />
                        
                        <x-forms.medical-input 
                            name="vital_signs[respiratory_rate]" 
                            label="Respiratory Rate"
                            placeholder="16"
                            value="{{ old('vital_signs.respiratory_rate', $medicalRecord->vital_signs['respiratory_rate'] ?? '') }}"
                            icon="fas fa-lungs"
                        />
                        
                        <x-forms.medical-input 
                            name="vital_signs[weight]" 
                            label="Weight (kg)"
                            placeholder="70"
                            value="{{ old('vital_signs.weight', $medicalRecord->vital_signs['weight'] ?? '') }}"
                            icon="fas fa-weight"
                        />
                        
                        <x-forms.medical-input 
                            name="vital_signs[height]" 
                            label="Height (cm)"
                            placeholder="170"
                            value="{{ old('vital_signs.height', $medicalRecord->vital_signs['height'] ?? '') }}"
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
                        value="{{ old('physical_examination', $medicalRecord->physical_examination) }}"
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
                                value="{{ old('assessment', $medicalRecord->assessment) }}"
                            />
                        </div>
                        
                        <div>
                            <x-forms.medical-input 
                                name="plan" 
                                label="Treatment Plan"
                                type="textarea"
                                rows="4"
                                placeholder="Recommended treatment and follow-up..."
                                value="{{ old('plan', $medicalRecord->plan) }}"
                            />
                        </div>
                    </div>
                </div>

                <!-- Status -->
                <div class="border-t border-gray-200 dark:border-gray-700 pt-6">
                    <h4 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4">
                        <i class="fas fa-info-circle mr-2"></i>
                        Record Status
                    </h4>
                    
                    <x-forms.medical-input 
                        name="status" 
                        label="Status"
                        type="select"
                        icon="fas fa-flag"
                    >
                        <option value="active" {{ old('status', $medicalRecord->status) == 'active' ? 'selected' : '' }}>Active</option>
                        <option value="archived" {{ old('status', $medicalRecord->status) == 'archived' ? 'selected' : '' }}>Archived</option>
                        <option value="deleted" {{ old('status', $medicalRecord->status) == 'deleted' ? 'selected' : '' }}>Deleted</option>
                    </x-forms.medical-input>
                </div>

                <!-- File Attachments -->
                <div class="border-t border-gray-200 dark:border-gray-700 pt-6">
                    <h4 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4">
                        <i class="fas fa-paperclip mr-2"></i>
                        Attachments
                    </h4>
                    
                    @if($medicalRecord->attachments && count($medicalRecord->attachments) > 0)
                    <div class="mb-4">
                        <h5 class="text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Current Attachments:</h5>
                        <div class="space-y-2">
                            @foreach($medicalRecord->attachments as $index => $attachment)
                            <div class="flex items-center justify-between bg-gray-50 dark:bg-gray-700 rounded-lg p-3">
                                <div class="flex items-center">
                                    <i class="fas fa-file text-gray-400 mr-2"></i>
                                    <span class="text-sm text-gray-900 dark:text-gray-100">{{ basename($attachment) }}</span>
                                </div>
                                <a href="{{ route('medical-records.download', ['medicalRecord' => $medicalRecord, 'index' => $index]) }}" 
                                   class="text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-300">
                                    <i class="fas fa-download"></i>
                                </a>
                            </div>
                            @endforeach
                        </div>
                    </div>
                    @endif
                    
                    <div class="border-2 border-dashed border-gray-300 dark:border-gray-600 rounded-lg p-6">
                        <div class="text-center">
                            <i class="fas fa-cloud-upload-alt text-4xl text-gray-400 mb-4"></i>
                            <p class="text-sm text-gray-600 dark:text-gray-400 mb-2">
                                Upload additional medical documents, images, or reports
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
