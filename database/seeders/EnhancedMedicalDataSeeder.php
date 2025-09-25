<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Symptom;
use App\Models\Disease;
use App\Models\ExpertSystemRule;
use App\Models\Drug;
use App\Models\Pharmacy;
use App\Models\User;

class EnhancedMedicalDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->createComprehensiveSymptoms();
        $this->createDiseases();
        $this->createExpertSystemRules();
        $this->createDrugDatabase();
        $this->createPharmacyData();
    }

    private function createComprehensiveSymptoms(): void
    {
        // Malaria Symptoms (VSs, Ss, Ws, VWs)
        $malariaSymptoms = [
            // Very Strong Signs (VSs)
            ['name' => 'Abdominal Pain', 'description' => 'Severe pain in the abdominal region', 'severity_level' => 'critical', 'category' => 'gastrointestinal', 'is_common' => true, 'symptom_strength' => 'very_strong'],
            ['name' => 'Vomiting', 'description' => 'Forceful ejection of stomach contents', 'severity_level' => 'critical', 'category' => 'gastrointestinal', 'is_common' => true, 'symptom_strength' => 'very_strong'],
            ['name' => 'Sore Throat', 'description' => 'Pain or irritation in the throat', 'severity_level' => 'critical', 'category' => 'respiratory', 'is_common' => true, 'symptom_strength' => 'very_strong'],
            
            // Strong Signs (Ss)
            ['name' => 'Headache', 'description' => 'Pain in the head or neck area', 'severity_level' => 'severe', 'category' => 'neurological', 'is_common' => true, 'symptom_strength' => 'strong'],
            ['name' => 'Fatigue', 'description' => 'Extreme tiredness and lack of energy', 'severity_level' => 'severe', 'category' => 'general', 'is_common' => true, 'symptom_strength' => 'strong'],
            ['name' => 'Cough', 'description' => 'Reflex action to clear throat and airways', 'severity_level' => 'severe', 'category' => 'respiratory', 'is_common' => true, 'symptom_strength' => 'strong'],
            ['name' => 'Constipation', 'description' => 'Difficulty in bowel movements', 'severity_level' => 'severe', 'category' => 'gastrointestinal', 'is_common' => true, 'symptom_strength' => 'strong'],
            
            // Weak Signs (Ws)
            ['name' => 'Chest Pain', 'description' => 'Pain or discomfort in the chest area', 'severity_level' => 'moderate', 'category' => 'cardiovascular', 'is_common' => false, 'symptom_strength' => 'weak'],
            ['name' => 'Back Pain', 'description' => 'Pain in the back region', 'severity_level' => 'moderate', 'category' => 'musculoskeletal', 'is_common' => false, 'symptom_strength' => 'weak'],
            ['name' => 'Muscle Pain', 'description' => 'Pain or discomfort in muscles', 'severity_level' => 'moderate', 'category' => 'musculoskeletal', 'is_common' => false, 'symptom_strength' => 'weak'],
            
            // Very Weak Signs (VWs)
            ['name' => 'Diarrhea', 'description' => 'Frequent loose or liquid bowel movements', 'severity_level' => 'mild', 'category' => 'gastrointestinal', 'is_common' => true, 'symptom_strength' => 'very_weak'],
            ['name' => 'Sweating', 'description' => 'Excessive perspiration', 'severity_level' => 'mild', 'category' => 'general', 'is_common' => true, 'symptom_strength' => 'very_weak'],
            ['name' => 'Rash', 'description' => 'Skin irritation or eruption', 'severity_level' => 'mild', 'category' => 'dermatological', 'is_common' => false, 'symptom_strength' => 'very_weak'],
            ['name' => 'Loss of Appetite', 'description' => 'Reduced desire to eat', 'severity_level' => 'mild', 'category' => 'gastrointestinal', 'is_common' => true, 'symptom_strength' => 'very_weak'],
        ];

        // Typhoid Symptoms (VSs, Ss, Ws, VWs)
        $typhoidSymptoms = [
            // Very Strong Signs (VSs)
            ['name' => 'Abdominal Pain', 'description' => 'Severe pain in the abdominal region', 'severity_level' => 'critical', 'category' => 'gastrointestinal', 'is_common' => true, 'symptom_strength' => 'very_strong'],
            ['name' => 'Stomach Issues', 'description' => 'Digestive problems and stomach discomfort', 'severity_level' => 'critical', 'category' => 'gastrointestinal', 'is_common' => true, 'symptom_strength' => 'very_strong'],
            
            // Strong Signs (Ss)
            ['name' => 'Headache', 'description' => 'Pain in the head or neck area', 'severity_level' => 'severe', 'category' => 'neurological', 'is_common' => true, 'symptom_strength' => 'strong'],
            ['name' => 'Persistent High Fever', 'description' => 'Sustained elevated body temperature', 'severity_level' => 'severe', 'category' => 'fever', 'is_common' => true, 'symptom_strength' => 'strong'],
            
            // Weak Signs (Ws)
            ['name' => 'Weakness', 'description' => 'Lack of physical strength', 'severity_level' => 'moderate', 'category' => 'general', 'is_common' => true, 'symptom_strength' => 'weak'],
            ['name' => 'Tiredness', 'description' => 'Feeling of exhaustion', 'severity_level' => 'moderate', 'category' => 'general', 'is_common' => true, 'symptom_strength' => 'weak'],
            
            // Very Weak Signs (VWs)
            ['name' => 'Rash', 'description' => 'Skin irritation or eruption', 'severity_level' => 'mild', 'category' => 'dermatological', 'is_common' => false, 'symptom_strength' => 'very_weak'],
            ['name' => 'Loss of Appetite', 'description' => 'Reduced desire to eat', 'severity_level' => 'mild', 'category' => 'gastrointestinal', 'is_common' => true, 'symptom_strength' => 'very_weak'],
        ];

        // Additional symptoms for other diseases
        $additionalSymptoms = [
            // TB Symptoms
            ['name' => 'Persistent Cough', 'description' => 'Cough lasting more than 3 weeks', 'severity_level' => 'severe', 'category' => 'respiratory', 'is_common' => false, 'symptom_strength' => 'strong'],
            ['name' => 'Chest X-ray Abnormalities', 'description' => 'Abnormal findings on chest X-ray', 'severity_level' => 'critical', 'category' => 'respiratory', 'is_common' => false, 'symptom_strength' => 'very_strong'],
            ['name' => 'Night Sweats', 'description' => 'Excessive sweating during sleep', 'severity_level' => 'moderate', 'category' => 'general', 'is_common' => false, 'symptom_strength' => 'weak'],
            ['name' => 'Weight Loss', 'description' => 'Unintentional weight reduction', 'severity_level' => 'moderate', 'category' => 'general', 'is_common' => false, 'symptom_strength' => 'weak'],
            
            // HIV/AIDS Symptoms
            ['name' => 'Recurrent Infections', 'description' => 'Frequent bacterial or viral infections', 'severity_level' => 'severe', 'category' => 'immunological', 'is_common' => false, 'symptom_strength' => 'strong'],
            ['name' => 'Swollen Lymph Nodes', 'description' => 'Enlarged lymph nodes', 'severity_level' => 'moderate', 'category' => 'immunological', 'is_common' => false, 'symptom_strength' => 'weak'],
            ['name' => 'Oral Thrush', 'description' => 'Fungal infection in the mouth', 'severity_level' => 'moderate', 'category' => 'oral', 'is_common' => false, 'symptom_strength' => 'weak'],
            
            // Diabetes Symptoms
            ['name' => 'Excessive Thirst', 'description' => 'Increased need for fluids', 'severity_level' => 'moderate', 'category' => 'metabolic', 'is_common' => false, 'symptom_strength' => 'weak'],
            ['name' => 'Frequent Urination', 'description' => 'Increased frequency of urination', 'severity_level' => 'moderate', 'category' => 'urinary', 'is_common' => false, 'symptom_strength' => 'weak'],
            ['name' => 'Blurred Vision', 'description' => 'Difficulty in seeing clearly', 'severity_level' => 'moderate', 'category' => 'ophthalmic', 'is_common' => false, 'symptom_strength' => 'weak'],
            ['name' => 'Slow Healing', 'description' => 'Delayed wound healing', 'severity_level' => 'moderate', 'category' => 'dermatological', 'is_common' => false, 'symptom_strength' => 'weak'],
            
            // Mental Health Symptoms
            ['name' => 'Depression', 'description' => 'Persistent sadness and loss of interest', 'severity_level' => 'severe', 'category' => 'psychological', 'is_common' => false, 'symptom_strength' => 'strong'],
            ['name' => 'Anxiety', 'description' => 'Excessive worry and fear', 'severity_level' => 'moderate', 'category' => 'psychological', 'is_common' => false, 'symptom_strength' => 'weak'],
            ['name' => 'Mood Swings', 'description' => 'Rapid changes in emotional state', 'severity_level' => 'moderate', 'category' => 'psychological', 'is_common' => false, 'symptom_strength' => 'weak'],
            ['name' => 'Sleep Disturbances', 'description' => 'Problems with sleep patterns', 'severity_level' => 'moderate', 'category' => 'psychological', 'is_common' => false, 'symptom_strength' => 'weak'],
        ];

        // Combine all symptoms
        $allSymptoms = array_merge($malariaSymptoms, $typhoidSymptoms, $additionalSymptoms);
        
        // Remove duplicates based on name
        $uniqueSymptoms = [];
        foreach ($allSymptoms as $symptom) {
            if (!isset($uniqueSymptoms[$symptom['name']])) {
                $uniqueSymptoms[$symptom['name']] = $symptom;
            }
        }

        foreach ($uniqueSymptoms as $symptom) {
            Symptom::firstOrCreate(
                ['name' => $symptom['name']],
                $symptom
            );
        }
    }

    private function createDiseases(): void
    {
        $diseases = [
            [
                'name' => 'Malaria',
                'description' => 'A life-threatening disease caused by parasites transmitted through mosquito bites',
                'icd10_code' => 'B50',
                'treatment_guidelines' => 'Antimalarial medications (Artemether-Lumefantrine, Chloroquine), supportive care, prevention measures',
                'requires_xray' => false,
                'is_active' => true,
            ],
            [
                'name' => 'Typhoid Fever',
                'description' => 'A bacterial infection caused by Salmonella typhi',
                'icd10_code' => 'A01.0',
                'treatment_guidelines' => 'Antibiotic therapy (Ciprofloxacin, Azithromycin), supportive care, fluid replacement',
                'requires_xray' => false,
                'is_active' => true,
            ],
            [
                'name' => 'Tuberculosis (TB)',
                'description' => 'A bacterial infection that primarily affects the lungs',
                'icd10_code' => 'A15',
                'treatment_guidelines' => 'Multi-drug therapy (Isoniazid, Rifampin, Ethambutol, Pyrazinamide), DOT therapy',
                'requires_xray' => true,
                'is_active' => true,
            ],
            [
                'name' => 'HIV/AIDS',
                'description' => 'Human Immunodeficiency Virus infection',
                'icd10_code' => 'B20',
                'treatment_guidelines' => 'Antiretroviral therapy (ART), opportunistic infection prevention',
                'requires_xray' => false,
                'is_active' => true,
            ],
            [
                'name' => 'Diabetes Mellitus',
                'description' => 'A metabolic disorder characterized by high blood sugar levels',
                'icd10_code' => 'E11',
                'treatment_guidelines' => 'Blood glucose monitoring, insulin therapy, lifestyle modifications',
                'requires_xray' => false,
                'is_active' => true,
            ],
            [
                'name' => 'Mental Health Disorders',
                'description' => 'Various psychological and psychiatric conditions',
                'icd10_code' => 'F32',
                'treatment_guidelines' => 'Psychotherapy, medication, counseling, support groups',
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
    }

    private function createExpertSystemRules(): void
    {
        $malaria = Disease::where('name', 'Malaria')->first();
        $typhoid = Disease::where('name', 'Typhoid Fever')->first();
        $tb = Disease::where('name', 'Tuberculosis (TB)')->first();
        $hiv = Disease::where('name', 'HIV/AIDS')->first();
        $diabetes = Disease::where('name', 'Diabetes Mellitus')->first();
        $mental = Disease::where('name', 'Mental Health Disorders')->first();

        // Get symptoms
        $symptoms = Symptom::all()->keyBy('name');

        // Malaria Rules
        $this->createMalariaRules($malaria, $symptoms);
        $this->createTyphoidRules($typhoid, $symptoms);
        $this->createTBRules($tb, $symptoms);
        $this->createHIVRules($hiv, $symptoms);
        $this->createDiabetesRules($diabetes, $symptoms);
        $this->createMentalHealthRules($mental, $symptoms);
    }

    private function createMalariaRules($malaria, $symptoms): void
    {
        // Very Strong Signs (VSs) - Requires X-ray
        ExpertSystemRule::create([
            'disease_id' => $malaria->id,
            'required_symptoms' => [
                $symptoms['Abdominal Pain']->id,
                $symptoms['Vomiting']->id,
                $symptoms['Sore Throat']->id
            ],
            'optional_symptoms' => [],
            'min_symptoms_required' => 2,
            'confidence_level' => 'very_strong',
            'priority_order' => 1,
            'rule_description' => 'Very Strong Signs (VSs) of Malaria - requires chest X-ray',
            'requires_xray' => true,
            'is_active' => true,
        ]);

        // Strong Signs (Ss)
        ExpertSystemRule::create([
            'disease_id' => $malaria->id,
            'required_symptoms' => [
                $symptoms['Headache']->id,
                $symptoms['Fatigue']->id
            ],
            'optional_symptoms' => [
                $symptoms['Cough']->id,
                $symptoms['Constipation']->id
            ],
            'min_symptoms_required' => 2,
            'confidence_level' => 'strong',
            'priority_order' => 2,
            'rule_description' => 'Strong Signs (Ss) of Malaria - drug administration only',
            'requires_xray' => false,
            'is_active' => true,
        ]);

        // Weak Signs (Ws)
        ExpertSystemRule::create([
            'disease_id' => $malaria->id,
            'required_symptoms' => [
                $symptoms['Chest Pain']->id,
                $symptoms['Back Pain']->id,
                $symptoms['Muscle Pain']->id
            ],
            'optional_symptoms' => [],
            'min_symptoms_required' => 1,
            'confidence_level' => 'weak',
            'priority_order' => 3,
            'rule_description' => 'Weak Signs (Ws) of Malaria - drug administration only',
            'requires_xray' => false,
            'is_active' => true,
        ]);

        // Very Weak Signs (VWs)
        ExpertSystemRule::create([
            'disease_id' => $malaria->id,
            'required_symptoms' => [
                $symptoms['Diarrhea']->id,
                $symptoms['Sweating']->id,
                $symptoms['Rash']->id,
                $symptoms['Loss of Appetite']->id
            ],
            'optional_symptoms' => [],
            'min_symptoms_required' => 2,
            'confidence_level' => 'very_weak',
            'priority_order' => 4,
            'rule_description' => 'Very Weak Signs (VWs) of Malaria - drug administration only',
            'requires_xray' => false,
            'is_active' => true,
        ]);
    }

    private function createTyphoidRules($typhoid, $symptoms): void
    {
        // Very Strong Signs (VSs) - Requires X-ray
        ExpertSystemRule::create([
            'disease_id' => $typhoid->id,
            'required_symptoms' => [
                $symptoms['Abdominal Pain']->id,
                $symptoms['Stomach Issues']->id
            ],
            'optional_symptoms' => [],
            'min_symptoms_required' => 2,
            'confidence_level' => 'very_strong',
            'priority_order' => 1,
            'rule_description' => 'Very Strong Signs (VSs) of Typhoid - requires chest X-ray',
            'requires_xray' => true,
            'is_active' => true,
        ]);

        // Strong Signs (Ss)
        ExpertSystemRule::create([
            'disease_id' => $typhoid->id,
            'required_symptoms' => [
                $symptoms['Headache']->id,
                $symptoms['Persistent High Fever']->id
            ],
            'optional_symptoms' => [],
            'min_symptoms_required' => 2,
            'confidence_level' => 'strong',
            'priority_order' => 2,
            'rule_description' => 'Strong Signs (Ss) of Typhoid - drug administration only',
            'requires_xray' => false,
            'is_active' => true,
        ]);

        // Weak Signs (Ws)
        ExpertSystemRule::create([
            'disease_id' => $typhoid->id,
            'required_symptoms' => [
                $symptoms['Weakness']->id,
                $symptoms['Tiredness']->id
            ],
            'optional_symptoms' => [],
            'min_symptoms_required' => 1,
            'confidence_level' => 'weak',
            'priority_order' => 3,
            'rule_description' => 'Weak Signs (Ws) of Typhoid - drug administration only',
            'requires_xray' => false,
            'is_active' => true,
        ]);

        // Very Weak Signs (VWs)
        ExpertSystemRule::create([
            'disease_id' => $typhoid->id,
            'required_symptoms' => [
                $symptoms['Rash']->id,
                $symptoms['Loss of Appetite']->id
            ],
            'optional_symptoms' => [],
            'min_symptoms_required' => 1,
            'confidence_level' => 'very_weak',
            'priority_order' => 4,
            'rule_description' => 'Very Weak Signs (VWs) of Typhoid - drug administration only',
            'requires_xray' => false,
            'is_active' => true,
        ]);
    }

    private function createTBRules($tb, $symptoms): void
    {
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
            'rule_description' => 'Tuberculosis diagnosis based on persistent cough and chest X-ray',
            'requires_xray' => true,
            'is_active' => true,
        ]);
    }

    private function createHIVRules($hiv, $symptoms): void
    {
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
            'rule_description' => 'HIV/AIDS diagnosis based on recurrent infections',
            'requires_xray' => false,
            'is_active' => true,
        ]);
    }

    private function createDiabetesRules($diabetes, $symptoms): void
    {
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
            'rule_description' => 'Diabetes diagnosis based on metabolic symptoms',
            'requires_xray' => false,
            'is_active' => true,
        ]);
    }

    private function createMentalHealthRules($mental, $symptoms): void
    {
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
            'rule_description' => 'Mental Health diagnosis based on psychological symptoms',
            'requires_xray' => false,
            'is_active' => true,
        ]);
    }

    private function createDrugDatabase(): void
    {
        $drugs = [
            // Malaria Drugs
            [
                'name' => 'Artemether-Lumefantrine',
                'generic_name' => 'Artemether-Lumefantrine',
                'drug_code' => 'AL001',
                'category' => 'Antimalarial',
                'dosage_form' => 'Tablet',
                'strength' => '20mg/120mg',
                'indications' => 'Treatment of uncomplicated malaria',
                'contraindications' => 'Pregnancy (first trimester), severe malaria',
                'side_effects' => 'Nausea, vomiting, dizziness, headache',
                'interactions' => 'Warfarin, anticonvulsants',
                'storage_conditions' => 'Store below 30°C, protect from light',
                'is_active' => true,
            ],
            [
                'name' => 'Chloroquine',
                'generic_name' => 'Chloroquine Phosphate',
                'drug_code' => 'CQ001',
                'category' => 'Antimalarial',
                'dosage_form' => 'Tablet',
                'strength' => '250mg',
                'indications' => 'Treatment and prevention of malaria',
                'contraindications' => 'Retinal disease, porphyria',
                'side_effects' => 'Retinal damage, gastrointestinal upset',
                'interactions' => 'Digoxin, antacids',
                'storage_conditions' => 'Store at room temperature',
                'is_active' => true,
            ],
            [
                'name' => 'Quinine',
                'generic_name' => 'Quinine Sulfate',
                'drug_code' => 'QN001',
                'category' => 'Antimalarial',
                'dosage_form' => 'Tablet',
                'strength' => '300mg',
                'indications' => 'Treatment of severe malaria',
                'contraindications' => 'G6PD deficiency, optic neuritis',
                'side_effects' => 'Cinchonism, hypoglycemia',
                'interactions' => 'Digoxin, warfarin',
                'storage_conditions' => 'Store below 25°C',
                'is_active' => true,
            ],

            // Typhoid Drugs
            [
                'name' => 'Ciprofloxacin',
                'generic_name' => 'Ciprofloxacin',
                'drug_code' => 'CF001',
                'category' => 'Antibiotic',
                'dosage_form' => 'Tablet',
                'strength' => '500mg',
                'indications' => 'Treatment of typhoid fever',
                'contraindications' => 'Pregnancy, children under 18',
                'side_effects' => 'Tendon rupture, photosensitivity',
                'interactions' => 'Warfarin, theophylline',
                'storage_conditions' => 'Store at room temperature',
                'is_active' => true,
            ],
            [
                'name' => 'Azithromycin',
                'generic_name' => 'Azithromycin',
                'drug_code' => 'AZ001',
                'category' => 'Antibiotic',
                'dosage_form' => 'Tablet',
                'strength' => '500mg',
                'indications' => 'Treatment of typhoid fever',
                'contraindications' => 'Severe liver disease',
                'side_effects' => 'Gastrointestinal upset, liver toxicity',
                'interactions' => 'Warfarin, digoxin',
                'storage_conditions' => 'Store below 30°C',
                'is_active' => true,
            ],
            [
                'name' => 'Ceftriaxone',
                'generic_name' => 'Ceftriaxone',
                'drug_code' => 'CT001',
                'category' => 'Antibiotic',
                'dosage_form' => 'Injection',
                'strength' => '1g',
                'indications' => 'Severe typhoid fever',
                'contraindications' => 'Penicillin allergy',
                'side_effects' => 'Allergic reactions, diarrhea',
                'interactions' => 'Warfarin, probenecid',
                'storage_conditions' => 'Store in refrigerator',
                'is_active' => true,
            ],

            // TB Drugs
            [
                'name' => 'Isoniazid',
                'generic_name' => 'Isoniazid',
                'drug_code' => 'INH001',
                'category' => 'Antitubercular',
                'dosage_form' => 'Tablet',
                'strength' => '300mg',
                'indications' => 'Treatment of tuberculosis',
                'contraindications' => 'Severe liver disease',
                'side_effects' => 'Hepatotoxicity, peripheral neuropathy',
                'interactions' => 'Phenytoin, warfarin',
                'storage_conditions' => 'Store below 25°C',
                'is_active' => true,
            ],
            [
                'name' => 'Rifampin',
                'generic_name' => 'Rifampin',
                'drug_code' => 'RF001',
                'category' => 'Antitubercular',
                'dosage_form' => 'Capsule',
                'strength' => '300mg',
                'indications' => 'Treatment of tuberculosis',
                'contraindications' => 'Severe liver disease',
                'side_effects' => 'Hepatotoxicity, orange discoloration of body fluids',
                'interactions' => 'Warfarin, oral contraceptives',
                'storage_conditions' => 'Store below 25°C',
                'is_active' => true,
            ],

            // HIV/AIDS Drugs
            [
                'name' => 'Tenofovir',
                'generic_name' => 'Tenofovir Disoproxil Fumarate',
                'drug_code' => 'TDF001',
                'category' => 'Antiretroviral',
                'dosage_form' => 'Tablet',
                'strength' => '300mg',
                'indications' => 'Treatment of HIV infection',
                'contraindications' => 'Severe renal impairment',
                'side_effects' => 'Renal toxicity, bone density loss',
                'interactions' => 'Acyclovir, ganciclovir',
                'storage_conditions' => 'Store below 30°C',
                'is_active' => true,
            ],

            // Diabetes Drugs
            [
                'name' => 'Metformin',
                'generic_name' => 'Metformin',
                'drug_code' => 'MF001',
                'category' => 'Antidiabetic',
                'dosage_form' => 'Tablet',
                'strength' => '500mg',
                'indications' => 'Treatment of type 2 diabetes',
                'contraindications' => 'Severe renal impairment',
                'side_effects' => 'Lactic acidosis, gastrointestinal upset',
                'interactions' => 'Contrast media, alcohol',
                'storage_conditions' => 'Store below 30°C',
                'is_active' => true,
            ],

            // Mental Health Drugs
            [
                'name' => 'Fluoxetine',
                'generic_name' => 'Fluoxetine',
                'drug_code' => 'FL001',
                'category' => 'Antidepressant',
                'dosage_form' => 'Capsule',
                'strength' => '20mg',
                'indications' => 'Treatment of depression and anxiety',
                'contraindications' => 'MAO inhibitors, pregnancy',
                'side_effects' => 'Nausea, insomnia, sexual dysfunction',
                'interactions' => 'Warfarin, MAO inhibitors',
                'storage_conditions' => 'Store below 25°C',
                'is_active' => true,
            ],
        ];

        foreach ($drugs as $drug) {
            Drug::firstOrCreate(
                ['drug_code' => $drug['drug_code']],
                $drug
            );
        }
    }

    private function createPharmacyData(): void
    {
        // Get a pharmacist user
        $pharmacist = User::role('pharmacist')->first();
        
        $pharmacies = [
            [
                'name' => 'Central Medical Pharmacy',
                'description' => 'Main hospital pharmacy providing comprehensive medication services',
                'address' => 'Main Hospital Building, Ground Floor',
                'phone' => '+1234567890',
                'email' => 'pharmacy@mesmtf.com',
                'license_number' => 'PH001',
                'pharmacist_id' => $pharmacist ? $pharmacist->id : null,
                'operating_hours' => '24/7',
                'is_active' => true,
            ],
            [
                'name' => 'Emergency Pharmacy',
                'description' => 'Emergency department pharmacy for urgent medication needs',
                'address' => 'Emergency Department, Level 1',
                'phone' => '+1234567891',
                'email' => 'emergency.pharmacy@mesmtf.com',
                'license_number' => 'PH002',
                'pharmacist_id' => $pharmacist ? $pharmacist->id : null,
                'operating_hours' => '24/7',
                'is_active' => true,
            ],
        ];

        foreach ($pharmacies as $pharmacy) {
            Pharmacy::firstOrCreate(
                ['name' => $pharmacy['name']],
                $pharmacy
            );
        }
    }
}
