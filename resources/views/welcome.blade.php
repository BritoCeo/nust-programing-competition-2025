@extends('layouts.app')

@section('title', 'Welcome to MESMTF')

@section('content')
<!-- Hero Section -->
<section class="relative bg-gradient-to-br from-blue-50 via-cyan-50 to-teal-50 dark:from-gray-900 dark:via-gray-800 dark:to-gray-900 overflow-hidden">
    <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-20 lg:py-32">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-center">
            <!-- Content -->
            <div class="space-y-8">
                <div class="space-y-4">
                    <div class="inline-flex items-center px-4 py-2 bg-blue-100 dark:bg-blue-900/30 text-blue-800 dark:text-blue-200 rounded-full text-sm font-medium">
                        <i class="fas fa-heartbeat mr-2"></i>
                        Ministry of Health & Social Services
                    </div>
                    <h1 class="text-4xl lg:text-6xl font-bold text-gray-900 dark:text-white leading-tight">
                        Medical Expert System for
                        <span class="bg-gradient-to-r from-blue-600 to-cyan-600 bg-clip-text text-transparent">
                            Malaria & Typhoid
                        </span>
                    </h1>
                    <p class="text-xl text-gray-600 dark:text-gray-300 leading-relaxed">
                        AI-powered diagnostic system providing healthcare professionals with intelligent tools 
                        for accurate malaria and typhoid fever diagnosis, comprehensive patient management, 
                        and evidence-based treatment recommendations.
                    </p>
                </div>

                <!-- Features List -->
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div class="flex items-center space-x-3">
                        <div class="w-8 h-8 bg-green-100 dark:bg-green-900/30 rounded-lg flex items-center justify-center">
                            <i class="fas fa-brain text-green-600 dark:text-green-400"></i>
                        </div>
                        <span class="text-gray-700 dark:text-gray-300 font-medium">AI-Powered Diagnosis</span>
                    </div>
                    <div class="flex items-center space-x-3">
                        <div class="w-8 h-8 bg-blue-100 dark:bg-blue-900/30 rounded-lg flex items-center justify-center">
                            <i class="fas fa-shield-alt text-blue-600 dark:text-blue-400"></i>
                        </div>
                        <span class="text-gray-700 dark:text-gray-300 font-medium">HIPAA Compliant</span>
                    </div>
                    <div class="flex items-center space-x-3">
                        <div class="w-8 h-8 bg-purple-100 dark:bg-purple-900/30 rounded-lg flex items-center justify-center">
                            <i class="fas fa-chart-line text-purple-600 dark:text-purple-400"></i>
                        </div>
                        <span class="text-gray-700 dark:text-gray-300 font-medium">Real-time Analytics</span>
                    </div>
                    <div class="flex items-center space-x-3">
                        <div class="w-8 h-8 bg-cyan-100 dark:bg-cyan-900/30 rounded-lg flex items-center justify-center">
                            <i class="fas fa-mobile-alt text-cyan-600 dark:text-cyan-400"></i>
                        </div>
                        <span class="text-gray-700 dark:text-gray-300 font-medium">Mobile Responsive</span>
                    </div>
                </div>

                <!-- CTA Buttons -->
                <div class="flex flex-col sm:flex-row gap-4">
                    @auth
                        <a href="{{ route('dashboard') }}" class="inline-flex items-center justify-center px-8 py-4 bg-gradient-to-r from-blue-600 to-cyan-600 text-white font-semibold rounded-xl hover:shadow-xl hover:scale-105 transition-all duration-300">
                            <i class="fas fa-tachometer-alt mr-2"></i>
                            Go to Dashboard
                        </a>
                    @else
                        <a href="{{ route('register') }}" class="inline-flex items-center justify-center px-8 py-4 bg-gradient-to-r from-blue-600 to-cyan-600 text-white font-semibold rounded-xl hover:shadow-xl hover:scale-105 transition-all duration-300">
                            <i class="fas fa-user-plus mr-2"></i>
                            Get Started
                        </a>
                        <a href="{{ route('login') }}" class="inline-flex items-center justify-center px-8 py-4 bg-white dark:bg-gray-800 text-gray-900 dark:text-white font-semibold rounded-xl border-2 border-gray-200 dark:border-gray-700 hover:border-blue-300 dark:hover:border-blue-600 hover:shadow-lg transition-all duration-300">
                            <i class="fas fa-sign-in-alt mr-2"></i>
                            Login
                        </a>
                    @endauth
                </div>
            </div>

            <!-- Hero Image/Illustration -->
            <div class="relative">
                <div class="relative z-10">
                    <div class="bg-white dark:bg-gray-800 rounded-3xl shadow-2xl p-8 border border-gray-200 dark:border-gray-700">
                        <div class="space-y-6">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center space-x-3">
                                    <div class="w-12 h-12 bg-gradient-to-r from-blue-500 to-cyan-500 rounded-xl flex items-center justify-center">
                                        <i class="fas fa-heartbeat text-white text-xl"></i>
                                    </div>
                                    <div>
                                        <h3 class="text-lg font-bold text-gray-900 dark:text-white">MESMTF System</h3>
                                        <p class="text-sm text-gray-500 dark:text-gray-400">AI Medical Assistant</p>
                                    </div>
                                </div>
                                <div class="flex space-x-2">
                                    <div class="w-3 h-3 bg-red-500 rounded-full"></div>
                                    <div class="w-3 h-3 bg-yellow-500 rounded-full"></div>
                                    <div class="w-3 h-3 bg-green-500 rounded-full"></div>
                                </div>
                            </div>

                            <div class="grid grid-cols-2 gap-4">
                                <div class="bg-blue-50 dark:bg-blue-900/20 rounded-xl p-4">
                                    <div class="flex items-center space-x-3 mb-2">
                                        <i class="fas fa-user-md text-blue-600 dark:text-blue-400"></i>
                                        <span class="text-sm font-medium text-gray-900 dark:text-white">Patients</span>
                                    </div>
                                    <div class="text-2xl font-bold text-blue-600 dark:text-blue-400">1,247</div>
                                    <div class="text-xs text-gray-500 dark:text-gray-400">+12% this month</div>
                                </div>
                                <div class="bg-green-50 dark:bg-green-900/20 rounded-xl p-4">
                                    <div class="flex items-center space-x-3 mb-2">
                                        <i class="fas fa-stethoscope text-green-600 dark:text-green-400"></i>
                                        <span class="text-sm font-medium text-gray-900 dark:text-white">Diagnoses</span>
                                    </div>
                                    <div class="text-2xl font-bold text-green-600 dark:text-green-400">892</div>
                                    <div class="text-xs text-gray-500 dark:text-gray-400">+8% accuracy</div>
                                </div>
                            </div>

                            <div class="bg-gradient-to-r from-purple-50 to-pink-50 dark:from-purple-900/20 dark:to-pink-900/20 rounded-xl p-4">
                                <div class="flex items-center justify-between mb-3">
                                    <div class="flex items-center space-x-2">
                                        <i class="fas fa-brain text-purple-600 dark:text-purple-400"></i>
                                        <span class="font-medium text-gray-900 dark:text-white">AI Diagnosis</span>
                                    </div>
                                    <div class="flex items-center space-x-1">
                                        <div class="w-2 h-2 bg-green-500 rounded-full animate-pulse"></div>
                                        <span class="text-xs text-gray-500 dark:text-gray-400">Active</span>
                                    </div>
                                </div>
                                <div class="space-y-2">
                                    <div class="flex justify-between text-sm">
                                        <span class="text-gray-600 dark:text-gray-400">Malaria Detection</span>
                                        <span class="font-medium text-gray-900 dark:text-white">98.5%</span>
                                    </div>
                                    <div class="w-full bg-gray-200 dark:bg-gray-700 rounded-full h-2">
                                        <div class="bg-gradient-to-r from-purple-500 to-pink-500 h-2 rounded-full" style="width: 98.5%"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Features Section -->
