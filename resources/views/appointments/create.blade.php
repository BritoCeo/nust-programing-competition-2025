<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                <i class="fas fa-plus-circle mr-2"></i>
                {{ __('Schedule Appointment') }}
            </h2>
            <div class="flex items-center space-x-4">
                <a href="{{ route('appointments.index') }}" 
                   class="inline-flex items-center px-4 py-2 bg-gray-500 border border-transparent rounded-lg font-semibold text-sm text-white hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition-all duration-200">
                    <i class="fas fa-arrow-left mr-2"></i>
                    Back to Appointments
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <x-forms.medical-form 
                method="POST"
                action="{{ route('appointments.store') }}"
                title="Schedule New Appointment"
                submit-text="Schedule Appointment"
                :cancel-url="route('appointments.index')"
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
                            label="Select Doctor"
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

                <!-- Appointment Details -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <x-forms.medical-input 
                            name="appointment_date" 
                            label="Appointment Date"
                            type="date"
                            required
                            value="{{ old('appointment_date', now()->format('Y-m-d')) }}"
                            icon="fas fa-calendar"
                        />
                    </div>
                    
                    <div>
                        <x-forms.medical-input 
                            name="appointment_time" 
                            label="Appointment Time"
                            type="time"
                            required
                            value="{{ old('appointment_time') }}"
                            icon="fas fa-clock"
                        />
                    </div>
                </div>

                <!-- Appointment Type and Duration -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <x-forms.medical-input 
                            name="type" 
                            label="Appointment Type"
                            type="select"
                            required
                            icon="fas fa-tags"
                        >
                            <option value="">Select type...</option>
                            <option value="consultation" {{ old('type') == 'consultation' ? 'selected' : '' }}>Consultation</option>
                            <option value="follow_up" {{ old('type') == 'follow_up' ? 'selected' : '' }}>Follow-up</option>
                            <option value="emergency" {{ old('type') == 'emergency' ? 'selected' : '' }}>Emergency</option>
                            <option value="routine_checkup" {{ old('type') == 'routine_checkup' ? 'selected' : '' }}>Routine Checkup</option>
                            <option value="specialist" {{ old('type') == 'specialist' ? 'selected' : '' }}>Specialist</option>
                        </x-forms.medical-input>
                    </div>
                    
                    <div>
                        <x-forms.medical-input 
                            name="duration_minutes" 
                            label="Duration (minutes)"
                            type="number"
                            placeholder="30"
                            value="{{ old('duration_minutes', 30) }}"
                            icon="fas fa-hourglass-half"
                        />
                    </div>
                </div>

                <!-- Reason and Notes -->
                <div>
                    <x-forms.medical-input 
                        name="reason" 
                        label="Reason for Appointment"
                        type="textarea"
                        required
                        rows="3"
                        placeholder="Primary reason for the appointment..."
                        value="{{ old('reason') }}"
                        icon="fas fa-comment-medical"
                    />
                </div>

                <div>
                    <x-forms.medical-input 
                        name="notes" 
                        label="Additional Notes"
                        type="textarea"
                        rows="3"
                        placeholder="Any additional information or special requirements..."
                        value="{{ old('notes') }}"
                        icon="fas fa-sticky-note"
                    />
                </div>

                <!-- Priority Level -->
                <div class="border-t border-gray-200 dark:border-gray-700 pt-6">
                    <h4 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4">
                        <i class="fas fa-exclamation-triangle mr-2"></i>
                        Priority & Special Requirements
                    </h4>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <x-forms.medical-input 
                                name="priority" 
                                label="Priority Level"
                                type="select"
                                icon="fas fa-flag"
                            >
                                <option value="normal" {{ old('priority') == 'normal' ? 'selected' : '' }}>Normal</option>
                                <option value="high" {{ old('priority') == 'high' ? 'selected' : '' }}>High</option>
                                <option value="urgent" {{ old('priority') == 'urgent' ? 'selected' : '' }}>Urgent</option>
                                <option value="emergency" {{ old('priority') == 'emergency' ? 'selected' : '' }}>Emergency</option>
                            </x-forms.medical-input>
                        </div>
                        
                        <div>
                            <x-forms.medical-input 
                                name="special_requirements" 
                                label="Special Requirements"
                                placeholder="e.g., Wheelchair access, interpreter needed"
                                value="{{ old('special_requirements') }}"
                                icon="fas fa-wheelchair"
                            />
                        </div>
                    </div>
                </div>

                <!-- Reminder Settings -->
                <div class="border-t border-gray-200 dark:border-gray-700 pt-6">
                    <h4 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4">
                        <i class="fas fa-bell mr-2"></i>
                        Reminder Settings
                    </h4>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <x-forms.medical-input 
                                name="reminder_method" 
                                label="Reminder Method"
                                type="select"
                                icon="fas fa-envelope"
                            >
                                <option value="email" {{ old('reminder_method') == 'email' ? 'selected' : '' }}>Email</option>
                                <option value="sms" {{ old('reminder_method') == 'sms' ? 'selected' : '' }}>SMS</option>
                                <option value="both" {{ old('reminder_method') == 'both' ? 'selected' : '' }}>Both Email & SMS</option>
                                <option value="none" {{ old('reminder_method') == 'none' ? 'selected' : '' }}>No Reminder</option>
                            </x-forms.medical-input>
                        </div>
                        
                        <div>
                            <x-forms.medical-input 
                                name="reminder_time" 
                                label="Reminder Time"
                                type="select"
                                icon="fas fa-clock"
                            >
                                <option value="1_hour" {{ old('reminder_time') == '1_hour' ? 'selected' : '' }}>1 Hour Before</option>
                                <option value="2_hours" {{ old('reminder_time') == '2_hours' ? 'selected' : '' }}>2 Hours Before</option>
                                <option value="1_day" {{ old('reminder_time') == '1_day' ? 'selected' : '' }}>1 Day Before</option>
                                <option value="2_days" {{ old('reminder_time') == '2_days' ? 'selected' : '' }}>2 Days Before</option>
                            </x-forms.medical-input>
                        </div>
                    </div>
                </div>
            </x-forms.medical-form>
        </div>
    </div>
</x-app-layout>
