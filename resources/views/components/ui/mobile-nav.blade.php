@props([
    'user' => null
])

<div class="lg:hidden">
    <div class="fixed inset-0 z-50 flex">
        <!-- Mobile menu overlay -->
        <div class="fixed inset-0 bg-gray-600 bg-opacity-75" x-data="{ open: false }" x-show="open" @click="open = false"></div>
        
        <!-- Mobile menu panel -->
        <div class="relative flex-1 flex flex-col max-w-xs w-full bg-white dark:bg-gray-800">
            <div class="absolute top-0 right-0 -mr-12 pt-2">
                <button type="button" class="ml-1 flex items-center justify-center h-10 w-10 rounded-full focus:outline-none focus:ring-2 focus:ring-inset focus:ring-white">
                    <span class="sr-only">Close sidebar</span>
                    <i class="fas fa-times text-white"></i>
                </button>
            </div>
            
            <div class="flex-1 h-0 pt-5 pb-4 overflow-y-auto">
                <div class="flex-shrink-0 flex items-center px-4">
                    <div class="flex items-center">
                        <div class="w-8 h-8 bg-medical-500 rounded-lg flex items-center justify-center">
                            <i class="fas fa-user-md text-white"></i>
                        </div>
                        <div class="ml-3">
                            <h1 class="text-lg font-semibold text-gray-900 dark:text-gray-100">MESMTF</h1>
                            <p class="text-sm text-gray-500 dark:text-gray-400">Medical Expert System</p>
                        </div>
                    </div>
                </div>
                
                <nav class="mt-5 px-2 space-y-1">
                    <!-- Dashboard -->
                    <a href="{{ route('dashboard') }}" 
                       class="group flex items-center px-2 py-2 text-base font-medium rounded-md text-gray-600 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 hover:text-gray-900 dark:hover:text-gray-100">
                        <i class="fas fa-tachometer-alt mr-4 text-gray-400 group-hover:text-gray-500"></i>
                        Dashboard
                    </a>
                    
                    <!-- Medical Records -->
                    @can('view medical records')
                    <a href="{{ route('medical-records.index') }}" 
                       class="group flex items-center px-2 py-2 text-base font-medium rounded-md text-gray-600 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 hover:text-gray-900 dark:hover:text-gray-100">
                        <i class="fas fa-file-medical mr-4 text-gray-400 group-hover:text-gray-500"></i>
                        Medical Records
                    </a>
                    @endcan
                    
                    <!-- Appointments -->
                    @can('view appointments')
                    <a href="{{ route('appointments.index') }}" 
                       class="group flex items-center px-2 py-2 text-base font-medium rounded-md text-gray-600 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 hover:text-gray-900 dark:hover:text-gray-100">
                        <i class="fas fa-calendar-check mr-4 text-gray-400 group-hover:text-gray-500"></i>
                        Appointments
                    </a>
                    @endcan
                    
                    <!-- Expert System -->
                    @can('use expert system')
                    <a href="{{ route('expert-system.index') }}" 
                       class="group flex items-center px-2 py-2 text-base font-medium rounded-md text-gray-600 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 hover:text-gray-900 dark:hover:text-gray-100">
                        <i class="fas fa-brain mr-4 text-gray-400 group-hover:text-gray-500"></i>
                        Expert System
                    </a>
                    @endcan
                    
                    <!-- Pharmacy -->
                    @can('view pharmacy')
                    <a href="{{ route('pharmacy.index') }}" 
                       class="group flex items-center px-2 py-2 text-base font-medium rounded-md text-gray-600 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 hover:text-gray-900 dark:hover:text-gray-100">
                        <i class="fas fa-pills mr-4 text-gray-400 group-hover:text-gray-500"></i>
                        Pharmacy
                    </a>
                    @endcan
                    
                    <!-- Reports -->
                    @can('view reports')
                    <a href="{{ route('reports.index') }}" 
                       class="group flex items-center px-2 py-2 text-base font-medium rounded-md text-gray-600 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 hover:text-gray-900 dark:hover:text-gray-100">
                        <i class="fas fa-chart-bar mr-4 text-gray-400 group-hover:text-gray-500"></i>
                        Reports
                    </a>
                    @endcan
                </nav>
            </div>
            
            <!-- User section -->
            <div class="flex-shrink-0 flex border-t border-gray-200 dark:border-gray-700 p-4">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-10 h-10 bg-medical-500 rounded-full flex items-center justify-center">
                            <i class="fas fa-user text-white"></i>
                        </div>
                    </div>
                    <div class="ml-3">
                        <p class="text-base font-medium text-gray-800 dark:text-gray-200">{{ $user->name ?? 'User' }}</p>
                        <p class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ $user->role_name ?? 'Role' }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
