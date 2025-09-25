@props([
    'title' => '',
    'subtitle' => '',
    'actions' => null,
    'breadcrumbs' => []
])

<div class="bg-white dark:bg-gray-800 shadow-sm border-b border-gray-200 dark:border-gray-700">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between items-center py-6">
            <!-- Left side -->
            <div class="flex items-center">
                <!-- Mobile menu button -->
                <button type="button" class="lg:hidden -ml-0.5 -mt-0.5 h-12 w-12 inline-flex items-center justify-center rounded-md text-gray-500 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-100 hover:bg-gray-100 dark:hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-inset focus:ring-indigo-500">
                    <span class="sr-only">Open sidebar</span>
                    <i class="fas fa-bars text-xl"></i>
                </button>
                
                <!-- Title and breadcrumbs -->
                <div class="ml-4 lg:ml-0">
                    @if(!empty($breadcrumbs))
                        <nav class="flex" aria-label="Breadcrumb">
                            <ol class="flex items-center space-x-4">
                                @foreach($breadcrumbs as $index => $crumb)
                                    <li class="flex">
                                        @if($index > 0)
                                            <i class="fas fa-chevron-right text-gray-400 mx-2"></i>
                                        @endif
                                        @if(isset($crumb['url']))
                                            <a href="{{ $crumb['url'] }}" class="text-sm font-medium text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-300">
                                                {{ $crumb['name'] }}
                                            </a>
                                        @else
                                            <span class="text-sm font-medium text-gray-900 dark:text-gray-100">
                                                {{ $crumb['name'] }}
                                            </span>
                                        @endif
                                    </li>
                                @endforeach
                            </ol>
                        </nav>
                    @endif
                    
                    <div class="mt-1">
                        <h1 class="text-2xl font-bold text-gray-900 dark:text-gray-100">
                            {{ $title }}
                        </h1>
                        @if($subtitle)
                            <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                                {{ $subtitle }}
                            </p>
                        @endif
                    </div>
                </div>
            </div>
            
            <!-- Right side -->
            <div class="flex items-center space-x-4">
                <!-- Search (desktop) -->
                <div class="hidden md:block">
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <i class="fas fa-search text-gray-400"></i>
                        </div>
                        <input type="text" 
                               placeholder="Search..." 
                               class="block w-full pl-10 pr-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md leading-5 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-300 placeholder-gray-500 focus:outline-none focus:placeholder-gray-400 focus:ring-1 focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                    </div>
                </div>
                
                <!-- Notifications -->
                <button type="button" class="relative p-2 text-gray-400 hover:text-gray-500 dark:hover:text-gray-300 focus:outline-none focus:ring-2 focus:ring-inset focus:ring-indigo-500">
                    <span class="sr-only">View notifications</span>
                    <i class="fas fa-bell text-xl"></i>
                    <span class="absolute top-0 right-0 block h-2 w-2 rounded-full bg-red-400 ring-2 ring-white dark:ring-gray-800"></span>
                </button>
                
                <!-- User menu -->
                <div class="relative" x-data="{ open: false }">
                    <button type="button" 
                            @click="open = !open"
                            class="flex items-center text-sm rounded-full focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                        <span class="sr-only">Open user menu</span>
                        <div class="w-8 h-8 bg-gradient-to-r from-medical-500 to-medical-600 rounded-full flex items-center justify-center">
                            <i class="fas fa-user text-white text-sm"></i>
                        </div>
                    </button>
                    
                    <!-- Dropdown menu -->
                    <div x-show="open" 
                         @click.away="open = false"
                         x-transition:enter="transition ease-out duration-100"
                         x-transition:enter-start="transform opacity-0 scale-95"
                         x-transition:enter-end="transform opacity-100 scale-100"
                         x-transition:leave="transition ease-in duration-75"
                         x-transition:leave-start="transform opacity-100 scale-100"
                         x-transition:leave-end="transform opacity-0 scale-95"
                         class="origin-top-right absolute right-0 mt-2 w-48 rounded-md shadow-lg py-1 bg-white dark:bg-gray-700 ring-1 ring-black ring-opacity-5 focus:outline-none z-50">
                        <a href="{{ route('profile.edit') }}" class="block px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-600">
                            <i class="fas fa-user mr-2"></i>
                            Your Profile
                        </a>
                        <a href="#" class="block px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-600">
                            <i class="fas fa-cog mr-2"></i>
                            Settings
                        </a>
                        <div class="border-t border-gray-100 dark:border-gray-600"></div>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="block w-full text-left px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-600">
                                <i class="fas fa-sign-out-alt mr-2"></i>
                                Sign out
                            </button>
                        </form>
                    </div>
                </div>
                
                <!-- Actions slot -->
                @if($actions)
                    <div class="flex items-center space-x-2">
                        {{ $actions }}
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
