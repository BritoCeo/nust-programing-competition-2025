@props([
    'type' => 'info',
    'dismissible' => false,
    'icon' => true
])

@php
    $baseClasses = 'rounded-xl p-4 border transition-all duration-300';
    
    $types = [
        'success' => [
            'bg' => 'bg-green-50 dark:bg-green-900/20',
            'border' => 'border-green-200 dark:border-green-800',
            'text' => 'text-green-800 dark:text-green-200',
            'icon' => 'fas fa-check-circle text-green-500',
            'title' => 'Success'
        ],
        'error' => [
            'bg' => 'bg-red-50 dark:bg-red-900/20',
            'border' => 'border-red-200 dark:border-red-800',
            'text' => 'text-red-800 dark:text-red-200',
            'icon' => 'fas fa-exclamation-circle text-red-500',
            'title' => 'Error'
        ],
        'warning' => [
            'bg' => 'bg-yellow-50 dark:bg-yellow-900/20',
            'border' => 'border-yellow-200 dark:border-yellow-800',
            'text' => 'text-yellow-800 dark:text-yellow-200',
            'icon' => 'fas fa-exclamation-triangle text-yellow-500',
            'title' => 'Warning'
        ],
        'info' => [
            'bg' => 'bg-blue-50 dark:bg-blue-900/20',
            'border' => 'border-blue-200 dark:border-blue-800',
            'text' => 'text-blue-800 dark:text-blue-200',
            'icon' => 'fas fa-info-circle text-blue-500',
            'title' => 'Information'
        ]
    ];
    
    $config = $types[$type];
    $classes = $baseClasses . ' ' . $config['bg'] . ' ' . $config['border'] . ' ' . $config['text'];
@endphp

<div {{ $attributes->merge(['class' => $classes]) }}>
    <div class="flex items-start">
        @if($icon)
            <div class="flex-shrink-0">
                <i class="{{ $config['icon'] }} text-lg"></i>
            </div>
        @endif
        
        <div class="ml-3 flex-1">
            @if(isset($title))
                <h3 class="text-sm font-medium mb-1">
                    {{ $title }}
                </h3>
            @endif
            
            <div class="text-sm">
                {{ $slot }}
            </div>
        </div>
        
        @if($dismissible)
            <div class="ml-auto pl-3">
                <button type="button" 
                        onclick="this.parentElement.parentElement.parentElement.remove()"
                        class="inline-flex text-gray-400 hover:text-gray-600 dark:hover:text-gray-300 transition-colors">
                    <i class="fas fa-times"></i>
                </button>
            </div>
        @endif
    </div>
</div>
