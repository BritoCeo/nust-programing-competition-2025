<?php

namespace App\Services;

use App\Models\Symptom;
use App\Models\Disease;
use App\Models\Drug;
use App\Models\ExpertSystemRule;
use App\Models\BlogPost;
use Illuminate\Support\Facades\Log;

class ChatbotService
{
    private $expertSystemService;
    
    public function __construct(ExpertSystemService $expertSystemService)
    {
        $this->expertSystemService = $expertSystemService;
    }

    /**
     * Process chatbot message and generate response
     */
    public function processMessage(string $message, int $userId = null): array
    {
        $message = trim($message);
        $intent = $this->detectIntent($message);
        
        switch ($intent) {
            case 'symptom_check':
                return $this->handleSymptomCheck($message);
            case 'appointment_booking':
                return $this->handleAppointmentBooking($message);
            case 'drug_information':
                return $this->handleDrugInformation($message);
            case 'general_medical_advice':
                return $this->handleGeneralMedicalAdvice($message);
            case 'disease_information':
                return $this->handleDiseaseInformation($message);
            case 'emergency':
                return $this->handleEmergency($message);
            default:
                return $this->handleGeneralQuery($message);
        }
    }

    /**
     * Detect user intent from message
     */
    private function detectIntent(string $message): string
    {
        $message = strtolower($message);
        
        // Emergency keywords
        if (preg_match('/\b(emergency|urgent|critical|severe|help|pain|bleeding|unconscious|difficulty breathing)\b/', $message)) {
            return 'emergency';
        }
        
        // Symptom check keywords
        if (preg_match('/\b(symptom|pain|fever|headache|nausea|vomiting|cough|diarrhea|rash|fatigue|weakness)\b/', $message)) {
            return 'symptom_check';
        }
        
        // Appointment booking keywords
        if (preg_match('/\b(appointment|book|schedule|visit|consultation|meeting)\b/', $message)) {
            return 'appointment_booking';
        }
        
        // Drug information keywords
        if (preg_match('/\b(drug|medicine|medication|pill|tablet|capsule|dosage|side effect|interaction)\b/', $message)) {
            return 'drug_information';
        }
        
        // Disease information keywords
        if (preg_match('/\b(disease|condition|illness|malaria|typhoid|tuberculosis|diabetes|hiv|aids|mental health)\b/', $message)) {
            return 'disease_information';
        }
        
        // General medical advice keywords
        if (preg_match('/\b(advice|help|what should|how to|treatment|care|prevention|health)\b/', $message)) {
            return 'general_medical_advice';
        }
        
        return 'general_query';
    }

    /**
     * Handle symptom check requests
     */
    private function handleSymptomCheck(string $message): array
    {
        $symptoms = $this->extractSymptoms($message);
        
        if (empty($symptoms)) {
            return [
                'type' => 'symptom_check',
                'response' => 'I can help you with symptom analysis. Please describe your symptoms in detail. For example: "I have fever, headache, and fatigue."',
                'suggestions' => [
                    'Describe your main symptoms',
                    'Mention any pain or discomfort',
                    'Include duration of symptoms',
                    'Note any other relevant details'
                ],
                'actions' => [
                    [
                        'type' => 'button',
                        'text' => 'Start Symptom Analysis',
                        'action' => 'start_symptom_analysis'
                    ]
                ]
            ];
        }
        
        // Perform symptom analysis
        $symptomIds = $this->getSymptomIds($symptoms);
        if (!empty($symptomIds)) {
            $analysis = $this->expertSystemService->analyzeMultiDiseaseSymptoms($symptomIds, 1, 1);
            
            return [
                'type' => 'symptom_analysis',
                'response' => 'Based on your symptoms, here\'s my analysis:',
                'analysis' => $analysis,
                'recommendations' => $this->generateSymptomRecommendations($analysis),
                'actions' => [
                    [
                        'type' => 'button',
                        'text' => 'Book Appointment',
                        'action' => 'book_appointment'
                    ],
                    [
                        'type' => 'button',
                        'text' => 'Get More Details',
                        'action' => 'get_detailed_analysis'
                    ]
                ]
            ];
        }
        
        return [
            'type' => 'symptom_check',
            'response' => 'I understand you\'re experiencing symptoms. Could you please provide more specific details about what you\'re feeling?',
            'suggestions' => $this->getSymptomSuggestions()
        ];
    }

