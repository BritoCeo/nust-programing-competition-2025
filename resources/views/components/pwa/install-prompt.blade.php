@props([
    'show' => false
])

<div x-data="pwaInstall()" x-show="showInstallPrompt" class="fixed bottom-4 right-4 z-50" x-transition>
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg border border-gray-200 dark:border-gray-700 p-4 max-w-sm">
        <div class="flex items-center space-x-3">
            <div class="flex-shrink-0">
                <div class="w-10 h-10 bg-gradient-to-r from-medical-500 to-medical-600 rounded-lg flex items-center justify-center">
                    <i class="fas fa-download text-white"></i>
                </div>
            </div>
            <div class="flex-1">
                <h3 class="text-sm font-medium text-gray-900 dark:text-gray-100">
                    Install MESMTF
                </h3>
                <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                    Install this app on your device for quick access
                </p>
            </div>
            <button @click="dismissPrompt" class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300">
                <i class="fas fa-times"></i>
            </button>
        </div>
        
        <div class="mt-3 flex space-x-2">
            <button @click="installApp" 
                    class="flex-1 bg-gradient-to-r from-medical-500 to-medical-600 text-white text-xs font-medium py-2 px-3 rounded-md hover:from-medical-600 hover:to-medical-700 focus:outline-none focus:ring-2 focus:ring-medical-500 focus:ring-offset-2 transition-all duration-200">
                <i class="fas fa-download mr-1"></i>
                Install
            </button>
            <button @click="dismissPrompt" 
                    class="flex-1 bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 text-xs font-medium py-2 px-3 rounded-md hover:bg-gray-200 dark:hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition-all duration-200">
                Later
            </button>
        </div>
    </div>
</div>

<script>
function pwaInstall() {
    return {
        showInstallPrompt: false,
        deferredPrompt: null,
        
        init() {
            // Check if app is already installed
            if (window.matchMedia('(display-mode: standalone)').matches) {
                return;
            }
            
            // Listen for the beforeinstallprompt event
            window.addEventListener('beforeinstallprompt', (e) => {
                e.preventDefault();
                this.deferredPrompt = e;
                this.showInstallPrompt = true;
            });
            
            // Listen for the appinstalled event
            window.addEventListener('appinstalled', () => {
                this.showInstallPrompt = false;
                this.deferredPrompt = null;
                this.showSuccessMessage();
            });
            
            // Check if user has previously dismissed the prompt
            if (!localStorage.getItem('pwa-install-dismissed')) {
                this.showInstallPrompt = true;
            }
        },
        
        async installApp() {
            if (!this.deferredPrompt) {
                return;
            }
            
            this.deferredPrompt.prompt();
            const { outcome } = await this.deferredPrompt.userChoice;
            
            if (outcome === 'accepted') {
                console.log('User accepted the install prompt');
            } else {
                console.log('User dismissed the install prompt');
            }
            
            this.deferredPrompt = null;
            this.showInstallPrompt = false;
        },
        
        dismissPrompt() {
            this.showInstallPrompt = false;
            localStorage.setItem('pwa-install-dismissed', 'true');
        },
        
        showSuccessMessage() {
            // Show success message
            this.$dispatch('show-notification', {
                type: 'success',
                message: 'MESMTF has been installed successfully!'
            });
        }
    }
}
</script>
