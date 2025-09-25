<?php

namespace App\Services;

use App\Models\Symptom;
use App\Models\Disease;
use App\Models\ExpertSystemRule;
use App\Models\Diagnosis;
use App\Models\User;
use Illuminate\Support\Collection;

class ExpertSystemService
{
    /**
     * Analyze symptoms and provide diagnosis recommendations
     * Enhanced for Table 1 requirements with VSs, Ss, Ws, VWs classification
     */
    public function analyzeSymptoms(array $symptomIds, int $patientId, int $doctorId): array
    {
        $symptoms = Symptom::whereIn('id', $symptomIds)->get();
        $rules = ExpertSystemRule::with('disease')->active()->get();
        
        // Calculate symptom strength distribution
        $symptomStrengthDistribution = $this->calculateSymptomStrengthDistribution($symptoms);
        
        $analysis = [
            'patient_id' => $patientId,
            'doctor_id' => $doctorId,
            'symptoms_analyzed' => $symptoms->pluck('name')->toArray(),
            'symptom_strength_distribution' => $symptomStrengthDistribution,
            'recommendations' => [],
            'confidence_scores' => [],
            'requires_xray' => false,
            'urgency_level' => 'normal',
            'analysis_timestamp' => now(),
        ];

        foreach ($rules as $rule) {
            $matchScore = $this->calculateRuleMatch($rule, $symptomIds);
            
            if ($matchScore > 0) {
                $recommendation = $this->generateRecommendation($rule, $matchScore, $symptoms);
                $analysis['recommendations'][] = $recommendation;
                $analysis['confidence_scores'][$rule->disease->name] = $matchScore;
                
                if ($rule->requires_xray) {
                    $analysis['requires_xray'] = true;
                }
                
                if ($matchScore >= 0.8) {
                    $analysis['urgency_level'] = 'high';
                } elseif ($matchScore >= 0.6) {
                    $analysis['urgency_level'] = 'medium';
                }
            }
        }

        // Sort recommendations by confidence score
        usort($analysis['recommendations'], function ($a, $b) {
            return $b['confidence_score'] <=> $a['confidence_score'];
        });

        return $analysis;
    }

    /**
     * Calculate how well symptoms match a rule
     */
    private function calculateRuleMatch(ExpertSystemRule $rule, array $symptomIds): float
    {
        $requiredSymptoms = $rule->required_symptoms;
        $optionalSymptoms = $rule->optional_symptoms ?? [];
        
        $requiredMatches = count(array_intersect($symptomIds, $requiredSymptoms));
        $optionalMatches = count(array_intersect($symptomIds, $optionalSymptoms));
        
        $totalRequired = count($requiredSymptoms);
        $totalOptional = count($optionalSymptoms);
        
        if ($requiredMatches < $rule->min_symptoms_required) {
            return 0;
        }
        
        // Calculate base score from required symptoms
        $requiredScore = $requiredMatches / $totalRequired;
        
        // Calculate bonus from optional symptoms
        $optionalScore = $totalOptional > 0 ? $optionalMatches / $totalOptional : 0;
        
        // Weight the scores (required symptoms are more important)
        $baseScore = ($requiredScore * 0.8) + ($optionalScore * 0.2);
        
        // Apply confidence level multiplier
        $confidenceMultiplier = $this->getConfidenceMultiplier($rule->confidence_level);
        
        return min(1.0, $baseScore * $confidenceMultiplier);
    }

    /**
     * Get confidence multiplier based on rule confidence level
     */
    private function getConfidenceMultiplier(string $confidenceLevel): float
    {
        return match ($confidenceLevel) {
            'very_strong' => 1.0,
            'strong' => 0.9,
            'weak' => 0.7,
            'very_weak' => 0.5,
            default => 0.8,
        };
    }

    /**
     * Calculate symptom strength score based on VSs, Ss, Ws, VWs classification
     */
    private function calculateSymptomStrengthScore(array $symptomIds): array
    {
        $symptoms = Symptom::whereIn('id', $symptomIds)->get();
        $strengthScores = [
            'very_strong' => 0,
            'strong' => 0,
            'weak' => 0,
            'very_weak' => 0,
        ];

        foreach ($symptoms as $symptom) {
            $strength = $symptom->symptom_strength ?? 'weak';
            $strengthScores[$strength]++;
        }

        return $strengthScores;
    }

    /**
     * Determine if X-ray is required based on symptom strength
     */
    private function requiresXrayBasedOnSymptoms(array $symptomIds): bool
    {
        $symptoms = Symptom::whereIn('id', $symptomIds)->get();
        
        foreach ($symptoms as $symptom) {
            if ($symptom->symptom_strength === 'very_strong') {
                return true;
            }
        }
        
        return false;
    }

