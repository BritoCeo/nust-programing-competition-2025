<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Symptom;

class Table1SymptomSeeder extends Seeder
{
    /**
     * Run the database seeds.
     * This implements the exact symptoms from Table 1 of the requirements
     */
    public function run(): void
    {
        // Clear existing symptoms to avoid duplicates
        // Can't truncate due to foreign key constraints, so we'll delete manually
        Symptom::query()->delete();

        // MALARIA SYMPTOMS (Table 1)
        $malariaSymptoms = [
            // Very Strong Signs (VSs) - Requires X-ray
            [
                'name' => 'Abdominal Pain',
                'description' => 'Severe pain in the abdominal region, often indicating serious complications',
                'severity_level' => 'critical',
                'category' => 'gastrointestinal',
                'is_common' => true,
                'symptom_strength' => 'very_strong',
                'requires_xray' => true,
                'disease_association' => 'malaria'
            ],
            [
                'name' => 'Vomiting',
                'description' => 'Forceful ejection of stomach contents, often persistent and severe',
                'severity_level' => 'critical',
                'category' => 'gastrointestinal',
                'is_common' => true,
                'symptom_strength' => 'very_strong',
                'requires_xray' => true,
                'disease_association' => 'malaria'
            ],
            [
                'name' => 'Sore Throat',
                'description' => 'Pain or irritation in the throat, often severe and persistent',
                'severity_level' => 'critical',
                'category' => 'respiratory',
                'is_common' => true,
                'symptom_strength' => 'very_strong',
                'requires_xray' => true,
                'disease_association' => 'malaria'
            ],

            // Strong Signs (Ss) - Drug administration only
            [
                'name' => 'Headache',
                'description' => 'Pain in the head or neck area, often severe and persistent',
                'severity_level' => 'severe',
                'category' => 'neurological',
                'is_common' => true,
                'symptom_strength' => 'strong',
                'requires_xray' => false,
                'disease_association' => 'malaria'
            ],
            [
                'name' => 'Fatigue',
                'description' => 'Extreme tiredness and lack of energy, often debilitating',
                'severity_level' => 'severe',
                'category' => 'general',
                'is_common' => true,
                'symptom_strength' => 'strong',
                'requires_xray' => false,
                'disease_association' => 'malaria'
            ],
            [
                'name' => 'Cough',
                'description' => 'Reflex action to clear throat and airways, often persistent',
                'severity_level' => 'severe',
                'category' => 'respiratory',
                'is_common' => true,
                'symptom_strength' => 'strong',
                'requires_xray' => false,
                'disease_association' => 'malaria'
            ],
            [
                'name' => 'Constipation',
                'description' => 'Difficulty in bowel movements, often severe and persistent',
                'severity_level' => 'severe',
                'category' => 'gastrointestinal',
                'is_common' => true,
                'symptom_strength' => 'strong',
                'requires_xray' => false,
                'disease_association' => 'malaria'
            ],

            // Weak Signs (Ws) - Drug administration only
            [
                'name' => 'Chest Pain',
                'description' => 'Pain or discomfort in the chest area',
                'severity_level' => 'moderate',
                'category' => 'cardiovascular',
                'is_common' => false,
                'symptom_strength' => 'weak',
                'requires_xray' => false,
                'disease_association' => 'malaria'
            ],
            [
                'name' => 'Back Pain',
                'description' => 'Pain in the back region, often muscular',
                'severity_level' => 'moderate',
                'category' => 'musculoskeletal',
                'is_common' => false,
                'symptom_strength' => 'weak',
                'requires_xray' => false,
                'disease_association' => 'malaria'
            ],
            [
                'name' => 'Muscle Pain',
                'description' => 'Pain or discomfort in muscles, often generalized',
                'severity_level' => 'moderate',
                'category' => 'musculoskeletal',
                'is_common' => false,
                'symptom_strength' => 'weak',
                'requires_xray' => false,
                'disease_association' => 'malaria'
            ],

            // Very Weak Signs (VWs) - Drug administration only
            [
                'name' => 'Diarrhea',
                'description' => 'Frequent loose or liquid bowel movements',
                'severity_level' => 'mild',
                'category' => 'gastrointestinal',
                'is_common' => true,
                'symptom_strength' => 'very_weak',
                'requires_xray' => false,
                'disease_association' => 'malaria'
            ],
            [
                'name' => 'Sweating',
                'description' => 'Excessive perspiration, often profuse',
                'severity_level' => 'mild',
                'category' => 'general',
                'is_common' => true,
                'symptom_strength' => 'very_weak',
                'requires_xray' => false,
                'disease_association' => 'malaria'
            ],
            [
                'name' => 'Rash',
                'description' => 'Skin irritation or eruption, often mild',
                'severity_level' => 'mild',
                'category' => 'dermatological',
                'is_common' => false,
                'symptom_strength' => 'very_weak',
                'requires_xray' => false,
                'disease_association' => 'malaria'
            ],
            [
                'name' => 'Loss of Appetite',
                'description' => 'Reduced desire to eat, often significant',
                'severity_level' => 'mild',
                'category' => 'gastrointestinal',
                'is_common' => true,
                'symptom_strength' => 'very_weak',
                'requires_xray' => false,
                'disease_association' => 'malaria'
            ],
        ];

        // TYPHOID SYMPTOMS (Table 1)
        $typhoidSymptoms = [
            // Very Strong Signs (VSs) - Requires X-ray
            [
                'name' => 'Abdominal Pain',
                'description' => 'Severe pain in the abdominal region, often indicating serious complications',
                'severity_level' => 'critical',
                'category' => 'gastrointestinal',
                'is_common' => true,
                'symptom_strength' => 'very_strong',
                'requires_xray' => true,
                'disease_association' => 'typhoid'
            ],
            [
                'name' => 'Stomach Issues',
                'description' => 'Digestive problems and stomach discomfort, often severe',
                'severity_level' => 'critical',
                'category' => 'gastrointestinal',
                'is_common' => true,
                'symptom_strength' => 'very_strong',
                'requires_xray' => true,
                'disease_association' => 'typhoid'
            ],

            // Strong Signs (Ss) - Drug administration only
            [
                'name' => 'Headache',
                'description' => 'Pain in the head or neck area, often severe and persistent',
                'severity_level' => 'severe',
                'category' => 'neurological',
                'is_common' => true,
                'symptom_strength' => 'strong',
                'requires_xray' => false,
                'disease_association' => 'typhoid'
            ],
            [
                'name' => 'Persistent High Fever',
                'description' => 'Sustained elevated body temperature above 38.5°C',
                'severity_level' => 'severe',
                'category' => 'fever',
                'is_common' => true,
                'symptom_strength' => 'strong',
                'requires_xray' => false,
                'disease_association' => 'typhoid'
            ],

            // Weak Signs (Ws) - Drug administration only
            [
                'name' => 'Weakness',
                'description' => 'Lack of physical strength, often generalized',
                'severity_level' => 'moderate',
                'category' => 'general',
                'is_common' => true,
                'symptom_strength' => 'weak',
                'requires_xray' => false,
                'disease_association' => 'typhoid'
            ],
            [
                'name' => 'Tiredness',
                'description' => 'Feeling of exhaustion, often persistent',
                'severity_level' => 'moderate',
                'category' => 'general',
                'is_common' => true,
                'symptom_strength' => 'weak',
                'requires_xray' => false,
                'disease_association' => 'typhoid'
            ],

            // Very Weak Signs (VWs) - Drug administration only
            [
                'name' => 'Rash',
                'description' => 'Skin irritation or eruption, often mild',
                'severity_level' => 'mild',
                'category' => 'dermatological',
                'is_common' => false,
                'symptom_strength' => 'very_weak',
                'requires_xray' => false,
                'disease_association' => 'typhoid'
            ],
            [
                'name' => 'Loss of Appetite',
                'description' => 'Reduced desire to eat, often significant',
                'severity_level' => 'mild',
                'category' => 'gastrointestinal',
                'is_common' => true,
                'symptom_strength' => 'very_weak',
                'requires_xray' => false,
                'disease_association' => 'typhoid'
            ],
        ];

        // Combine all symptoms and remove duplicates
        $allSymptoms = array_merge($malariaSymptoms, $typhoidSymptoms);
        
        // Remove duplicates based on name and disease association
        $uniqueSymptoms = [];
        foreach ($allSymptoms as $symptom) {
            $key = $symptom['name'] . '_' . $symptom['disease_association'];
            if (!isset($uniqueSymptoms[$key])) {
                $uniqueSymptoms[$key] = $symptom;
            }
        }

        // Create symptoms in database
        foreach ($uniqueSymptoms as $symptom) {
            Symptom::create($symptom);
        }

        $this->command->info('Table 1 Symptoms seeded successfully!');
        $this->command->info('Total symptoms created: ' . count($uniqueSymptoms));
        
        // Display summary
        $this->command->info('Symptom Classification Summary:');
        $this->command->info('Very Strong Signs (VSs): ' . count(array_filter($uniqueSymptoms, fn($s) => $s['symptom_strength'] === 'very_strong')));
        $this->command->info('Strong Signs (Ss): ' . count(array_filter($uniqueSymptoms, fn($s) => $s['symptom_strength'] === 'strong')));
        $this->command->info('Weak Signs (Ws): ' . count(array_filter($uniqueSymptoms, fn($s) => $s['symptom_strength'] === 'weak')));
        $this->command->info('Very Weak Signs (VWs): ' . count(array_filter($uniqueSymptoms, fn($s) => $s['symptom_strength'] === 'very_weak')));
    }
}
