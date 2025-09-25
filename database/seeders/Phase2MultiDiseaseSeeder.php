<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Symptom;
use App\Models\Disease;
use App\Models\ExpertSystemRule;
use App\Models\Drug;

class Phase2MultiDiseaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     * This implements Phase 2: Multi-Disease Support
     */
    public function run(): void
    {
        $this->command->info('Starting Phase 2: Multi-Disease Support Implementation...');

        // Create additional diseases
        $this->createAdditionalDiseases();
        
        // Create additional symptoms for new diseases
        $this->createAdditionalSymptoms();
        
        // Create expert system rules for new diseases
        $this->createExpertSystemRules();
        
        // Create drugs for new diseases
        $this->createAdditionalDrugs();

        $this->command->info('Phase 2: Multi-Disease Support completed successfully!');
    }

    private function createAdditionalDiseases(): void
    {
        $diseases = [
            [
                'name' => 'Tuberculosis (TB)',
                'description' => 'A bacterial infection that primarily affects the lungs, caused by Mycobacterium tuberculosis',
                'icd10_code' => 'A15',
                'treatment_guidelines' => 'Multi-drug therapy (Isoniazid, Rifampin, Ethambutol, Pyrazinamide), DOT therapy, 6-month treatment course',
                'requires_xray' => true,
                'is_active' => true,
            ],
            [
                'name' => 'HIV/AIDS',
                'description' => 'Human Immunodeficiency Virus infection leading to Acquired Immunodeficiency Syndrome',
                'icd10_code' => 'B20',
                'treatment_guidelines' => 'Antiretroviral therapy (ART), opportunistic infection prevention, CD4 monitoring',
                'requires_xray' => false,
                'is_active' => true,
            ],
            [
                'name' => 'Diabetes Mellitus',
                'description' => 'A metabolic disorder characterized by high blood sugar levels due to insulin deficiency or resistance',
                'icd10_code' => 'E11',
                'treatment_guidelines' => 'Blood glucose monitoring, insulin therapy, lifestyle modifications, diet control',
                'requires_xray' => false,
                'is_active' => true,
            ],
            [
                'name' => 'Mental Health Disorders',
                'description' => 'Various psychological and psychiatric conditions affecting mood, behavior, and cognition',
                'icd10_code' => 'F32',
                'treatment_guidelines' => 'Psychotherapy, medication, counseling, support groups, lifestyle modifications',
                'requires_xray' => false,
                'is_active' => true,
            ],
        ];

        foreach ($diseases as $disease) {
            Disease::firstOrCreate(
                ['name' => $disease['name']],
                $disease
            );
        }

        $this->command->info('Additional diseases created: ' . count($diseases));
    }

    private function createAdditionalSymptoms(): void
    {
        $additionalSymptoms = [
            // TUBERCULOSIS SYMPTOMS
            [
                'name' => 'Persistent Cough',
                'description' => 'Cough lasting more than 3 weeks, often with blood-tinged sputum',
                'severity_level' => 'severe',
                'category' => 'respiratory',
                'is_common' => true,
                'symptom_strength' => 'strong',
                'requires_xray' => true,
                'disease_association' => 'tuberculosis'
            ],
            [
                'name' => 'Chest X-ray Abnormalities',
                'description' => 'Abnormal findings on chest X-ray indicating lung involvement',
                'severity_level' => 'critical',
                'category' => 'respiratory',
                'is_common' => false,
                'symptom_strength' => 'very_strong',
                'requires_xray' => true,
                'disease_association' => 'tuberculosis'
            ],
            [
                'name' => 'Night Sweats',
                'description' => 'Excessive sweating during sleep, often drenching',
                'severity_level' => 'moderate',
                'category' => 'general',
                'is_common' => false,
                'symptom_strength' => 'weak',
                'requires_xray' => false,
                'disease_association' => 'tuberculosis'
            ],
            [
                'name' => 'Weight Loss',
                'description' => 'Unintentional weight reduction, often significant',
                'severity_level' => 'moderate',
                'category' => 'general',
                'is_common' => false,
                'symptom_strength' => 'weak',
                'requires_xray' => false,
                'disease_association' => 'tuberculosis'
            ],

            // HIV/AIDS SYMPTOMS
            [
                'name' => 'Recurrent Infections',
                'description' => 'Frequent bacterial or viral infections due to weakened immune system',
                'severity_level' => 'severe',
                'category' => 'immunological',
                'is_common' => false,
                'symptom_strength' => 'strong',
                'requires_xray' => false,
                'disease_association' => 'hiv_aids'
            ],
            [
                'name' => 'Swollen Lymph Nodes',
                'description' => 'Enlarged lymph nodes, often persistent and generalized',
                'severity_level' => 'moderate',
                'category' => 'immunological',
                'is_common' => false,
                'symptom_strength' => 'weak',
                'requires_xray' => false,
                'disease_association' => 'hiv_aids'
            ],
            [
                'name' => 'Oral Thrush',
                'description' => 'Fungal infection in the mouth, often white patches',
                'severity_level' => 'moderate',
                'category' => 'oral',
                'is_common' => false,
                'symptom_strength' => 'weak',
                'requires_xray' => false,
                'disease_association' => 'hiv_aids'
            ],

            // DIABETES SYMPTOMS
            [
                'name' => 'Excessive Thirst',
                'description' => 'Increased need for fluids, often unquenchable',
                'severity_level' => 'moderate',
                'category' => 'metabolic',
                'is_common' => false,
                'symptom_strength' => 'weak',
                'requires_xray' => false,
                'disease_association' => 'diabetes'
            ],
            [
                'name' => 'Frequent Urination',
                'description' => 'Increased frequency of urination, often excessive',
                'severity_level' => 'moderate',
                'category' => 'urinary',
                'is_common' => false,
                'symptom_strength' => 'weak',
                'requires_xray' => false,
                'disease_association' => 'diabetes'
            ],
            [
                'name' => 'Blurred Vision',
                'description' => 'Difficulty in seeing clearly, often fluctuating',
                'severity_level' => 'moderate',
                'category' => 'ophthalmic',
                'is_common' => false,
                'symptom_strength' => 'weak',
                'requires_xray' => false,
                'disease_association' => 'diabetes'
            ],
            [
                'name' => 'Slow Healing',
                'description' => 'Delayed wound healing, often with infections',
                'severity_level' => 'moderate',
                'category' => 'dermatological',
                'is_common' => false,
                'symptom_strength' => 'weak',
                'requires_xray' => false,
                'disease_association' => 'diabetes'
            ],

            // MENTAL HEALTH SYMPTOMS
            [
                'name' => 'Depression',
                'description' => 'Persistent sadness and loss of interest in activities',
                'severity_level' => 'severe',
                'category' => 'psychological',
                'is_common' => false,
                'symptom_strength' => 'strong',
                'requires_xray' => false,
                'disease_association' => 'mental_health'
            ],
            [
                'name' => 'Anxiety',
                'description' => 'Excessive worry and fear, often debilitating',
                'severity_level' => 'moderate',
                'category' => 'psychological',
                'is_common' => false,
                'symptom_strength' => 'weak',
                'requires_xray' => false,
                'disease_association' => 'mental_health'
            ],
            [
                'name' => 'Mood Swings',
                'description' => 'Rapid changes in emotional state, often extreme',
                'severity_level' => 'moderate',
                'category' => 'psychological',
                'is_common' => false,
                'symptom_strength' => 'weak',
                'requires_xray' => false,
                'disease_association' => 'mental_health'
            ],
            [
                'name' => 'Sleep Disturbances',
                'description' => 'Problems with sleep patterns, insomnia or hypersomnia',
                'severity_level' => 'moderate',
                'category' => 'psychological',
                'is_common' => false,
                'symptom_strength' => 'weak',
                'requires_xray' => false,
                'disease_association' => 'mental_health'
            ],
        ];

        foreach ($additionalSymptoms as $symptom) {
            Symptom::firstOrCreate(
                ['name' => $symptom['name'], 'disease_association' => $symptom['disease_association']],
                $symptom
            );
        }

        $this->command->info('Additional symptoms created: ' . count($additionalSymptoms));
    }

    private function createExpertSystemRules(): void
    {
        $tb = Disease::where('name', 'Tuberculosis (TB)')->first();
        $hiv = Disease::where('name', 'HIV/AIDS')->first();
        $diabetes = Disease::where('name', 'Diabetes Mellitus')->first();
        $mental = Disease::where('name', 'Mental Health Disorders')->first();

        if (!$tb || !$hiv || !$diabetes || !$mental) {
            $this->command->error('Diseases not found. Please run the disease seeder first.');
            return;
        }

        $symptoms = Symptom::all()->keyBy('name');

        // TUBERCULOSIS RULES
        ExpertSystemRule::create([
            'disease_id' => $tb->id,
            'required_symptoms' => [
                $symptoms['Persistent Cough']->id,
                $symptoms['Chest X-ray Abnormalities']->id
            ],
            'optional_symptoms' => [
                $symptoms['Night Sweats']->id,
                $symptoms['Weight Loss']->id
            ],
            'min_symptoms_required' => 2,
            'confidence_level' => 'very_strong',
            'priority_order' => 1,
            'rule_description' => 'Tuberculosis diagnosis based on persistent cough and chest X-ray abnormalities',
            'requires_xray' => true,
            'is_active' => true,
        ]);

        // HIV/AIDS RULES
        ExpertSystemRule::create([
            'disease_id' => $hiv->id,
            'required_symptoms' => [
                $symptoms['Recurrent Infections']->id
            ],
            'optional_symptoms' => [
                $symptoms['Swollen Lymph Nodes']->id,
                $symptoms['Oral Thrush']->id
            ],
            'min_symptoms_required' => 1,
            'confidence_level' => 'strong',
            'priority_order' => 1,
            'rule_description' => 'HIV/AIDS diagnosis based on recurrent infections and immune system indicators',
            'requires_xray' => false,
            'is_active' => true,
        ]);

        // DIABETES RULES
        ExpertSystemRule::create([
            'disease_id' => $diabetes->id,
            'required_symptoms' => [
                $symptoms['Excessive Thirst']->id,
                $symptoms['Frequent Urination']->id
            ],
            'optional_symptoms' => [
                $symptoms['Blurred Vision']->id,
                $symptoms['Slow Healing']->id
            ],
            'min_symptoms_required' => 2,
            'confidence_level' => 'strong',
            'priority_order' => 1,
            'rule_description' => 'Diabetes diagnosis based on metabolic symptoms and blood glucose indicators',
            'requires_xray' => false,
            'is_active' => true,
        ]);

        // MENTAL HEALTH RULES
        ExpertSystemRule::create([
            'disease_id' => $mental->id,
            'required_symptoms' => [
                $symptoms['Depression']->id
            ],
            'optional_symptoms' => [
                $symptoms['Anxiety']->id,
                $symptoms['Mood Swings']->id,
                $symptoms['Sleep Disturbances']->id
            ],
            'min_symptoms_required' => 1,
            'confidence_level' => 'strong',
            'priority_order' => 1,
            'rule_description' => 'Mental Health diagnosis based on psychological symptoms and behavioral indicators',
            'requires_xray' => false,
            'is_active' => true,
        ]);

        $this->command->info('Expert system rules created for all additional diseases');
    }

    private function createAdditionalDrugs(): void
    {
        $additionalDrugs = [
            // TUBERCULOSIS DRUGS
            [
                'name' => 'Isoniazid',
                'generic_name' => 'Isoniazid',
                'drug_code' => 'INH001',
                'category' => 'Antitubercular',
                'dosage_form' => 'Tablet',
                'strength' => '300mg',
                'indications' => 'Treatment of tuberculosis, prevention of TB in high-risk individuals',
                'contraindications' => 'Severe liver disease, hypersensitivity to isoniazid',
                'side_effects' => 'Hepatotoxicity, peripheral neuropathy, gastrointestinal upset',
                'interactions' => 'Phenytoin, warfarin, alcohol',
                'storage_conditions' => 'Store below 25°C, protect from light',
                'administration_route' => 'Oral',
                'dosage_adult' => '300mg once daily for 6 months',
                'dosage_child' => '10-15mg/kg once daily for 6 months',
                'dosage_elderly' => 'Same as adult, monitor liver function closely',
                'treatment_duration' => '6 months',
                'is_active' => true,
            ],
            [
                'name' => 'Rifampin',
                'generic_name' => 'Rifampin',
                'drug_code' => 'RF001',
                'category' => 'Antitubercular',
                'dosage_form' => 'Capsule',
                'strength' => '300mg',
                'indications' => 'Treatment of tuberculosis, prevention of meningococcal disease',
                'contraindications' => 'Severe liver disease, hypersensitivity to rifampin',
                'side_effects' => 'Hepatotoxicity, orange discoloration of body fluids, gastrointestinal upset',
                'interactions' => 'Warfarin, oral contraceptives, antiretroviral drugs',
                'storage_conditions' => 'Store below 25°C, protect from light',
                'administration_route' => 'Oral',
                'dosage_adult' => '600mg once daily for 6 months',
                'dosage_child' => '10-20mg/kg once daily for 6 months',
                'dosage_elderly' => 'Same as adult, monitor liver function',
                'treatment_duration' => '6 months',
                'is_active' => true,
            ],

            // HIV/AIDS DRUGS
            [
                'name' => 'Tenofovir',
                'generic_name' => 'Tenofovir Disoproxil Fumarate',
                'drug_code' => 'TDF001',
                'category' => 'Antiretroviral',
                'dosage_form' => 'Tablet',
                'strength' => '300mg',
                'indications' => 'Treatment of HIV infection, prevention of HIV transmission',
                'contraindications' => 'Severe renal impairment, hypersensitivity to tenofovir',
                'side_effects' => 'Renal toxicity, bone density loss, gastrointestinal upset',
                'interactions' => 'Acyclovir, ganciclovir, didanosine',
                'storage_conditions' => 'Store below 30°C',
                'administration_route' => 'Oral',
                'dosage_adult' => '300mg once daily',
                'dosage_child' => '8mg/kg once daily',
                'dosage_elderly' => 'Same as adult, monitor renal function',
                'treatment_duration' => 'Lifelong',
                'is_active' => true,
            ],

            // DIABETES DRUGS
            [
                'name' => 'Metformin',
                'generic_name' => 'Metformin',
                'drug_code' => 'MF001',
                'category' => 'Antidiabetic',
                'dosage_form' => 'Tablet',
                'strength' => '500mg',
                'indications' => 'Treatment of type 2 diabetes, prevention of diabetes in high-risk individuals',
                'contraindications' => 'Severe renal impairment, severe liver disease, heart failure',
                'side_effects' => 'Lactic acidosis, gastrointestinal upset, metallic taste',
                'interactions' => 'Contrast media, alcohol, cimetidine',
                'storage_conditions' => 'Store below 30°C',
                'administration_route' => 'Oral',
                'dosage_adult' => '500mg twice daily, may increase to 1000mg twice daily',
                'dosage_child' => 'Not recommended for children under 10',
                'dosage_elderly' => 'Same as adult, monitor renal function',
                'treatment_duration' => 'Lifelong',
                'is_active' => true,
            ],

            // MENTAL HEALTH DRUGS
            [
                'name' => 'Fluoxetine',
                'generic_name' => 'Fluoxetine',
                'drug_code' => 'FL001',
                'category' => 'Antidepressant',
                'dosage_form' => 'Capsule',
                'strength' => '20mg',
                'indications' => 'Treatment of depression, anxiety disorders, obsessive-compulsive disorder',
                'contraindications' => 'MAO inhibitors, hypersensitivity to fluoxetine',
                'side_effects' => 'Nausea, insomnia, sexual dysfunction, weight changes',
                'interactions' => 'Warfarin, MAO inhibitors, alcohol',
                'storage_conditions' => 'Store below 25°C',
                'administration_route' => 'Oral',
                'dosage_adult' => '20mg once daily, may increase to 60mg daily',
                'dosage_child' => '10mg once daily for children 8-17 years',
                'dosage_elderly' => 'Same as adult, monitor for side effects',
                'treatment_duration' => '6-12 months minimum',
                'is_active' => true,
            ],
        ];

        foreach ($additionalDrugs as $drug) {
            Drug::firstOrCreate(
                ['drug_code' => $drug['drug_code']],
                $drug
            );
        }

        $this->command->info('Additional drugs created: ' . count($additionalDrugs));
    }
}
