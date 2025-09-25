<!-- Chatbot Widget Component -->
<div id="chatbot-widget" class="fixed bottom-6 right-6 z-50">
    <!-- Chatbot Toggle Button -->
    <button onclick="toggleChatbot()" 
            class="w-14 h-14 bg-gradient-to-r from-blue-500 to-cyan-500 rounded-full shadow-lg hover:shadow-xl transition-all duration-300 hover:scale-110 flex items-center justify-center group">
        <i id="chatbot-icon" class="fas fa-comments text-white text-xl group-hover:scale-110 transition-transform duration-300"></i>
    </button>

    <!-- Chatbot Panel -->
    <div id="chatbot-panel" class="hidden absolute bottom-16 right-0 w-80 h-96 bg-white dark:bg-gray-800 rounded-2xl shadow-2xl border border-gray-200 dark:border-gray-700 overflow-hidden">
        <!-- Chatbot Header -->
        <div class="bg-gradient-to-r from-blue-500 to-cyan-500 p-4 text-white">
            <div class="flex items-center justify-between">
                <div class="flex items-center space-x-3">
                    <div class="w-8 h-8 bg-white/20 rounded-full flex items-center justify-center">
                        <i class="fas fa-robot text-white"></i>
                    </div>
                    <div>
                        <h3 class="font-semibold">MESMTF Assistant</h3>
                        <p class="text-xs text-blue-100">AI Medical Assistant</p>
                    </div>
                </div>
                <button onclick="toggleChatbot()" class="text-white/80 hover:text-white transition-colors">
                    <i class="fas fa-times"></i>
                </button>
            </div>
        </div>

        <!-- Chatbot Messages -->
        <div id="chatbot-messages" class="h-64 overflow-y-auto p-4 space-y-3">
            <div class="flex items-start space-x-2">
                <div class="w-6 h-6 bg-blue-500 rounded-full flex items-center justify-center flex-shrink-0">
                    <i class="fas fa-robot text-white text-xs"></i>
                </div>
                <div class="bg-gray-100 dark:bg-gray-700 rounded-lg p-3 max-w-xs">
                    <p class="text-sm text-gray-800 dark:text-gray-200">
                        Hello! I'm your AI medical assistant. How can I help you today?
                    </p>
                </div>
            </div>
        </div>

        <!-- Chatbot Input -->
        <div class="p-4 border-t border-gray-200 dark:border-gray-700">
            <div class="flex space-x-2">
                <input type="text" 
                       id="chatbot-input" 
                       placeholder="Type your message..." 
                       class="flex-1 px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white placeholder-gray-500 dark:placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent text-sm">
                <button onclick="sendChatbotMessage()" 
                        class="px-4 py-2 bg-blue-500 text-white rounded-lg hover:bg-blue-600 transition-colors">
                    <i class="fas fa-paper-plane"></i>
                </button>
            </div>
        </div>
    </div>
</div>

<script>
    function toggleChatbot() {
        const panel = document.getElementById('chatbot-panel');
        const icon = document.getElementById('chatbot-icon');
        
        if (panel.classList.contains('hidden')) {
            panel.classList.remove('hidden');
            icon.classList.remove('fa-comments');
            icon.classList.add('fa-times');
        } else {
            panel.classList.add('hidden');
            icon.classList.remove('fa-times');
            icon.classList.add('fa-comments');
        }
    }

    function sendChatbotMessage() {
        const input = document.getElementById('chatbot-input');
        const messages = document.getElementById('chatbot-messages');
        const message = input.value.trim();
        
        if (message) {
            // Add user message
            const userMessage = document.createElement('div');
            userMessage.className = 'flex items-start space-x-2 justify-end';
            userMessage.innerHTML = `
                <div class="bg-blue-500 text-white rounded-lg p-3 max-w-xs">
                    <p class="text-sm">${message}</p>
                </div>
                <div class="w-6 h-6 bg-gray-400 rounded-full flex items-center justify-center flex-shrink-0">
                    <i class="fas fa-user text-white text-xs"></i>
                </div>
            `;
            messages.appendChild(userMessage);
            
            // Clear input
            input.value = '';
            
            // Scroll to bottom
            messages.scrollTop = messages.scrollHeight;
            
            // Simulate AI response
            setTimeout(() => {
                const aiMessage = document.createElement('div');
                aiMessage.className = 'flex items-start space-x-2';
                aiMessage.innerHTML = `
                    <div class="w-6 h-6 bg-blue-500 rounded-full flex items-center justify-center flex-shrink-0">
                        <i class="fas fa-robot text-white text-xs"></i>
                    </div>
                    <div class="bg-gray-100 dark:bg-gray-700 rounded-lg p-3 max-w-xs">
                        <p class="text-sm text-gray-800 dark:text-gray-200">
                            I understand you're asking about "${message}". As an AI medical assistant, I can help with general health information, but please consult with a healthcare professional for medical advice.
                        </p>
                    </div>
                `;
                messages.appendChild(aiMessage);
                messages.scrollTop = messages.scrollHeight;
            }, 1000);
        }
    }

    // Send message on Enter key
    document.getElementById('chatbot-input').addEventListener('keypress', function(e) {
        if (e.key === 'Enter') {
            sendChatbotMessage();
        }
    });
</script>