    /**
     * Calculate symptom strength distribution for Table 1 analysis
     */
    private function calculateSymptomStrengthDistribution(Collection $symptoms): array
    {
        $distribution = [
            'very_strong' => 0,
            'strong' => 0,
            'weak' => 0,
            'very_weak' => 0,
        ];

        foreach ($symptoms as $symptom) {
            $strength = $symptom->symptom_strength ?? 'weak';
            $distribution[$strength]++;
        }

        return $distribution;
    }

    /**
     * Get treatment recommendation based on symptom strength
     */
    private function getTreatmentRecommendation(array $symptomStrengthDistribution): array
    {
        $recommendations = [];

        // Very Strong Signs (VSs) - Requires X-ray + Drug administration
        if ($symptomStrengthDistribution['very_strong'] > 0) {
            $recommendations[] = [
                'type' => 'xray_required',
                'description' => 'Very Strong Signs detected - Chest X-ray required in addition to drug administration',
                'urgency' => 'high',
                'actions' => [
                    'Schedule chest X-ray immediately',
                    'Begin drug administration protocol',
                    'Monitor patient closely',
                    'Consider hospitalization'
                ]
            ];
        }

        // Strong Signs (Ss) - Drug administration only
        if ($symptomStrengthDistribution['strong'] > 0) {
            $recommendations[] = [
                'type' => 'drug_administration',
                'description' => 'Strong Signs detected - Drug administration required (no X-ray needed)',
                'urgency' => 'medium',
                'actions' => [
                    'Begin appropriate drug therapy',
                    'Monitor patient response',
                    'Schedule follow-up appointment'
                ]
            ];
        }

        // Weak Signs (Ws) - Drug administration only
        if ($symptomStrengthDistribution['weak'] > 0) {
            $recommendations[] = [
                'type' => 'drug_administration',
                'description' => 'Weak Signs detected - Drug administration required (no X-ray needed)',
                'urgency' => 'low',
                'actions' => [
                    'Begin mild drug therapy',
                    'Monitor symptoms',
                    'Provide supportive care'
                ]
            ];
        }

        // Very Weak Signs (VWs) - Drug administration only
        if ($symptomStrengthDistribution['very_weak'] > 0) {
            $recommendations[] = [
                'type' => 'drug_administration',
                'description' => 'Very Weak Signs detected - Drug administration required (no X-ray needed)',
                'urgency' => 'low',
                'actions' => [
                    'Begin supportive drug therapy',
                    'Monitor for symptom progression',
                    'Provide general care'
                ]
            ];
        }

        return $recommendations;
    }

    /**
     * Generate diagnosis recommendation
     */
    private function generateRecommendation(ExpertSystemRule $rule, float $matchScore, Collection $symptoms): array
    {
        $disease = $rule->disease;
        
        return [
            'disease_id' => $disease->id,
            'disease_name' => $disease->name,
            'icd10_code' => $disease->icd10_code,
            'confidence_score' => $matchScore,
            'confidence_level' => $this->getConfidenceLevel($matchScore),
            'rule_description' => $rule->rule_description,
            'recommended_tests' => $this->getRecommendedTests($disease->name),
            'treatment_guidelines' => $disease->treatment_guidelines,
            'urgency' => $this->getUrgencyLevel($matchScore),
            'symptoms_matched' => $this->getMatchedSymptoms($rule, $symptoms),
            'additional_symptoms' => $this->getAdditionalSymptoms($rule, $symptoms),
        ];
    }

    /**
     * Get confidence level based on score
     */
    private function getConfidenceLevel(float $score): string
    {
        return match (true) {
            $score >= 0.9 => 'very_high',
            $score >= 0.8 => 'high',
            $score >= 0.6 => 'medium',
            $score >= 0.4 => 'low',
            default => 'very_low',
        };
    }

    /**
     * Get recommended tests for a disease
     */
    private function getRecommendedTests(string $diseaseName): array
    {
        return match ($diseaseName) {
            'Malaria' => [
                'Blood smear microscopy',
                'Rapid diagnostic test (RDT)',
                'Polymerase chain reaction (PCR)',
                'Complete blood count (CBC)',
                'Liver function tests',
            ],
            'Typhoid Fever' => [
                'Blood culture',
                'Widal test',
                'Stool culture',
                'Urine culture',
                'Complete blood count (CBC)',
                'Liver function tests',
            ],
            default => [
                'Complete blood count (CBC)',
                'Basic metabolic panel',
                'Physical examination',
            ],
        };
    }

    /**
     * Get urgency level based on confidence score
     */
    private function getUrgencyLevel(float $score): string
    {
        return match (true) {
            $score >= 0.9 => 'critical',
            $score >= 0.8 => 'high',
            $score >= 0.6 => 'medium',
            default => 'low',
        };
    }

