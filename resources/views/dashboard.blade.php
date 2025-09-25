@extends('layouts.app')

@section('title', 'Dashboard - MESMTF')

@section('content')
<div class="min-h-screen bg-gray-50 dark:bg-gray-900">
    <!-- Header Section -->
    <div class="bg-white dark:bg-gray-800 shadow-sm border-b border-gray-200 dark:border-gray-700">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
            <div class="flex items-center justify-between">
                <div class="flex items-center space-x-4">
                    <div class="flex items-center space-x-3">
                        <div class="w-12 h-12 bg-gradient-to-r from-blue-500 to-cyan-500 rounded-xl flex items-center justify-center shadow-lg">
                            <i class="fas fa-heartbeat text-white text-xl"></i>
                        </div>
                        <div>
                            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">
                                {{ $data['title'] ?? 'Dashboard' }}
                            </h1>
                            <p class="text-sm text-gray-500 dark:text-gray-400">
                                Welcome back, {{ Auth::user()->name }}
                            </p>
                        </div>
                    </div>
                </div>
                <div class="flex items-center space-x-4">
                    <div class="text-sm text-gray-600 dark:text-gray-300 bg-gray-100 dark:bg-gray-700 px-4 py-2 rounded-lg">
                        <i class="fas fa-calendar-alt mr-2 text-blue-500"></i>
                        {{ now()->format('M d, Y') }}
                    </div>
                    <div class="text-sm text-gray-600 dark:text-gray-300 bg-gray-100 dark:bg-gray-700 px-4 py-2 rounded-lg">
                        <i class="fas fa-clock mr-2 text-green-500"></i>
                        {{ now()->format('h:i A') }}
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Welcome Banner -->
            <div class="bg-gradient-to-r from-blue-500 via-cyan-500 to-teal-500 overflow-hidden shadow-xl rounded-2xl mb-8">
                <div class="p-8 text-white">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center space-x-6">
                            <div class="flex-shrink-0">
                                <div class="w-16 h-16 bg-white/20 backdrop-blur-sm rounded-2xl flex items-center justify-center">
                                    <i class="fas fa-user-md text-3xl"></i>
                                </div>
                            </div>
                            <div>
                                <h3 class="text-3xl font-bold mb-2">Welcome back, {{ Auth::user()->name }}!</h3>
                                <p class="text-blue-100 text-lg">
                                    <i class="fas fa-user-tag mr-2"></i>
                                    {{ ucfirst(Auth::user()->role_name) }} • {{ Auth::user()->email }}
                                </p>
                                <div class="mt-3 flex items-center space-x-4">
                                    <div class="flex items-center space-x-2 bg-white/20 backdrop-blur-sm px-3 py-1 rounded-full">
                                        <i class="fas fa-shield-alt text-sm"></i>
                                        <span class="text-sm font-medium">Secure Access</span>
                                    </div>
                                    <div class="flex items-center space-x-2 bg-white/20 backdrop-blur-sm px-3 py-1 rounded-full">
                                        <i class="fas fa-clock text-sm"></i>
                                        <span class="text-sm font-medium">Last Login: {{ now()->format('M d, Y h:i A') }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="hidden lg:block">
                            <div class="w-32 h-32 bg-white/10 backdrop-blur-sm rounded-full flex items-center justify-center">
                                <i class="fas fa-stethoscope text-6xl text-white/80"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Statistics Cards -->
            @if(isset($data['stats']) && count($data['stats']) > 0)
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                @php
                    $statConfigs = [
                        'total_users' => ['icon' => 'fas fa-users', 'color' => 'from-blue-500 to-blue-600', 'bg' => 'bg-blue-50', 'text' => 'text-blue-600'],
                        'total_patients' => ['icon' => 'fas fa-user-injured', 'color' => 'from-green-500 to-green-600', 'bg' => 'bg-green-50', 'text' => 'text-green-600'],
                        'total_doctors' => ['icon' => 'fas fa-user-md', 'color' => 'from-cyan-500 to-cyan-600', 'bg' => 'bg-cyan-50', 'text' => 'text-cyan-600'],
                        'total_medical_records' => ['icon' => 'fas fa-file-medical', 'color' => 'from-purple-500 to-purple-600', 'bg' => 'bg-purple-50', 'text' => 'text-purple-600'],
                        'total_appointments' => ['icon' => 'fas fa-calendar-check', 'color' => 'from-orange-500 to-orange-600', 'bg' => 'bg-orange-50', 'text' => 'text-orange-600'],
                        'total_diagnoses' => ['icon' => 'fas fa-stethoscope', 'color' => 'from-red-500 to-red-600', 'bg' => 'bg-red-50', 'text' => 'text-red-600'],
                        'active_treatments' => ['icon' => 'fas fa-pills', 'color' => 'from-teal-500 to-teal-600', 'bg' => 'bg-teal-50', 'text' => 'text-teal-600'],
                        'pending_prescriptions' => ['icon' => 'fas fa-prescription-bottle', 'color' => 'from-indigo-500 to-indigo-600', 'bg' => 'bg-indigo-50', 'text' => 'text-indigo-600'],
                        'today_appointments' => ['icon' => 'fas fa-calendar-day', 'color' => 'from-pink-500 to-pink-600', 'bg' => 'bg-pink-50', 'text' => 'text-pink-600'],
                        'my_patients' => ['icon' => 'fas fa-user-friends', 'color' => 'from-emerald-500 to-emerald-600', 'bg' => 'bg-emerald-50', 'text' => 'text-emerald-600'],
                        'upcoming_appointments' => ['icon' => 'fas fa-clock', 'color' => 'from-amber-500 to-amber-600', 'bg' => 'bg-amber-50', 'text' => 'text-amber-600'],
                        'medical_records' => ['icon' => 'fas fa-clipboard-list', 'color' => 'from-violet-500 to-violet-600', 'bg' => 'bg-violet-50', 'text' => 'text-violet-600']
                    ];
                @endphp
                @foreach($data['stats'] as $key => $value)
                    @php
                        $config = $statConfigs[$key] ?? ['icon' => 'fas fa-chart-bar', 'color' => 'from-gray-500 to-gray-600', 'bg' => 'bg-gray-50', 'text' => 'text-gray-600'];
                    @endphp
                    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg hover:shadow-xl transition-all duration-300 border border-gray-100 dark:border-gray-700 overflow-hidden group">
                        <div class="p-6">
                            <div class="flex items-center justify-between mb-4">
                                <div class="flex items-center space-x-3">
                                    <div class="w-12 h-12 bg-gradient-to-r {{ $config['color'] }} rounded-xl flex items-center justify-center shadow-lg">
                                        <i class="{{ $config['icon'] }} text-white text-lg"></i>
                                    </div>
                                    <div>
                                        <h3 class="text-sm font-semibold text-gray-600 dark:text-gray-400 uppercase tracking-wide">
                                            {{ ucwords(str_replace('_', ' ', $key)) }}
                                        </h3>
                                        <p class="text-xs text-gray-500 dark:text-gray-500 mt-1">Total count in system</p>
                                    </div>
                                </div>
                                <div class="text-right">
                                    <div class="text-3xl font-bold text-gray-900 dark:text-white group-hover:scale-110 transition-transform duration-300">
                                        {{ number_format($value) }}
                                    </div>
                                    <div class="flex items-center mt-1">
                                        <i class="fas fa-arrow-up text-green-500 text-xs mr-1"></i>
                                        <span class="text-xs text-green-600 font-medium">+12%</span>
                                    </div>
                                </div>
                            </div>
                            <div class="flex items-center justify-between">
                                <div class="flex items-center space-x-2">
                                    <div class="w-2 h-2 bg-gradient-to-r {{ $config['color'] }} rounded-full"></div>
                                    <span class="text-xs text-gray-500 dark:text-gray-400">Active</span>
                                </div>
                                <button class="text-xs text-gray-400 hover:text-gray-600 dark:hover:text-gray-300 transition-colors">
                                    <i class="fas fa-external-link-alt"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
            @endif

            <!-- Module Access Cards -->
            @if(isset($data['modules']) && count($data['modules']) > 0)
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @if(isset($data['modules']['medical_records']) && $data['modules']['medical_records'])
                <a href="{{ route('medical-records.index') }}" class="group">
                    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg hover:shadow-2xl transition-all duration-300 border border-gray-100 dark:border-gray-700 overflow-hidden group-hover:border-blue-200 dark:group-hover:border-blue-700">
                        <div class="p-6">
                            <div class="flex items-center justify-between mb-4">
                                <div class="w-14 h-14 bg-gradient-to-r from-blue-500 to-blue-600 rounded-2xl flex items-center justify-center shadow-lg group-hover:scale-110 transition-transform duration-300">
                                    <i class="fas fa-file-medical text-white text-xl"></i>
                                </div>
                                <div class="flex items-center space-x-2">
                                    <span class="px-3 py-1 bg-green-100 text-green-800 text-xs font-semibold rounded-full">Active</span>
                                    <i class="fas fa-arrow-right text-blue-500 group-hover:translate-x-1 transition-transform duration-300"></i>
                                </div>
                            </div>
                            <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-2">Medical Records</h3>
                            <p class="text-gray-600 dark:text-gray-400 mb-4">Manage patient medical records and history</p>
                            <div class="flex items-center justify-between">
                                <div class="flex items-center text-sm text-blue-600 dark:text-blue-400">
                                    <i class="fas fa-chart-line mr-2"></i>
                                    <span class="font-medium">View & Manage</span>
                                </div>
                                <div class="flex items-center space-x-2 text-xs text-gray-500">
                                    <i class="fas fa-users"></i>
                                    <span>Records</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </a>
                @endif

                @if(isset($data['modules']['appointments']) && $data['modules']['appointments'])
                <a href="{{ route('appointments.index') }}" class="group">
                    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg hover:shadow-2xl transition-all duration-300 border border-gray-100 dark:border-gray-700 overflow-hidden group-hover:border-green-200 dark:group-hover:border-green-700">
                        <div class="p-6">
                            <div class="flex items-center justify-between mb-4">
                                <div class="w-14 h-14 bg-gradient-to-r from-green-500 to-green-600 rounded-2xl flex items-center justify-center shadow-lg group-hover:scale-110 transition-transform duration-300">
                                    <i class="fas fa-calendar-check text-white text-xl"></i>
                                </div>
                                <div class="flex items-center space-x-2">
                                    <span class="px-3 py-1 bg-blue-100 text-blue-800 text-xs font-semibold rounded-full">Today</span>
                                    <i class="fas fa-arrow-right text-green-500 group-hover:translate-x-1 transition-transform duration-300"></i>
                                </div>
                            </div>
                            <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-2">Appointments</h3>
                            <p class="text-gray-600 dark:text-gray-400 mb-4">Schedule and manage patient appointments</p>
                            <div class="flex items-center justify-between">
                                <div class="flex items-center text-sm text-green-600 dark:text-green-400">
                                    <i class="fas fa-clock mr-2"></i>
                                    <span class="font-medium">Schedule & Track</span>
                                </div>
                                <div class="flex items-center space-x-2 text-xs text-gray-500">
                                    <i class="fas fa-calendar"></i>
                                    <span>Schedule</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </a>
                @endif

                @if(isset($data['modules']['expert_system']) && $data['modules']['expert_system'])
                <a href="{{ route('expert-system.index') }}" class="group">
                    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg hover:shadow-2xl transition-all duration-300 border border-gray-100 dark:border-gray-700 overflow-hidden group-hover:border-purple-200 dark:group-hover:border-purple-700">
                        <div class="p-6">
                            <div class="flex items-center justify-between mb-4">
                                <div class="w-14 h-14 bg-gradient-to-r from-purple-500 to-purple-600 rounded-2xl flex items-center justify-center shadow-lg group-hover:scale-110 transition-transform duration-300">
                                    <i class="fas fa-brain text-white text-xl"></i>
                                </div>
                                <div class="flex items-center space-x-2">
                                    <span class="px-3 py-1 bg-purple-100 text-purple-800 text-xs font-semibold rounded-full">AI</span>
                                    <i class="fas fa-arrow-right text-purple-500 group-hover:translate-x-1 transition-transform duration-300"></i>
                                </div>
                            </div>
                            <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-2">AI Expert System</h3>
                            <p class="text-gray-600 dark:text-gray-400 mb-4">AI-powered diagnosis for malaria & typhoid</p>
                            <div class="flex items-center justify-between">
                                <div class="flex items-center text-sm text-purple-600 dark:text-purple-400">
                                    <i class="fas fa-microscope mr-2"></i>
                                    <span class="font-medium">Smart Diagnosis</span>
                                </div>
                                <div class="flex items-center space-x-2 text-xs text-gray-500">
                                    <i class="fas fa-robot"></i>
                                    <span>AI Powered</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </a>
                @endif

                @if(isset($data['modules']['pharmacy']) && $data['modules']['pharmacy'])
                <a href="{{ route('pharmacy.index') }}" class="group">
                    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg hover:shadow-2xl transition-all duration-300 border border-gray-100 dark:border-gray-700 overflow-hidden group-hover:border-teal-200 dark:group-hover:border-teal-700">
                        <div class="p-6">
                            <div class="flex items-center justify-between mb-4">
                                <div class="w-14 h-14 bg-gradient-to-r from-teal-500 to-teal-600 rounded-2xl flex items-center justify-center shadow-lg group-hover:scale-110 transition-transform duration-300">
                                    <i class="fas fa-pills text-white text-xl"></i>
                                </div>
                                <div class="flex items-center space-x-2">
                                    <span class="px-3 py-1 bg-teal-100 text-teal-800 text-xs font-semibold rounded-full">Inventory</span>
                                    <i class="fas fa-arrow-right text-teal-500 group-hover:translate-x-1 transition-transform duration-300"></i>
                                </div>
                            </div>
                            <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-2">Pharmacy</h3>
                            <p class="text-gray-600 dark:text-gray-400 mb-4">Manage drug inventory and prescriptions</p>
                            <div class="flex items-center justify-between">
                                <div class="flex items-center text-sm text-teal-600 dark:text-teal-400">
                                    <i class="fas fa-prescription-bottle mr-2"></i>
                                    <span class="font-medium">Drug Management</span>
                                </div>
                                <div class="flex items-center space-x-2 text-xs text-gray-500">
                                    <i class="fas fa-pills"></i>
                                    <span>Medication</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </a>
                @endif

                @if(isset($data['modules']['reports']) && $data['modules']['reports'])
                <a href="{{ route('reports.index') }}" class="group">
                    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg hover:shadow-2xl transition-all duration-300 border border-gray-100 dark:border-gray-700 overflow-hidden group-hover:border-indigo-200 dark:group-hover:border-indigo-700">
                        <div class="p-6">
                            <div class="flex items-center justify-between mb-4">
                                <div class="w-14 h-14 bg-gradient-to-r from-indigo-500 to-indigo-600 rounded-2xl flex items-center justify-center shadow-lg group-hover:scale-110 transition-transform duration-300">
                                    <i class="fas fa-chart-bar text-white text-xl"></i>
                                </div>
                                <div class="flex items-center space-x-2">
                                    <span class="px-3 py-1 bg-indigo-100 text-indigo-800 text-xs font-semibold rounded-full">Analytics</span>
                                    <i class="fas fa-arrow-right text-indigo-500 group-hover:translate-x-1 transition-transform duration-300"></i>
                                </div>
                            </div>
                            <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-2">Reports & Analytics</h3>
                            <p class="text-gray-600 dark:text-gray-400 mb-4">Generate comprehensive medical reports</p>
                            <div class="flex items-center justify-between">
                                <div class="flex items-center text-sm text-indigo-600 dark:text-indigo-400">
                                    <i class="fas fa-download mr-2"></i>
                                    <span class="font-medium">Export & Analyze</span>
                                </div>
                                <div class="flex items-center space-x-2 text-xs text-gray-500">
                                    <i class="fas fa-chart-line"></i>
                                    <span>Reports</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </a>
                @endif
            </div>
            @endif

            <!-- Recent Activity Section -->
            @if(isset($data['recent_activity']) && count($data['recent_activity']) > 0)
            <div class="mt-8">
                <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-6">Recent Activity</h3>
                <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg border border-gray-100 dark:border-gray-700 overflow-hidden">
                    <div class="p-6">
                        <div class="space-y-4">
                            @foreach($data['recent_activity'] as $activity)
                            <div class="flex items-center space-x-4 p-4 bg-gray-50 dark:bg-gray-700 rounded-xl hover:bg-gray-100 dark:hover:bg-gray-600 transition-colors">
                                <div class="w-10 h-10 bg-gradient-to-r from-{{ $activity['color'] }}-500 to-{{ $activity['color'] }}-600 rounded-xl flex items-center justify-center">
                                    <i class="{{ $activity['icon'] }} text-white text-sm"></i>
                                </div>
                                <div class="flex-1">
                                    <h4 class="font-semibold text-gray-900 dark:text-white">{{ $activity['title'] }}</h4>
                                    <p class="text-sm text-gray-600 dark:text-gray-400">{{ $activity['description'] }}</p>
                                </div>
                                <div class="text-right">
                                    <span class="text-xs text-gray-500 dark:text-gray-400">{{ $activity['timestamp']->diffForHumans() }}</span>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
            @endif

            <!-- Upcoming Appointments Section -->
            @if(isset($data['upcoming_appointments']) && count($data['upcoming_appointments']) > 0)
            <div class="mt-8">
                <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-6">Upcoming Appointments</h3>
                <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg border border-gray-100 dark:border-gray-700 overflow-hidden">
                    <div class="p-6">
                        <div class="space-y-4">
                            @foreach($data['upcoming_appointments'] as $appointment)
                            <div class="flex items-center space-x-4 p-4 bg-gradient-to-r from-green-50 to-blue-50 dark:from-green-900/20 dark:to-blue-900/20 rounded-xl border border-green-200 dark:border-green-700">
                                <div class="w-12 h-12 bg-gradient-to-r from-green-500 to-blue-500 rounded-xl flex items-center justify-center">
                                    <i class="fas fa-calendar-check text-white text-lg"></i>
                                </div>
                                <div class="flex-1">
                                    <h4 class="font-semibold text-gray-900 dark:text-white">
                                        @if(Auth::user()->hasRole('patient'))
                                            Dr. {{ $appointment->doctor->name }}
                                        @else
                                            {{ $appointment->patient->name }}
                                        @endif
                                    </h4>
                                    <p class="text-sm text-gray-600 dark:text-gray-400">{{ $appointment->reason }}</p>
                                    <div class="flex items-center space-x-4 mt-2">
                                        <span class="text-xs bg-blue-100 text-blue-800 px-2 py-1 rounded-full">
                                            <i class="fas fa-calendar mr-1"></i>
                                            {{ $appointment->appointment_date->format('M d, Y') }}
                                        </span>
                                        <span class="text-xs bg-green-100 text-green-800 px-2 py-1 rounded-full">
                                            <i class="fas fa-clock mr-1"></i>
                                            {{ $appointment->appointment_time }}
                                        </span>
                                        <span class="text-xs bg-purple-100 text-purple-800 px-2 py-1 rounded-full">
                                            {{ ucfirst($appointment->status) }}
                                        </span>
                                    </div>
                                </div>
                                <div class="text-right">
                                    <a href="{{ route('appointments.show', $appointment) }}" class="text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-300">
                                        <i class="fas fa-external-link-alt"></i>
                                    </a>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
            @endif
        </div>
    </div>
@endsection