<section class="py-20 bg-white dark:bg-gray-900">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-16">
            <h2 class="text-3xl lg:text-4xl font-bold text-gray-900 dark:text-white mb-4">
                Advanced Medical Technology
            </h2>
            <p class="text-xl text-gray-600 dark:text-gray-300 max-w-3xl mx-auto">
                Our AI-powered system combines cutting-edge technology with medical expertise 
                to provide accurate diagnoses and comprehensive patient care.
            </p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            <!-- Feature 1 -->
            <div class="group bg-white dark:bg-gray-800 rounded-2xl p-8 shadow-lg hover:shadow-2xl transition-all duration-300 border border-gray-100 dark:border-gray-700">
                <div class="w-16 h-16 bg-gradient-to-r from-blue-500 to-blue-600 rounded-2xl flex items-center justify-center mb-6 group-hover:scale-110 transition-transform duration-300">
                    <i class="fas fa-brain text-white text-2xl"></i>
                </div>
                <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-4">AI-Powered Diagnosis</h3>
                <p class="text-gray-600 dark:text-gray-300 mb-6">
                    Advanced machine learning algorithms analyze symptoms and medical history 
                    to provide accurate malaria and typhoid fever diagnoses with 98.5% accuracy.
                </p>
                <div class="flex items-center text-blue-600 dark:text-blue-400 font-medium">
                    <span>Learn More</span>
                    <i class="fas fa-arrow-right ml-2 group-hover:translate-x-1 transition-transform duration-300"></i>
                </div>
            </div>

            <!-- Feature 2 -->
            <div class="group bg-white dark:bg-gray-800 rounded-2xl p-8 shadow-lg hover:shadow-2xl transition-all duration-300 border border-gray-100 dark:border-gray-700">
                <div class="w-16 h-16 bg-gradient-to-r from-green-500 to-green-600 rounded-2xl flex items-center justify-center mb-6 group-hover:scale-110 transition-transform duration-300">
                    <i class="fas fa-shield-alt text-white text-2xl"></i>
                </div>
                <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-4">Secure & Compliant</h3>
                <p class="text-gray-600 dark:text-gray-300 mb-6">
                    HIPAA-compliant system with end-to-end encryption ensuring patient data 
                    privacy and security at all times.
                </p>
                <div class="flex items-center text-green-600 dark:text-green-400 font-medium">
                    <span>Learn More</span>
                    <i class="fas fa-arrow-right ml-2 group-hover:translate-x-1 transition-transform duration-300"></i>
                </div>
            </div>

            <!-- Feature 3 -->
            <div class="group bg-white dark:bg-gray-800 rounded-2xl p-8 shadow-lg hover:shadow-2xl transition-all duration-300 border border-gray-100 dark:border-gray-700">
                <div class="w-16 h-16 bg-gradient-to-r from-purple-500 to-purple-600 rounded-2xl flex items-center justify-center mb-6 group-hover:scale-110 transition-transform duration-300">
                    <i class="fas fa-chart-line text-white text-2xl"></i>
                </div>
                <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-4">Real-time Analytics</h3>
                <p class="text-gray-600 dark:text-gray-300 mb-6">
                    Comprehensive analytics and reporting tools provide insights into 
                    patient trends, treatment outcomes, and system performance.
                </p>
                <div class="flex items-center text-purple-600 dark:text-purple-400 font-medium">
                    <span>Learn More</span>
                    <i class="fas fa-arrow-right ml-2 group-hover:translate-x-1 transition-transform duration-300"></i>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- CTA Section -->
