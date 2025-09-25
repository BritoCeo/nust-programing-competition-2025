<div id="chatbot-container" class="fixed bottom-4 right-4 z-50">
    <!-- Chatbot Toggle Button -->
    <button id="chatbot-toggle" class="bg-blue-600 hover:bg-blue-700 text-white rounded-full p-4 shadow-lg transition-all duration-300 hover:scale-110">
        <i class="fas fa-robot text-xl"></i>
    </button>

    <!-- Chatbot Window -->
    <div id="chatbot-window" class="hidden bg-white rounded-lg shadow-2xl w-80 h-96 flex flex-col border border-gray-200">
        <!-- Header -->
        <div class="bg-blue-600 text-white p-4 rounded-t-lg flex justify-between items-center">
            <div class="flex items-center">
                <i class="fas fa-robot mr-2"></i>
                <span class="font-semibold">MESMTF Assistant</span>
            </div>
            <button id="chatbot-close" class="text-white hover:text-gray-200">
                <i class="fas fa-times"></i>
            </button>
        </div>

        <!-- Chat Messages -->
        <div id="chatbot-messages" class="flex-1 p-4 overflow-y-auto space-y-3">
            <div class="flex items-start space-x-2">
                <div class="bg-blue-100 rounded-lg p-3 max-w-xs">
                    <p class="text-sm text-gray-800">Hello! I'm your MESMTF assistant. How can I help you today?</p>
                </div>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="p-3 border-t border-gray-200">
            <div class="flex flex-wrap gap-2 mb-3">
                <button class="quick-action-btn bg-gray-100 hover:bg-gray-200 text-gray-700 px-3 py-1 rounded-full text-xs" data-action="symptoms">
                    Check Symptoms
                </button>
                <button class="quick-action-btn bg-gray-100 hover:bg-gray-200 text-gray-700 px-3 py-1 rounded-full text-xs" data-action="appointment">
                    Book Appointment
                </button>
                <button class="quick-action-btn bg-gray-100 hover:bg-gray-200 text-gray-700 px-3 py-1 rounded-full text-xs" data-action="drugs">
                    Drug Information
                </button>
                <button class="quick-action-btn bg-gray-100 hover:bg-gray-200 text-gray-700 px-3 py-1 rounded-full text-xs" data-action="help">
                    Help
                </button>
            </div>

            <!-- Input Form -->
            <form id="chatbot-form" class="flex space-x-2">
                <input type="text" id="chatbot-input" placeholder="Type your message..." 
                       class="flex-1 border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition-colors">
                    <i class="fas fa-paper-plane"></i>
                </button>
            </form>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const chatbotToggle = document.getElementById('chatbot-toggle');
    const chatbotWindow = document.getElementById('chatbot-window');
    const chatbotClose = document.getElementById('chatbot-close');
    const chatbotForm = document.getElementById('chatbot-form');
    const chatbotInput = document.getElementById('chatbot-input');
    const chatbotMessages = document.getElementById('chatbot-messages');
    const quickActionBtns = document.querySelectorAll('.quick-action-btn');

    let isOpen = false;

    // Toggle chatbot
    chatbotToggle.addEventListener('click', function() {
        isOpen = !isOpen;
        chatbotWindow.classList.toggle('hidden');
        chatbotToggle.innerHTML = isOpen ? '<i class="fas fa-times text-xl"></i>' : '<i class="fas fa-robot text-xl"></i>';
    });

    // Close chatbot
    chatbotClose.addEventListener('click', function() {
        isOpen = false;
        chatbotWindow.classList.add('hidden');
        chatbotToggle.innerHTML = '<i class="fas fa-robot text-xl"></i>';
    });

    // Send message
    chatbotForm.addEventListener('submit', function(e) {
        e.preventDefault();
        const message = chatbotInput.value.trim();
        if (message) {
            addMessage(message, 'user');
            chatbotInput.value = '';
            handleUserMessage(message);
        }
    });

    // Quick actions
    quickActionBtns.forEach(btn => {
        btn.addEventListener('click', function() {
            const action = this.dataset.action;
            handleQuickAction(action);
        });
    });

    function addMessage(message, sender) {
        const messageDiv = document.createElement('div');
        messageDiv.className = `flex items-start space-x-2 ${sender === 'user' ? 'justify-end' : ''}`;
        
        const messageContent = document.createElement('div');
        messageContent.className = `rounded-lg p-3 max-w-xs ${
            sender === 'user' 
                ? 'bg-blue-600 text-white' 
                : 'bg-gray-100 text-gray-800'
        }`;
        messageContent.innerHTML = `<p class="text-sm">${message}</p>`;
        
        messageDiv.appendChild(messageContent);
        chatbotMessages.appendChild(messageDiv);
        chatbotMessages.scrollTop = chatbotMessages.scrollHeight;
    }

    function handleUserMessage(message) {
        // Simulate AI response
        setTimeout(() => {
            const response = getAIResponse(message);
            addMessage(response, 'bot');
        }, 1000);
    }

    function handleQuickAction(action) {
        const responses = {
            symptoms: "I can help you check symptoms. Please describe what you're experiencing, and I'll guide you through our symptom checker.",
            appointment: "I can help you book an appointment. What type of appointment do you need? (General consultation, specialist, emergency)",
            drugs: "I can provide information about medications. What drug would you like to know about?",
            help: "I'm here to help! I can assist with:\n• Symptom checking\n• Appointment booking\n• Drug information\n• General medical advice\n\nWhat would you like to know?"
        };
        
        addMessage(responses[action], 'bot');
    }

    function getAIResponse(message) {
        const lowerMessage = message.toLowerCase();
        
        // Symptom-related responses
        if (lowerMessage.includes('fever') || lowerMessage.includes('headache')) {
            return "I understand you're experiencing fever and headache. These could be symptoms of malaria or typhoid. I recommend using our symptom checker for a more detailed analysis. Would you like me to guide you through it?";
        }
        
        if (lowerMessage.includes('appointment') || lowerMessage.includes('book')) {
            return "I can help you book an appointment. Please visit our appointment booking page or tell me what type of consultation you need.";
        }
        
        if (lowerMessage.includes('drug') || lowerMessage.includes('medication')) {
            return "I can provide information about medications. Please specify which drug you're asking about, and I'll give you detailed information including dosage, side effects, and interactions.";
        }
        
        if (lowerMessage.includes('malaria') || lowerMessage.includes('typhoid')) {
            return "For malaria and typhoid symptoms, I recommend:\n1. Using our expert system for diagnosis\n2. Booking an appointment with our specialists\n3. Getting proper medical treatment\n\nWould you like me to guide you through any of these steps?";
        }
        
        if (lowerMessage.includes('help') || lowerMessage.includes('what can you do')) {
            return "I'm your MESMTF assistant! I can help you with:\n• Symptom analysis and diagnosis\n• Appointment booking\n• Drug information and interactions\n• General medical advice\n• Navigation assistance\n\nWhat would you like to know?";
        }
        
        // Default response
        return "I understand your concern. For the best medical advice, I recommend:\n1. Using our expert system for symptom analysis\n2. Booking an appointment with our doctors\n3. Consulting our drug database for medication information\n\nHow can I assist you further?";
    }
});
</script>
