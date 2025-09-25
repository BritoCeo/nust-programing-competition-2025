@props([
    'label' => '',
    'type' => 'text',
    'required' => false,
    'error' => null,
    'help' => null,
    'icon' => null,
    'size' => 'md',
    'variant' => 'default'
])

@php
$sizeClasses = [
    'sm' => 'text-sm px-3 py-2',
    'md' => 'text-sm px-3 py-2',
    'lg' => 'text-base px-4 py-3'
];

$variantClasses = [
    'default' => 'border-gray-300 dark:border-gray-600 focus:border-indigo-500 focus:ring-indigo-500',
    'medical' => 'border-medical-300 dark:border-medical-600 focus:border-medical-500 focus:ring-medical-500',
    'health' => 'border-health-300 dark:border-health-600 focus:border-health-500 focus:ring-health-500',
    'emergency' => 'border-emergency-300 dark:border-emergency-600 focus:border-emergency-500 focus:ring-emergency-500'
];

$inputClasses = 'block w-full rounded-md shadow-sm transition-colors duration-200 ' . 
                $sizeClasses[$size] . ' ' . 
                $variantClasses[$variant] . ' ' .
                'dark:bg-gray-700 dark:text-gray-300 ' .
                ($error ? 'border-red-300 focus:border-red-500 focus:ring-red-500' : '');
@endphp

<div class="space-y-1">
    @if($label)
        <label {{ $attributes->merge(['class' => 'block text-sm font-medium text-gray-700 dark:text-gray-300']) }}>
            {{ $label }}
            @if($required)
                <span class="text-red-500 ml-1">*</span>
            @endif
        </label>
    @endif
    
    <div class="relative">
        @if($icon)
            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                <i class="{{ $icon }} text-gray-400"></i>
            </div>
            <input {{ $attributes->merge([
                'type' => $type,
                'class' => $inputClasses . ' pl-10'
            ]) }} />
        @else
            <input {{ $attributes->merge([
                'type' => $type,
                'class' => $inputClasses
            ]) }} />
        @endif
    </div>
    
    @if($error)
        <p class="text-sm text-red-600 dark:text-red-400 flex items-center">
            <i class="fas fa-exclamation-circle mr-1"></i>
            {{ $error }}
        </p>
    @elseif($help)
        <p class="text-sm text-gray-500 dark:text-gray-400">
            {{ $help }}
        </p>
    @endif
</div>
