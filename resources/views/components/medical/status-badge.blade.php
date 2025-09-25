@props([
    'status' => 'active',
    'size' => 'sm',
    'showIcon' => true
])

@php
$statusConfig = [
    'active' => [
        'class' => 'bg-green-100 text-green-800 dark:bg-green-900/20 dark:text-green-300',
        'icon' => 'fas fa-check-circle',
        'label' => 'Active'
    ],
    'inactive' => [
        'class' => 'bg-gray-100 text-gray-800 dark:bg-gray-900/20 dark:text-gray-300',
        'icon' => 'fas fa-pause-circle',
        'label' => 'Inactive'
    ],
    'pending' => [
        'class' => 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900/20 dark:text-yellow-300',
        'icon' => 'fas fa-clock',
        'label' => 'Pending'
    ],
    'completed' => [
        'class' => 'bg-blue-100 text-blue-800 dark:bg-blue-900/20 dark:text-blue-300',
        'icon' => 'fas fa-check-double',
        'label' => 'Completed'
    ],
    'cancelled' => [
        'class' => 'bg-red-100 text-red-800 dark:bg-red-900/20 dark:text-red-300',
        'icon' => 'fas fa-times-circle',
        'label' => 'Cancelled'
    ],
    'scheduled' => [
        'class' => 'bg-blue-100 text-blue-800 dark:bg-blue-900/20 dark:text-blue-300',
        'icon' => 'fas fa-calendar',
        'label' => 'Scheduled'
    ],
    'confirmed' => [
        'class' => 'bg-green-100 text-green-800 dark:bg-green-900/20 dark:text-green-300',
        'icon' => 'fas fa-check',
        'label' => 'Confirmed'
    ],
    'in_progress' => [
        'class' => 'bg-purple-100 text-purple-800 dark:bg-purple-900/20 dark:text-purple-300',
        'icon' => 'fas fa-spinner',
        'label' => 'In Progress'
    ],
    'no_show' => [
        'class' => 'bg-orange-100 text-orange-800 dark:bg-orange-900/20 dark:text-orange-300',
        'icon' => 'fas fa-user-times',
        'label' => 'No Show'
    ],
    'tentative' => [
        'class' => 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900/20 dark:text-yellow-300',
        'icon' => 'fas fa-question-circle',
        'label' => 'Tentative'
    ],
    'ruled_out' => [
        'class' => 'bg-red-100 text-red-800 dark:bg-red-900/20 dark:text-red-300',
        'icon' => 'fas fa-ban',
        'label' => 'Ruled Out'
    ],
    'follow_up' => [
        'class' => 'bg-indigo-100 text-indigo-800 dark:bg-indigo-900/20 dark:text-indigo-300',
        'icon' => 'fas fa-arrow-right',
        'label' => 'Follow Up'
    ],
    'prescribed' => [
        'class' => 'bg-blue-100 text-blue-800 dark:bg-blue-900/20 dark:text-blue-300',
        'icon' => 'fas fa-prescription-bottle',
        'label' => 'Prescribed'
    ],
    'dispensed' => [
        'class' => 'bg-green-100 text-green-800 dark:bg-green-900/20 dark:text-green-300',
        'icon' => 'fas fa-pills',
        'label' => 'Dispensed'
    ],
    'discontinued' => [
        'class' => 'bg-red-100 text-red-800 dark:bg-red-900/20 dark:text-red-300',
        'icon' => 'fas fa-stop',
        'label' => 'Discontinued'
    ]
];

$sizeClasses = [
    'xs' => 'px-2 py-1 text-xs',
    'sm' => 'px-2.5 py-0.5 text-xs',
    'md' => 'px-3 py-1 text-sm',
    'lg' => 'px-4 py-2 text-base'
];

$config = $statusConfig[$status] ?? $statusConfig['active'];
@endphp

<span {{ $attributes->merge([
    'class' => 'inline-flex items-center font-medium rounded-full ' . 
               $sizeClasses[$size] . ' ' . 
               $config['class']
]) }}>
    @if($showIcon && isset($config['icon']))
        <i class="{{ $config['icon'] }} mr-1.5"></i>
    @endif
    {{ $config['label'] }}
</span>
