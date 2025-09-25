@props([
    'method' => 'POST',
    'action' => '',
    'title' => 'Medical Form',
    'submitText' => 'Save',
    'cancelUrl' => null
])

<form method="{{ $method === 'PUT' || $method === 'PATCH' ? 'POST' : $method }}" 
      action="{{ $action }}"
      enctype="multipart/form-data"
      class="space-y-6">
      
    @if($method === 'PUT' || $method === 'PATCH')
        @method($method)
    @endif
    
    @csrf
    
    <div class="bg-white dark:bg-gray-800 shadow-sm rounded-lg">
        <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
            <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">
                <i class="fas fa-file-medical mr-2"></i>
                {{ $title }}
            </h3>
        </div>
        
        <div class="px-6 py-4 space-y-6">
            {{ $slot }}
        </div>
        
        <div class="px-6 py-4 bg-gray-50 dark:bg-gray-700 border-t border-gray-200 dark:border-gray-600 flex justify-end space-x-3">
            @if($cancelUrl)
                <a href="{{ $cancelUrl }}" 
                   class="inline-flex items-center px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm text-sm font-medium text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-800 hover:bg-gray-50 dark:hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                    <i class="fas fa-times mr-2"></i>
                    Cancel
                </a>
            @endif
            
            <button type="submit" 
                    class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-gradient-to-r from-medical-500 to-medical-600 hover:from-medical-600 hover:to-medical-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-medical-500 transition-all duration-200">
                <i class="fas fa-save mr-2"></i>
                {{ $submitText }}
            </button>
        </div>
    </div>
</form>
