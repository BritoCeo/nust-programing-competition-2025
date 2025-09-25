@props([
    'id' => null,
    'size' => 'md',
    'backdrop' => true,
    'closeOnBackdrop' => true
])

@php
    $sizes = [
        'sm' => 'max-w-md',
        'md' => 'max-w-lg',
        'lg' => 'max-w-2xl',
        'xl' => 'max-w-4xl',
        'full' => 'max-w-full mx-4'
    ];
    
    $modalId = $id ?? 'modal-' . uniqid();
@endphp

<!-- Modal Backdrop -->
<div id="{{ $modalId }}-backdrop" 
     class="fixed inset-0 bg-gray-600 bg-opacity-50 dark:bg-gray-900 dark:bg-opacity-75 z-50 hidden transition-opacity duration-300"
     @if($closeOnBackdrop) onclick="closeModal('{{ $modalId }}')" @endif>
</div>

<!-- Modal Container -->
<div id="{{ $modalId }}" 
     class="fixed inset-0 z-50 hidden overflow-y-auto"
     aria-labelledby="modal-title" 
     role="dialog" 
     aria-modal="true">
    <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
        <!-- Modal Content -->
        <div class="inline-block align-bottom bg-white dark:bg-gray-800 rounded-2xl text-left overflow-hidden shadow-xl transform transition-all duration-300 sm:my-8 sm:align-middle {{ $sizes[$size] }} w-full">
            {{ $slot }}
        </div>
    </div>
</div>

<script>
    function openModal(modalId) {
        const modal = document.getElementById(modalId);
        const backdrop = document.getElementById(modalId + '-backdrop');
        
        if (modal && backdrop) {
            modal.classList.remove('hidden');
            backdrop.classList.remove('hidden');
            document.body.classList.add('overflow-hidden');
            
            // Add animation
            setTimeout(() => {
                modal.classList.add('opacity-100');
                backdrop.classList.add('opacity-100');
            }, 10);
        }
    }
    
    function closeModal(modalId) {
        const modal = document.getElementById(modalId);
        const backdrop = document.getElementById(modalId + '-backdrop');
        
        if (modal && backdrop) {
            modal.classList.add('opacity-0');
            backdrop.classList.add('opacity-0');
            
            setTimeout(() => {
                modal.classList.add('hidden');
                backdrop.classList.add('hidden');
                document.body.classList.remove('overflow-hidden');
            }, 300);
        }
    }
    
    // Close modal on Escape key
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            const openModals = document.querySelectorAll('[id^="modal-"]:not(.hidden)');
            openModals.forEach(modal => {
                const modalId = modal.id;
                closeModal(modalId);
            });
        }
    });
</script>