    /**
     * Handle appointment booking requests
     */
    private function handleAppointmentBooking(string $message): array
    {
        return [
            'type' => 'appointment_booking',
            'response' => 'I can help you book an appointment. Let me guide you through the process.',
            'steps' => [
                'Select your preferred date and time',
                'Choose the type of consultation',
                'Provide your contact information',
                'Describe the reason for your visit'
            ],
            'actions' => [
                [
                    'type' => 'button',
                    'text' => 'Book Appointment Now',
                    'action' => 'book_appointment'
                ],
                [
                    'type' => 'button',
                    'text' => 'Check Availability',
                    'action' => 'check_availability'
                ]
            ]
        ];
    }

    /**
     * Handle drug information requests
     */
    private function handleDrugInformation(string $message): array
    {
        $drugName = $this->extractDrugName($message);
        
        if ($drugName) {
            $drug = Drug::where('name', 'like', "%{$drugName}%")->first();
            
            if ($drug) {
                return [
                    'type' => 'drug_information',
                    'response' => "Here's information about {$drug->name}:",
                    'drug' => [
                        'name' => $drug->name,
                        'generic_name' => $drug->generic_name,
                        'category' => $drug->category,
                        'dosage_form' => $drug->dosage_form,
                        'strength' => $drug->strength,
                        'indications' => $drug->indications,
                        'side_effects' => $drug->side_effects,
                        'contraindications' => $drug->contraindications,
                        'interactions' => $drug->interactions
                    ],
                    'actions' => [
                        [
                            'type' => 'button',
                            'text' => 'View Full Details',
                            'action' => 'view_drug_details',
                            'drug_id' => $drug->id
                        ]
                    ]
                ];
            }
        }
        
        return [
            'type' => 'drug_information',
            'response' => 'I can provide information about medications. Please specify which drug you\'d like to know about.',
            'suggestions' => $this->getDrugSuggestions(),
            'actions' => [
                [
                    'type' => 'button',
                    'text' => 'Browse Drug Database',
                    'action' => 'browse_drugs'
                ]
            ]
        ];
    }

    /**
     * Handle general medical advice
     */
    private function handleGeneralMedicalAdvice(string $message): array
    {
        $advice = $this->generateMedicalAdvice($message);
        
        return [
            'type' => 'general_advice',
            'response' => $advice['response'],
            'tips' => $advice['tips'],
            'actions' => [
                [
                    'type' => 'button',
                    'text' => 'Get Personalized Advice',
                    'action' => 'get_personalized_advice'
                ],
                [
                    'type' => 'button',
                    'text' => 'Book Consultation',
                    'action' => 'book_consultation'
                ]
            ]
        ];
    }

    /**
     * Handle disease information requests
     */
    private function handleDiseaseInformation(string $message): array
    {
        $diseaseName = $this->extractDiseaseName($message);
        
        if ($diseaseName) {
            $disease = Disease::where('name', 'like', "%{$diseaseName}%")->first();
            
            if ($disease) {
                return [
                    'type' => 'disease_information',
                    'response' => "Here's information about {$disease->name}:",
                    'disease' => [
                        'name' => $disease->name,
                        'description' => $disease->description,
                        'icd10_code' => $disease->icd10_code,
                        'treatment_guidelines' => $disease->treatment_guidelines,
                        'requires_xray' => $disease->requires_xray
                    ],
                    'actions' => [
                        [
                            'type' => 'button',
                            'text' => 'Learn More',
                            'action' => 'view_disease_details',
                            'disease_id' => $disease->id
                        ]
                    ]
                ];
            }
        }
        
        return [
            'type' => 'disease_information',
            'response' => 'I can provide information about various diseases and conditions. What would you like to know about?',
            'suggestions' => $this->getDiseaseSuggestions()
        ];
    }

    /**
     * Handle emergency situations
     */
    private function handleEmergency(string $message): array
    {
        return [
            'type' => 'emergency',
            'response' => '🚨 EMERGENCY ALERT: If you\'re experiencing a medical emergency, please call emergency services immediately or go to the nearest hospital.',
            'emergency_actions' => [
                'Call Emergency Services: 911',
                'Go to the nearest hospital',
                'Contact your doctor immediately',
                'If unconscious, call for help'
            ],
            'actions' => [
                [
                    'type' => 'button',
                    'text' => 'Call Emergency Services',
                    'action' => 'call_emergency',
                    'phone' => '911'
                ],
                [
                    'type' => 'button',
                    'text' => 'Find Nearest Hospital',
                    'action' => 'find_hospital'
                ]
            ]
        ];
    }

