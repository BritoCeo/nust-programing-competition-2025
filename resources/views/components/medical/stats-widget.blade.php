@props([
    'title' => '',
    'value' => 0,
    'change' => 0,
    'changeType' => 'positive', // positive, negative, neutral
    'icon' => 'fas fa-chart-line',
    'color' => 'blue',
    'trend' => null,
    'description' => ''
])

@php
$colorClasses = [
    'blue' => 'bg-blue-500 text-white',
    'green' => 'bg-green-500 text-white',
    'red' => 'bg-red-500 text-white',
    'yellow' => 'bg-yellow-500 text-white',
    'purple' => 'bg-purple-500 text-white',
    'indigo' => 'bg-indigo-500 text-white',
    'pink' => 'bg-pink-500 text-white',
    'gray' => 'bg-gray-500 text-white'
];

$changeClasses = [
    'positive' => 'text-green-600 dark:text-green-400',
    'negative' => 'text-red-600 dark:text-red-400',
    'neutral' => 'text-gray-600 dark:text-gray-400'
];

$changeIcons = [
    'positive' => 'fas fa-arrow-up',
    'negative' => 'fas fa-arrow-down',
    'neutral' => 'fas fa-minus'
];
@endphp

<div {{ $attributes->merge(['class' => 'bg-white dark:bg-gray-800 overflow-hidden shadow-sm rounded-lg']) }}>
    <div class="p-5">
        <div class="flex items-center">
            <div class="flex-shrink-0">
                <div class="w-8 h-8 {{ $colorClasses[$color] }} rounded-md flex items-center justify-center">
                    <i class="{{ $icon }} text-sm"></i>
                </div>
            </div>
            <div class="ml-5 w-0 flex-1">
                <dl>
                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400 truncate">
                        {{ $title }}
                    </dt>
                    <dd class="flex items-baseline">
                        <div class="text-2xl font-semibold text-gray-900 dark:text-gray-100">
                            {{ number_format($value) }}
                        </div>
                        @if($change !== 0)
                            <div class="ml-2 flex items-baseline text-sm font-semibold {{ $changeClasses[$changeType] }}">
                                <i class="{{ $changeIcons[$changeType] }} mr-1"></i>
                                {{ abs($change) }}%
                            </div>
                        @endif
                    </dd>
                    @if($description)
                        <dd class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                            {{ $description }}
                        </dd>
                    @endif
                </dl>
            </div>
        </div>
    </div>
    
    @if($trend)
        <div class="bg-gray-50 dark:bg-gray-700 px-5 py-3">
            <div class="text-sm">
                <span class="text-gray-500 dark:text-gray-400">Trend:</span>
                <span class="font-medium text-gray-900 dark:text-gray-100 ml-1">{{ $trend }}</span>
            </div>
        </div>
    @endif
</div>
