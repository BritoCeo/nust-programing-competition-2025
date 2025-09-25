<!-- Navigation Component -->
<nav class="bg-white dark:bg-gray-800 shadow-lg border-b border-gray-200 dark:border-gray-700 sticky top-0 z-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between items-center h-16">
            <!-- Logo and Brand -->
            <div class="flex items-center space-x-4">
                <a href="{{ route('dashboard') }}" class="flex items-center space-x-3 group">
                    <div class="w-10 h-10 bg-gradient-to-r from-blue-500 to-cyan-500 rounded-xl flex items-center justify-center shadow-lg group-hover:shadow-xl transition-all duration-300">
                        <i class="fas fa-heartbeat text-white text-lg"></i>
                    </div>
                    <div class="hidden sm:block">
                        <h1 class="text-xl font-bold text-gray-900 dark:text-white">MESMTF</h1>
                        <p class="text-xs text-gray-500 dark:text-gray-400">Medical Expert System</p>
                    </div>
                </a>
            </div>

            <!-- Desktop Navigation -->
            <div class="hidden md:flex items-center space-x-8">
                @auth
                    <!-- Navigation Links -->
                    <div class="flex items-center space-x-6">
                        <a href="{{ route('dashboard') }}" class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                            <i class="fas fa-tachometer-alt"></i>
                            <span>Dashboard</span>
                        </a>

                        @if(auth()->user()->role_name === 'patient' || auth()->user()->role_name === 'doctor' || auth()->user()->role_name === 'nurse')
                        <a href="{{ route('medical-records.index') }}" class="nav-link {{ request()->routeIs('medical-records.*') ? 'active' : '' }}">
                            <i class="fas fa-file-medical"></i>
                            <span>Medical Records</span>
                        </a>
                        @endif

                        @if(auth()->user()->role_name === 'patient' || auth()->user()->role_name === 'doctor' || auth()->user()->role_name === 'nurse')
                        <a href="{{ route('appointments.index') }}" class="nav-link {{ request()->routeIs('appointments.*') ? 'active' : '' }}">
                            <i class="fas fa-calendar-check"></i>
                            <span>Appointments</span>
                        </a>
                        @endif

                        @if(auth()->user()->role_name === 'doctor' || auth()->user()->role_name === 'nurse')
                        <a href="{{ route('expert-system.index') }}" class="nav-link {{ request()->routeIs('expert-system.*') ? 'active' : '' }}">
                            <i class="fas fa-brain"></i>
                            <span>AI Diagnosis</span>
                        </a>
                        @endif

                        @if(auth()->user()->role_name === 'pharmacist' || auth()->user()->role_name === 'doctor')
                        <a href="{{ route('pharmacy.index') }}" class="nav-link {{ request()->routeIs('pharmacy.*') ? 'active' : '' }}">
                            <i class="fas fa-pills"></i>
                            <span>Pharmacy</span>
                        </a>
                        @endif

                        @if(auth()->user()->role_name === 'admin' || auth()->user()->role_name === 'doctor')
                        <a href="{{ route('reports.index') }}" class="nav-link {{ request()->routeIs('reports.*') ? 'active' : '' }}">
                            <i class="fas fa-chart-bar"></i>
                            <span>Reports</span>
                        </a>
                        @endif
                    </div>
                @endauth
            </div>

            <!-- Right Side Actions -->
            <div class="flex items-center space-x-4">
                <!-- Dark Mode Toggle -->
                <button onclick="toggleDarkMode()" class="p-2 rounded-lg text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors">
                    <i class="fas fa-moon dark:hidden"></i>
                    <i class="fas fa-sun hidden dark:block"></i>
                </button>

                @auth
                    <!-- Notifications -->
                    <div class="relative">
                        <button class="p-2 rounded-lg text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors relative">
                            <i class="fas fa-bell"></i>
                            <span class="absolute -top-1 -right-1 w-3 h-3 bg-red-500 rounded-full"></span>
                        </button>
                    </div>

                    <!-- User Menu -->
                    <div class="relative" x-data="{ open: false }">
                        <button @click="open = !open" class="flex items-center space-x-3 p-2 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors">
                            <div class="w-8 h-8 bg-gradient-to-r from-blue-500 to-cyan-500 rounded-full flex items-center justify-center">
                                <i class="fas fa-user text-white text-sm"></i>
                            </div>
                            <div class="hidden sm:block text-left">
                                <p class="text-sm font-medium text-gray-900 dark:text-white">{{ Auth::user()->name }}</p>
                                <p class="text-xs text-gray-500 dark:text-gray-400">{{ ucfirst(Auth::user()->role_name) }}</p>
                            </div>
                            <i class="fas fa-chevron-down text-xs text-gray-500"></i>
                        </button>

                        <!-- Dropdown Menu -->
                        <div x-show="open" @click.away="open = false" x-transition class="absolute right-0 mt-2 w-48 bg-white dark:bg-gray-800 rounded-lg shadow-lg border border-gray-200 dark:border-gray-700 py-1 z-50">
                            <a href="{{ route('profile.edit') }}" class="flex items-center px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700">
                                <i class="fas fa-user-circle mr-3"></i>
                                Profile
                            </a>
                            <a href="{{ route('profile.edit') }}" class="flex items-center px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700">
                                <i class="fas fa-cog mr-3"></i>
                                Settings
                            </a>
                            <hr class="my-1 border-gray-200 dark:border-gray-700">
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="flex items-center w-full px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700">
                                    <i class="fas fa-sign-out-alt mr-3"></i>
                                    Logout
                                </button>
                            </form>
                        </div>
                    </div>
                @else
                    <!-- Guest Actions -->
                    <div class="flex items-center space-x-3">
                        <a href="{{ route('login') }}" class="text-gray-700 dark:text-gray-300 hover:text-blue-600 dark:hover:text-blue-400 font-medium transition-colors">
                            Login
                        </a>
                        <a href="{{ route('register') }}" class="bg-gradient-to-r from-blue-500 to-cyan-500 text-white px-4 py-2 rounded-lg font-medium hover:shadow-lg transition-all duration-300">
                            Register
                        </a>
                    </div>
                @endauth

                <!-- Mobile Menu Button -->
                <button class="md:hidden p-2 rounded-lg text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors" onclick="toggleMobileMenu()">
                    <i class="fas fa-bars"></i>
                </button>
            </div>
        </div>

        <!-- Mobile Navigation -->
        <div id="mobile-menu" class="md:hidden hidden border-t border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800">
            <div class="px-4 py-3 space-y-2">
                @auth
                    <a href="{{ route('dashboard') }}" class="mobile-nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                        <i class="fas fa-tachometer-alt"></i>
                        <span>Dashboard</span>
                    </a>

                    @if(auth()->user()->role_name === 'patient' || auth()->user()->role_name === 'doctor' || auth()->user()->role_name === 'nurse')
                    <a href="{{ route('medical-records.index') }}" class="mobile-nav-link {{ request()->routeIs('medical-records.*') ? 'active' : '' }}">
                        <i class="fas fa-file-medical"></i>
                        <span>Medical Records</span>
                    </a>
                    @endif

                    @if(auth()->user()->role_name === 'patient' || auth()->user()->role_name === 'doctor' || auth()->user()->role_name === 'nurse')
                    <a href="{{ route('appointments.index') }}" class="mobile-nav-link {{ request()->routeIs('appointments.*') ? 'active' : '' }}">
                        <i class="fas fa-calendar-check"></i>
                        <span>Appointments</span>
                    </a>
                    @endif

                    @if(auth()->user()->role_name === 'doctor' || auth()->user()->role_name === 'nurse')
                    <a href="{{ route('expert-system.index') }}" class="mobile-nav-link {{ request()->routeIs('expert-system.*') ? 'active' : '' }}">
                        <i class="fas fa-brain"></i>
                        <span>AI Diagnosis</span>
                    </a>
                    @endif

                    @if(auth()->user()->role_name === 'pharmacist' || auth()->user()->role_name === 'doctor')
                    <a href="{{ route('pharmacy.index') }}" class="mobile-nav-link {{ request()->routeIs('pharmacy.*') ? 'active' : '' }}">
                        <i class="fas fa-pills"></i>
                        <span>Pharmacy</span>
                    </a>
                    @endif

                    @if(auth()->user()->role_name === 'admin' || auth()->user()->role_name === 'doctor')
                    <a href="{{ route('reports.index') }}" class="mobile-nav-link {{ request()->routeIs('reports.*') ? 'active' : '' }}">
                        <i class="fas fa-chart-bar"></i>
                        <span>Reports</span>
                    </a>
                    @endif
                @else
                    <a href="{{ route('login') }}" class="mobile-nav-link">
                        <i class="fas fa-sign-in-alt"></i>
                        <span>Login</span>
                    </a>
                    <a href="{{ route('register') }}" class="mobile-nav-link">
                        <i class="fas fa-user-plus"></i>
                        <span>Register</span>
                    </a>
                @endauth
            </div>
        </div>
    </div>
</nav>

<style>
    .nav-link {
        @apply flex items-center space-x-2 px-3 py-2 rounded-lg text-gray-700 dark:text-gray-300 hover:text-blue-600 dark:hover:text-blue-400 hover:bg-blue-50 dark:hover:bg-blue-900/20 transition-all duration-300;
    }
    
    .nav-link.active {
        @apply text-blue-600 dark:text-blue-400 bg-blue-50 dark:bg-blue-900/20;
    }
    
    .nav-link i {
        @apply w-4 h-4;
    }
    
    .mobile-nav-link {
        @apply flex items-center space-x-3 px-3 py-2 rounded-lg text-gray-700 dark:text-gray-300 hover:text-blue-600 dark:hover:text-blue-400 hover:bg-blue-50 dark:hover:bg-blue-900/20 transition-all duration-300;
    }
    
    .mobile-nav-link.active {
        @apply text-blue-600 dark:text-blue-400 bg-blue-50 dark:bg-blue-900/20;
    }
    
    .mobile-nav-link i {
        @apply w-4 h-4;
    }
</style>

<script>
    function toggleMobileMenu() {
        const menu = document.getElementById('mobile-menu');
        menu.classList.toggle('hidden');
    }
</script>