    /**
     * Handle general queries
     */
    private function handleGeneralQuery(string $message): array
    {
        return [
            'type' => 'general_query',
            'response' => 'Hello! I\'m your medical assistant. I can help you with:',
            'capabilities' => [
                'Symptom analysis and diagnosis',
                'Appointment booking',
                'Drug information',
                'Disease information',
                'General medical advice',
                'Emergency guidance'
            ],
            'actions' => [
                [
                    'type' => 'button',
                    'text' => 'Check Symptoms',
                    'action' => 'start_symptom_check'
                ],
                [
                    'type' => 'button',
                    'text' => 'Book Appointment',
                    'action' => 'book_appointment'
                ],
                [
                    'type' => 'button',
                    'text' => 'Get Medical Advice',
                    'action' => 'get_medical_advice'
                ]
            ]
        ];
    }

    /**
     * Extract symptoms from message
     */
    private function extractSymptoms(string $message): array
    {
        $symptoms = [];
        $symptomKeywords = [
            'fever', 'headache', 'fatigue', 'nausea', 'vomiting', 'cough',
            'diarrhea', 'rash', 'pain', 'weakness', 'dizziness', 'chest pain',
            'abdominal pain', 'back pain', 'muscle pain', 'joint pain'
        ];
        
        foreach ($symptomKeywords as $keyword) {
            if (stripos($message, $keyword) !== false) {
                $symptoms[] = $keyword;
            }
        }
        
        return $symptoms;
    }

    /**
     * Extract drug name from message
     */
    private function extractDrugName(string $message): ?string
    {
        $drugs = Drug::pluck('name')->toArray();
        
        foreach ($drugs as $drug) {
            if (stripos($message, $drug) !== false) {
                return $drug;
            }
        }
        
        return null;
    }

    /**
     * Extract disease name from message
     */
    private function extractDiseaseName(string $message): ?string
    {
        $diseases = Disease::pluck('name')->toArray();
        
        foreach ($diseases as $disease) {
            if (stripos($message, $disease) !== false) {
                return $disease;
            }
        }
        
        return null;
    }

    /**
     * Get symptom IDs from symptom names
     */
    private function getSymptomIds(array $symptoms): array
    {
        return Symptom::whereIn('name', $symptoms)->pluck('id')->toArray();
    }

    /**
     * Generate symptom recommendations
     */
    private function generateSymptomRecommendations(array $analysis): array
    {
        $recommendations = [];
        
        if (!empty($analysis['recommendations'])) {
            foreach ($analysis['recommendations'] as $recommendation) {
                $recommendations[] = [
                    'disease' => $recommendation['disease_name'],
                    'confidence' => $recommendation['confidence_score'],
                    'urgency' => $recommendation['urgency'],
                    'actions' => $recommendation['recommended_tests'] ?? []
                ];
            }
        }
        
        return $recommendations;
    }

    /**
     * Generate medical advice based on message
     */
    private function generateMedicalAdvice(string $message): array
    {
        $advice = [
            'response' => 'Here are some general health tips:',
            'tips' => [
                'Maintain a balanced diet',
                'Get regular exercise',
                'Stay hydrated',
                'Get adequate sleep',
                'Practice good hygiene',
                'Manage stress effectively'
            ]
        ];
        
        // Add specific advice based on keywords
        if (stripos($message, 'fever') !== false) {
            $advice['tips'][] = 'For fever: Rest, stay hydrated, and monitor temperature';
        }
        
        if (stripos($message, 'headache') !== false) {
            $advice['tips'][] = 'For headaches: Rest in a dark room, apply cold compress';
        }
        
        return $advice;
    }

    /**
     * Get symptom suggestions
     */
    private function getSymptomSuggestions(): array
    {
        return [
            'Fever and chills',
            'Headache and fatigue',
            'Nausea and vomiting',
            'Cough and chest pain',
            'Abdominal pain',
            'Skin rash or irritation'
        ];
    }

    /**
     * Get drug suggestions
     */
    private function getDrugSuggestions(): array
    {
        return Drug::limit(5)->pluck('name')->toArray();
    }

    /**
     * Get disease suggestions
     */
    private function getDiseaseSuggestions(): array
    {
        return Disease::limit(5)->pluck('name')->toArray();
    }
}
