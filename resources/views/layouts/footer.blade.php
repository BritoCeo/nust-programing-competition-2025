<!-- Footer Component -->
<footer class="bg-white dark:bg-gray-800 border-t border-gray-200 dark:border-gray-700 mt-auto">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
            <!-- Brand Section -->
            <div class="col-span-1 md:col-span-2">
                <div class="flex items-center space-x-3 mb-4">
                    <div class="w-10 h-10 bg-gradient-to-r from-blue-500 to-cyan-500 rounded-xl flex items-center justify-center shadow-lg">
                        <i class="fas fa-heartbeat text-white text-lg"></i>
                    </div>
                    <div>
                        <h3 class="text-xl font-bold text-gray-900 dark:text-white">MESMTF</h3>
                        <p class="text-sm text-gray-500 dark:text-gray-400">Medical Expert System</p>
                    </div>
                </div>
                <p class="text-gray-600 dark:text-gray-400 mb-4 max-w-md">
                    Advanced AI-powered medical expert system for malaria and typhoid fever diagnosis, 
                    providing healthcare professionals with intelligent diagnostic tools and comprehensive patient management.
                </p>
                <div class="flex space-x-4">
                    <a href="#" class="text-gray-400 hover:text-blue-500 transition-colors">
                        <i class="fab fa-facebook-f"></i>
                    </a>
                    <a href="#" class="text-gray-400 hover:text-blue-500 transition-colors">
                        <i class="fab fa-twitter"></i>
                    </a>
                    <a href="#" class="text-gray-400 hover:text-blue-500 transition-colors">
                        <i class="fab fa-linkedin-in"></i>
                    </a>
                    <a href="#" class="text-gray-400 hover:text-blue-500 transition-colors">
                        <i class="fab fa-github"></i>
                    </a>
                </div>
            </div>

            <!-- Quick Links -->
            <div>
                <h4 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Quick Links</h4>
                <ul class="space-y-2">
                    <li><a href="{{ route('dashboard') }}" class="text-gray-600 dark:text-gray-400 hover:text-blue-600 dark:hover:text-blue-400 transition-colors">Dashboard</a></li>
                    @auth
                        @if(auth()->user()->role_name === 'patient' || auth()->user()->role_name === 'doctor' || auth()->user()->role_name === 'nurse')
                        <li><a href="{{ route('medical-records.index') }}" class="text-gray-600 dark:text-gray-400 hover:text-blue-600 dark:hover:text-blue-400 transition-colors">Medical Records</a></li>
                        <li><a href="{{ route('appointments.index') }}" class="text-gray-600 dark:text-gray-400 hover:text-blue-600 dark:hover:text-blue-400 transition-colors">Appointments</a></li>
                        @endif
                        @if(auth()->user()->role_name === 'doctor' || auth()->user()->role_name === 'nurse')
                        <li><a href="{{ route('expert-system.index') }}" class="text-gray-600 dark:text-gray-400 hover:text-blue-600 dark:hover:text-blue-400 transition-colors">AI Diagnosis</a></li>
                        @endif
                        @if(auth()->user()->role_name === 'pharmacist' || auth()->user()->role_name === 'doctor')
                        <li><a href="{{ route('pharmacy.index') }}" class="text-gray-600 dark:text-gray-400 hover:text-blue-600 dark:hover:text-blue-400 transition-colors">Pharmacy</a></li>
                        @endif
                    @else
                        <li><a href="{{ route('login') }}" class="text-gray-600 dark:text-gray-400 hover:text-blue-600 dark:hover:text-blue-400 transition-colors">Login</a></li>
                        <li><a href="{{ route('register') }}" class="text-gray-600 dark:text-gray-400 hover:text-blue-600 dark:hover:text-blue-400 transition-colors">Register</a></li>
                    @endauth
                </ul>
            </div>

            <!-- Contact Info -->
            <div>
                <h4 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Contact</h4>
                <div class="space-y-3">
                    <div class="flex items-center space-x-3">
                        <i class="fas fa-map-marker-alt text-blue-500"></i>
                        <span class="text-gray-600 dark:text-gray-400 text-sm">Ministry of Health & Social Services</span>
                    </div>
                    <div class="flex items-center space-x-3">
                        <i class="fas fa-phone text-blue-500"></i>
                        <span class="text-gray-600 dark:text-gray-400 text-sm">+264 61 203 9111</span>
                    </div>
                    <div class="flex items-center space-x-3">
                        <i class="fas fa-envelope text-blue-500"></i>
                        <span class="text-gray-600 dark:text-gray-400 text-sm">info@health.gov.na</span>
                    </div>
                    <div class="flex items-center space-x-3">
                        <i class="fas fa-clock text-blue-500"></i>
                        <span class="text-gray-600 dark:text-gray-400 text-sm">Mon - Fri: 8:00 - 17:00</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Bottom Section -->
        <div class="border-t border-gray-200 dark:border-gray-700 mt-8 pt-8">
            <div class="flex flex-col md:flex-row justify-between items-center space-y-4 md:space-y-0">
                <div class="text-sm text-gray-500 dark:text-gray-400">
                    © {{ date('Y') }} MESMTF. All rights reserved. 
                    <span class="mx-2">•</span>
                    <a href="#" class="hover:text-blue-600 dark:hover:text-blue-400 transition-colors">Privacy Policy</a>
                    <span class="mx-2">•</span>
                    <a href="#" class="hover:text-blue-600 dark:hover:text-blue-400 transition-colors">Terms of Service</a>
                </div>
                <div class="flex items-center space-x-4 text-sm text-gray-500 dark:text-gray-400">
                    <span>Powered by AI Technology</span>
                    <div class="flex items-center space-x-1">
                        <i class="fas fa-shield-alt text-green-500"></i>
                        <span>HIPAA Compliant</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</footer>
