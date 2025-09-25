<?php

namespace App\Http\Controllers;

use App\Services\ChatbotService;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class ChatbotController extends Controller
{
    private $chatbotService;

    public function __construct(ChatbotService $chatbotService)
    {
        $this->chatbotService = $chatbotService;
    }

    /**
     * Process chatbot message
     */
    public function processMessage(Request $request): JsonResponse
    {
        $request->validate([
            'message' => 'required|string|max:1000',
            'user_id' => 'nullable|integer|exists:users,id'
        ]);

        try {
            $response = $this->chatbotService->processMessage(
                $request->input('message'),
                $request->input('user_id')
            );

            return response()->json([
                'success' => true,
                'data' => $response,
                'timestamp' => now()
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to process message',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get chatbot suggestions
     */
    public function getSuggestions(): JsonResponse
    {
        $suggestions = [
            'symptom_check' => [
                'I have fever and headache',
                'I feel nauseous and tired',
                'I have chest pain and difficulty breathing',
                'I have a rash on my skin'
            ],
            'appointment_booking' => [
                'I need to book an appointment',
                'Can I schedule a consultation?',
                'I want to see a doctor',
                'I need a medical checkup'
            ],
            'drug_information' => [
                'Tell me about malaria drugs',
                'What are the side effects of antibiotics?',
                'How should I take this medication?',
                'Are there any drug interactions?'
            ],
            'general_advice' => [
                'How can I stay healthy?',
                'What should I do for a cold?',
                'How to prevent diseases?',
                'What are healthy lifestyle tips?'
            ]
        ];

        return response()->json([
            'success' => true,
            'data' => $suggestions
        ]);
    }

    /**
     * Get chatbot capabilities
     */
    public function getCapabilities(): JsonResponse
    {
        $capabilities = [
            'symptom_analysis' => [
                'name' => 'Symptom Analysis',
                'description' => 'Analyze your symptoms and provide preliminary diagnosis',
                'icon' => 'fas fa-stethoscope'
            ],
            'appointment_booking' => [
                'name' => 'Appointment Booking',
                'description' => 'Help you schedule medical appointments',
                'icon' => 'fas fa-calendar-plus'
            ],
            'drug_information' => [
                'name' => 'Drug Information',
                'description' => 'Provide detailed information about medications',
                'icon' => 'fas fa-pills'
            ],
            'disease_information' => [
                'name' => 'Disease Information',
                'description' => 'Explain diseases, symptoms, and treatments',
                'icon' => 'fas fa-book-medical'
            ],
            'emergency_guidance' => [
                'name' => 'Emergency Guidance',
                'description' => 'Provide emergency medical guidance',
                'icon' => 'fas fa-exclamation-triangle'
            ],
            'general_advice' => [
                'name' => 'General Medical Advice',
                'description' => 'Provide general health and wellness advice',
                'icon' => 'fas fa-heart'
            ]
        ];

        return response()->json([
            'success' => true,
            'data' => $capabilities
        ]);
    }

    /**
     * Get chatbot history for user
     */
    public function getHistory(Request $request): JsonResponse
    {
        $userId = $request->input('user_id');
        
        // This would typically fetch from a chatbot_conversations table
        // For now, return empty history
        $history = [];

        return response()->json([
            'success' => true,
            'data' => $history
        ]);
    }

    /**
     * Clear chatbot history for user
     */
    public function clearHistory(Request $request): JsonResponse
    {
        $userId = $request->input('user_id');
        
        // This would typically clear from a chatbot_conversations table
        // For now, return success

        return response()->json([
            'success' => true,
            'message' => 'Chat history cleared successfully'
        ]);
    }
}
