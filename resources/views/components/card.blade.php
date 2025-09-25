@props([
    'variant' => 'default',
    'padding' => 'p-6',
    'shadow' => 'shadow-lg',
    'hover' => false
])

@php
    $baseClasses = 'bg-white dark:bg-gray-800 rounded-2xl border border-gray-200 dark:border-gray-700 transition-all duration-300';
    
    $variants = [
        'default' => '',
        'gradient' => 'bg-gradient-to-br from-blue-50 to-cyan-50 dark:from-gray-800 dark:to-gray-700',
        'bordered' => 'border-2 border-blue-200 dark:border-blue-700',
        'elevated' => 'shadow-2xl'
    ];
    
    $hoverClasses = $hover ? 'hover:shadow-xl hover:scale-105' : '';
    
    $classes = $baseClasses . ' ' . $variants[$variant] . ' ' . $shadow . ' ' . $hoverClasses . ' ' . $padding;
@endphp

<div {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</div>