    /**
     * Get symptoms that matched the rule
     */
    private function getMatchedSymptoms(ExpertSystemRule $rule, Collection $symptoms): array
    {
        $matchedIds = array_intersect($symptoms->pluck('id')->toArray(), $rule->required_symptoms);
        return $symptoms->whereIn('id', $matchedIds)->pluck('name')->toArray();
    }

    /**
     * Get additional symptoms that could strengthen diagnosis
     */
    private function getAdditionalSymptoms(ExpertSystemRule $rule, Collection $symptoms): array
    {
        $allRuleSymptoms = array_merge($rule->required_symptoms, $rule->optional_symptoms ?? []);
        $currentSymptoms = $symptoms->pluck('id')->toArray();
        $missingSymptoms = array_diff($allRuleSymptoms, $currentSymptoms);
        
        return Symptom::whereIn('id', $missingSymptoms)->pluck('name')->toArray();
    }

    /**
     * Create diagnosis record from analysis
     */
    public function createDiagnosis(array $analysis, array $additionalData = []): Diagnosis
    {
        $topRecommendation = $analysis['recommendations'][0] ?? null;
        
        if (!$topRecommendation) {
            throw new \Exception('No diagnosis recommendations available');
        }

        return Diagnosis::create([
            'patient_id' => $analysis['patient_id'],
            'doctor_id' => $analysis['doctor_id'],
            'disease_id' => $topRecommendation['disease_id'],
            'diagnosis_code' => $topRecommendation['icd10_code'],
            'symptoms_entered' => $analysis['symptoms_analyzed'],
            'expert_system_analysis' => $analysis,
            'confidence_level' => $this->mapConfidenceLevel($topRecommendation['confidence_level']),
            'requires_xray' => $analysis['requires_xray'],
            'clinical_notes' => $additionalData['clinical_notes'] ?? '',
            'differential_diagnosis' => $this->generateDifferentialDiagnosis($analysis['recommendations']),
            'status' => 'tentative',
            'diagnosis_date' => now(),
        ]);
    }

    /**
     * Map confidence level to database enum
     */
    private function mapConfidenceLevel(string $level): string
    {
        return match ($level) {
            'very_high', 'high' => 'very_strong',
            'medium' => 'strong',
            'low' => 'weak',
            'very_low' => 'very_weak',
            default => 'weak',
        };
    }

    /**
     * Generate differential diagnosis
     */
    private function generateDifferentialDiagnosis(array $recommendations): string
    {
        $differentials = [];
        
        foreach (array_slice($recommendations, 1, 3) as $recommendation) {
            $differentials[] = $recommendation['disease_name'] . 
                ' (Confidence: ' . round($recommendation['confidence_score'] * 100) . '%)';
        }
        
        return implode(', ', $differentials);
    }

    /**
     * Get expert system statistics
     */
    public function getSystemStatistics(): array
    {
        return [
            'total_rules' => ExpertSystemRule::active()->count(),
            'total_diseases' => Disease::count(),
            'total_symptoms' => Symptom::count(),
            'total_diagnoses' => Diagnosis::count(),
            'recent_diagnoses' => Diagnosis::where('created_at', '>=', now()->subDays(7))->count(),
            'most_common_diseases' => $this->getMostCommonDiseases(),
            'system_accuracy' => $this->calculateSystemAccuracy(),
        ];
    }

    /**
     * Get most commonly diagnosed diseases
     */
    private function getMostCommonDiseases(): array
    {
        return Diagnosis::with('disease')
            ->selectRaw('disease_id, count(*) as diagnosis_count')
            ->groupBy('disease_id')
            ->orderBy('diagnosis_count', 'desc')
            ->limit(5)
            ->get()
            ->map(function ($item) {
                return [
                    'disease_name' => $item->disease->name,
                    'count' => $item->diagnosis_count,
                ];
            })
            ->toArray();
    }

    /**
     * Calculate system accuracy (placeholder for future implementation)
     */
    private function calculateSystemAccuracy(): float
    {
        // This would be calculated based on confirmed diagnoses vs expert system predictions
        // For now, return a placeholder value
        return 0.85; // 85% accuracy
    }

    /**
     * Validate symptoms for expert system analysis
     */
    public function validateSymptoms(array $symptomIds): array
    {
        $errors = [];
        
        if (empty($symptomIds)) {
            $errors[] = 'At least one symptom must be selected';
        }
        
        if (count($symptomIds) > 20) {
            $errors[] = 'Maximum 20 symptoms can be analyzed at once';
        }
        
        $validSymptoms = Symptom::whereIn('id', $symptomIds)->count();
        if ($validSymptoms !== count($symptomIds)) {
            $errors[] = 'One or more selected symptoms are invalid';
        }
        
        return $errors;
    }
}
