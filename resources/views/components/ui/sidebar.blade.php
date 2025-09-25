@props([
    'user' => null,
    'currentRoute' => null
])

<div class="hidden lg:flex lg:flex-shrink-0">
    <div class="flex flex-col w-64">
        <!-- Sidebar header -->
        <div class="flex flex-col h-0 flex-1 bg-white dark:bg-gray-800 border-r border-gray-200 dark:border-gray-700">
            <div class="flex-1 flex flex-col pt-5 pb-4 overflow-y-auto">
                <!-- Logo -->
                <div class="flex items-center flex-shrink-0 px-4">
                    <div class="flex items-center">
                        <div class="w-10 h-10 bg-gradient-to-r from-medical-500 to-medical-600 rounded-lg flex items-center justify-center">
                            <i class="fas fa-user-md text-white text-xl"></i>
                        </div>
                        <div class="ml-3">
                            <h1 class="text-xl font-bold text-gray-900 dark:text-gray-100">MESMTF</h1>
                            <p class="text-xs text-gray-500 dark:text-gray-400">Medical Expert System</p>
                        </div>
                    </div>
                </div>
                
                <!-- Navigation -->
                <nav class="mt-8 flex-1 px-2 space-y-1">
                    <!-- Dashboard -->
                    <a href="{{ route('dashboard') }}" 
                       class="group flex items-center px-2 py-2 text-sm font-medium rounded-md transition-colors duration-200 {{ $currentRoute === 'dashboard' ? 'bg-medical-100 text-medical-900 dark:bg-medical-900/20 dark:text-medical-300' : 'text-gray-600 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 hover:text-gray-900 dark:hover:text-gray-100' }}">
                        <i class="fas fa-tachometer-alt mr-3 text-lg {{ $currentRoute === 'dashboard' ? 'text-medical-500' : 'text-gray-400 group-hover:text-gray-500' }}"></i>
                        Dashboard
                    </a>
                    
                    <!-- Medical Records -->
                    @can('view medical records')
                    <a href="{{ route('medical-records.index') }}" 
                       class="group flex items-center px-2 py-2 text-sm font-medium rounded-md transition-colors duration-200 {{ str_starts_with($currentRoute, 'medical-records') ? 'bg-medical-100 text-medical-900 dark:bg-medical-900/20 dark:text-medical-300' : 'text-gray-600 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 hover:text-gray-900 dark:hover:text-gray-100' }}">
                        <i class="fas fa-file-medical mr-3 text-lg {{ str_starts_with($currentRoute, 'medical-records') ? 'text-medical-500' : 'text-gray-400 group-hover:text-gray-500' }}"></i>
                        Medical Records
                    </a>
                    @endcan
                    
                    <!-- Appointments -->
                    @can('view appointments')
                    <a href="{{ route('appointments.index') }}" 
                       class="group flex items-center px-2 py-2 text-sm font-medium rounded-md transition-colors duration-200 {{ str_starts_with($currentRoute, 'appointments') ? 'bg-health-100 text-health-900 dark:bg-health-900/20 dark:text-health-300' : 'text-gray-600 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 hover:text-gray-900 dark:hover:text-gray-100' }}">
                        <i class="fas fa-calendar-check mr-3 text-lg {{ str_starts_with($currentRoute, 'appointments') ? 'text-health-500' : 'text-gray-400 group-hover:text-gray-500' }}"></i>
                        Appointments
                    </a>
                    @endcan
                    
                    <!-- Expert System -->
                    @can('use expert system')
                    <a href="{{ route('expert-system.index') }}" 
                       class="group flex items-center px-2 py-2 text-sm font-medium rounded-md transition-colors duration-200 {{ str_starts_with($currentRoute, 'expert-system') ? 'bg-diagnosis-100 text-diagnosis-900 dark:bg-diagnosis-900/20 dark:text-diagnosis-300' : 'text-gray-600 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 hover:text-gray-900 dark:hover:text-gray-100' }}">
                        <i class="fas fa-brain mr-3 text-lg {{ str_starts_with($currentRoute, 'expert-system') ? 'text-diagnosis-500' : 'text-gray-400 group-hover:text-gray-500' }}"></i>
                        Expert System
                    </a>
                    @endcan
                    
                    <!-- Pharmacy -->
                    @can('view pharmacy')
                    <a href="{{ route('pharmacy.index') }}" 
                       class="group flex items-center px-2 py-2 text-sm font-medium rounded-md transition-colors duration-200 {{ str_starts_with($currentRoute, 'pharmacy') ? 'bg-health-100 text-health-900 dark:bg-health-900/20 dark:text-health-300' : 'text-gray-600 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 hover:text-gray-900 dark:hover:text-gray-100' }}">
                        <i class="fas fa-pills mr-3 text-lg {{ str_starts_with($currentRoute, 'pharmacy') ? 'text-health-500' : 'text-gray-400 group-hover:text-gray-500' }}"></i>
                        Pharmacy
                    </a>
                    @endcan
                    
                    <!-- Reports -->
                    @can('view reports')
                    <a href="{{ route('reports.index') }}" 
                       class="group flex items-center px-2 py-2 text-sm font-medium rounded-md transition-colors duration-200 {{ str_starts_with($currentRoute, 'reports') ? 'bg-medical-100 text-medical-900 dark:bg-medical-900/20 dark:text-medical-300' : 'text-gray-600 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 hover:text-gray-900 dark:hover:text-gray-100' }}">
                        <i class="fas fa-chart-bar mr-3 text-lg {{ str_starts_with($currentRoute, 'reports') ? 'text-medical-500' : 'text-gray-400 group-hover:text-gray-500' }}"></i>
                        Reports
                    </a>
                    @endcan
                    
                    <!-- User Management (Admin only) -->
                    @can('view users')
                    <a href="{{ route('users.index') }}" 
                       class="group flex items-center px-2 py-2 text-sm font-medium rounded-md transition-colors duration-200 {{ str_starts_with($currentRoute, 'users') ? 'bg-gray-100 text-gray-900 dark:bg-gray-700 dark:text-gray-300' : 'text-gray-600 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 hover:text-gray-900 dark:hover:text-gray-100' }}">
                        <i class="fas fa-users mr-3 text-lg {{ str_starts_with($currentRoute, 'users') ? 'text-gray-500' : 'text-gray-400 group-hover:text-gray-500' }}"></i>
                        User Management
                    </a>
                    @endcan
                </nav>
            </div>
            
            <!-- User section -->
            <div class="flex-shrink-0 flex border-t border-gray-200 dark:border-gray-700 p-4">
                <div class="flex items-center w-full">
                    <div class="flex-shrink-0">
                        <div class="w-10 h-10 bg-gradient-to-r from-medical-500 to-medical-600 rounded-full flex items-center justify-center">
                            <i class="fas fa-user text-white"></i>
                        </div>
                    </div>
                    <div class="ml-3 flex-1">
                        <p class="text-sm font-medium text-gray-800 dark:text-gray-200">{{ $user->name ?? 'User' }}</p>
                        <p class="text-xs text-gray-500 dark:text-gray-400">{{ ucfirst($user->role_name ?? 'Role') }}</p>
                    </div>
                    <div class="flex-shrink-0">
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300">
                                <i class="fas fa-sign-out-alt"></i>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
