@props([
    'title' => '',
    'subtitle' => '',
    'actions' => null,
    'breadcrumbs' => [],
    'currentRoute' => null
])

<div class="h-screen flex overflow-hidden bg-gray-100 dark:bg-gray-900">
    <!-- Sidebar -->
    <x-ui.sidebar :user="auth()->user()" :current-route="$currentRoute" />
    
    <!-- Mobile sidebar -->
    <x-ui.mobile-nav :user="auth()->user()" />
    
    <!-- Main content -->
    <div class="flex flex-col w-0 flex-1 overflow-hidden">
        <!-- Header -->
        <x-ui.header 
            :title="$title" 
            :subtitle="$subtitle" 
            :actions="$actions"
            :breadcrumbs="$breadcrumbs"
        />
        
        <!-- Page content -->
        <main class="flex-1 relative overflow-y-auto focus:outline-none">
            <div class="py-6">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    {{ $slot }}
                </div>
            </div>
        </main>
    </div>
</div>

@push('scripts')
<script src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
@endpush