<section class="py-20 bg-gradient-to-r from-blue-600 via-cyan-600 to-teal-600">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
        <div class="max-w-3xl mx-auto">
            <h2 class="text-3xl lg:text-4xl font-bold text-white mb-6">
                Ready to Transform Healthcare?
            </h2>
            <p class="text-xl text-blue-100 mb-8">
                Join thousands of healthcare professionals who trust MESMTF for accurate 
                diagnosis and comprehensive patient management.
            </p>
            <div class="flex flex-col sm:flex-row gap-4 justify-center">
                @auth
                    <a href="{{ route('dashboard') }}" class="inline-flex items-center justify-center px-8 py-4 bg-white text-blue-600 font-semibold rounded-xl hover:shadow-xl hover:scale-105 transition-all duration-300">
                        <i class="fas fa-tachometer-alt mr-2"></i>
                        Access Dashboard
                    </a>
                @else
                    <a href="{{ route('register') }}" class="inline-flex items-center justify-center px-8 py-4 bg-white text-blue-600 font-semibold rounded-xl hover:shadow-xl hover:scale-105 transition-all duration-300">
                        <i class="fas fa-user-plus mr-2"></i>
                        Start Free Trial
                    </a>
                    <a href="{{ route('login') }}" class="inline-flex items-center justify-center px-8 py-4 bg-transparent border-2 border-white text-white font-semibold rounded-xl hover:bg-white hover:text-blue-600 transition-all duration-300">
                        <i class="fas fa-sign-in-alt mr-2"></i>
                        Sign In
                    </a>
                @endauth
            </div>
        </div>
    </div>
</section>
@endsection
