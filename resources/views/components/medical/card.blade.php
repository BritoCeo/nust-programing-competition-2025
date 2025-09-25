@props([
    'title' => '',
    'subtitle' => '',
    'icon' => '',
    'color' => 'medical',
    'size' => 'md',
    'clickable' => false,
    'href' => null,
    'badge' => null,
    'badgeColor' => 'blue'
])

@php
$sizeClasses = [
    'sm' => 'p-4',
    'md' => 'p-6',
    'lg' => 'p-8'
];

$colorClasses = [
    'medical' => 'bg-white dark:bg-gray-800 border-gray-200 dark:border-gray-700',
    'health' => 'bg-health-50 dark:bg-health-900/20 border-health-200 dark:border-health-800',
    'emergency' => 'bg-emergency-50 dark:bg-emergency-900/20 border-emergency-200 dark:border-emergency-800',
    'diagnosis' => 'bg-diagnosis-50 dark:bg-diagnosis-900/20 border-diagnosis-200 dark:border-diagnosis-800'
];

$badgeColorClasses = [
    'blue' => 'bg-blue-100 text-blue-800 dark:bg-blue-900/20 dark:text-blue-300',
    'green' => 'bg-green-100 text-green-800 dark:bg-green-900/20 dark:text-green-300',
    'red' => 'bg-red-100 text-red-800 dark:bg-red-900/20 dark:text-red-300',
    'yellow' => 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900/20 dark:text-yellow-300',
    'purple' => 'bg-purple-100 text-purple-800 dark:bg-purple-900/20 dark:text-purple-300'
];
@endphp

<div {{ $attributes->merge([
    'class' => 'rounded-lg border shadow-sm transition-all duration-200 hover:shadow-md ' . 
               $colorClasses[$color] . ' ' . 
               ($clickable ? 'cursor-pointer hover:scale-105' : '')
]) }}>
    @if($clickable && $href)
        <a href="{{ $href }}" class="block">
    @endif
    
    <div class="{{ $sizeClasses[$size] }}">
        @if($icon || $badge)
            <div class="flex items-center justify-between mb-4">
                @if($icon)
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <i class="{{ $icon }} text-2xl text-{{ $color }}-600 dark:text-{{ $color }}-400"></i>
                        </div>
                    </div>
                @endif
                
                @if($badge)
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $badgeColorClasses[$badgeColor] }}">
                        {{ $badge }}
                    </span>
                @endif
            </div>
        @endif
        
        @if($title)
            <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-2">
                {{ $title }}
            </h3>
        @endif
        
        @if($subtitle)
            <p class="text-sm text-gray-600 dark:text-gray-400 mb-4">
                {{ $subtitle }}
            </p>
        @endif
        
        {{ $slot }}
    </div>
    
    @if($clickable && $href)
        </a>
    @endif
</div>
