<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Drug;
use App\Models\Pharmacy;

class Table1DrugAdministrationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     * This implements the drug administration requirements from Table 1
     */
    public function run(): void
    {
        // Clear existing drugs to avoid duplicates
        Drug::query()->delete();

        // MALARIA DRUGS (Table 1 Requirements)
        $malariaDrugs = [
            [
                'name' => 'Artemether-Lumefantrine',
                'generic_name' => 'Artemether-Lumefantrine',
                'drug_code' => 'AL001',
                'category' => 'Antimalarial',
                'dosage_form' => 'Tablet',
                'strength' => '20mg/120mg',
                'indications' => 'Treatment of uncomplicated malaria caused by Plasmodium falciparum',
                'contraindications' => 'Pregnancy (first trimester), severe malaria, hypersensitivity to artemether or lumefantrine',
                'side_effects' => 'Nausea, vomiting, dizziness, headache, abdominal pain, diarrhea',
                'interactions' => 'Warfarin, anticonvulsants, antiretroviral drugs',
                'storage_conditions' => 'Store below 30°C, protect from light and moisture',
                'administration_route' => 'Oral',
                'dosage_adult' => '4 tablets twice daily for 3 days',
                'dosage_child' => 'Weight-based dosing: 5-14kg: 1 tablet, 15-24kg: 2 tablets, 25-34kg: 3 tablets, ≥35kg: 4 tablets',
                'dosage_elderly' => 'Same as adult, monitor for adverse effects',
                'treatment_duration' => '3 days',
                'is_active' => true,
            ],
            [
                'name' => 'Chloroquine',
                'generic_name' => 'Chloroquine Phosphate',
                'drug_code' => 'CQ001',
                'category' => 'Antimalarial',
                'dosage_form' => 'Tablet',
                'strength' => '250mg',
                'indications' => 'Treatment and prevention of malaria caused by Plasmodium vivax, ovale, malariae',
                'contraindications' => 'Retinal disease, porphyria, psoriasis, hypersensitivity to chloroquine',
                'side_effects' => 'Retinal damage, gastrointestinal upset, headache, dizziness, skin rash',
                'interactions' => 'Digoxin, antacids, kaolin, cimetidine',
                'storage_conditions' => 'Store at room temperature, protect from light',
                'administration_route' => 'Oral',
                'dosage_adult' => '600mg base (4 tablets) immediately, then 300mg base (2 tablets) at 6, 24, and 48 hours',
                'dosage_child' => '10mg base/kg immediately, then 5mg base/kg at 6, 24, and 48 hours',
                'dosage_elderly' => 'Same as adult, monitor for retinal toxicity',
                'treatment_duration' => '3 days',
                'is_active' => true,
            ],
            [
                'name' => 'Quinine',
                'generic_name' => 'Quinine Sulfate',
                'drug_code' => 'QN001',
                'category' => 'Antimalarial',
                'dosage_form' => 'Tablet',
                'strength' => '300mg',
                'indications' => 'Treatment of severe malaria, chloroquine-resistant malaria',
                'contraindications' => 'G6PD deficiency, optic neuritis, hypersensitivity to quinine',
                'side_effects' => 'Cinchonism (tinnitus, headache, nausea), hypoglycemia, cardiac arrhythmias',
                'interactions' => 'Digoxin, warfarin, antacids, cimetidine',
                'storage_conditions' => 'Store below 25°C, protect from light',
                'administration_route' => 'Oral',
                'dosage_adult' => '600mg every 8 hours for 7 days',
                'dosage_child' => '10mg/kg every 8 hours for 7 days',
                'dosage_elderly' => 'Same as adult, monitor for cinchonism',
                'treatment_duration' => '7 days',
                'is_active' => true,
            ],
        ];

        // TYPHOID DRUGS (Table 1 Requirements)
        $typhoidDrugs = [
            [
                'name' => 'Ciprofloxacin',
                'generic_name' => 'Ciprofloxacin',
                'drug_code' => 'CF001',
                'category' => 'Antibiotic',
                'dosage_form' => 'Tablet',
                'strength' => '500mg',
                'indications' => 'Treatment of typhoid fever caused by Salmonella typhi',
                'contraindications' => 'Pregnancy, children under 18, hypersensitivity to fluoroquinolones',
                'side_effects' => 'Tendon rupture, photosensitivity, nausea, diarrhea, headache',
                'interactions' => 'Warfarin, theophylline, antacids, iron supplements',
                'storage_conditions' => 'Store at room temperature, protect from light',
                'administration_route' => 'Oral',
                'dosage_adult' => '500mg twice daily for 10-14 days',
                'dosage_child' => 'Not recommended for children under 18',
                'dosage_elderly' => 'Same as adult, monitor for tendon problems',
                'treatment_duration' => '10-14 days',
                'is_active' => true,
            ],
            [
                'name' => 'Azithromycin',
                'generic_name' => 'Azithromycin',
                'drug_code' => 'AZ001',
                'category' => 'Antibiotic',
                'dosage_form' => 'Tablet',
                'strength' => '500mg',
                'indications' => 'Treatment of typhoid fever, especially in children and pregnant women',
                'contraindications' => 'Severe liver disease, hypersensitivity to macrolides',
                'side_effects' => 'Gastrointestinal upset, liver toxicity, allergic reactions',
                'interactions' => 'Warfarin, digoxin, antacids',
                'storage_conditions' => 'Store below 30°C',
                'administration_route' => 'Oral',
                'dosage_adult' => '500mg once daily for 7 days',
                'dosage_child' => '10mg/kg once daily for 7 days',
                'dosage_elderly' => 'Same as adult, monitor liver function',
                'treatment_duration' => '7 days',
                'is_active' => true,
            ],
            [
                'name' => 'Ceftriaxone',
                'generic_name' => 'Ceftriaxone',
                'drug_code' => 'CT001',
                'category' => 'Antibiotic',
                'dosage_form' => 'Injection',
                'strength' => '1g',
                'indications' => 'Severe typhoid fever, hospital treatment',
                'contraindications' => 'Penicillin allergy, severe liver disease',
                'side_effects' => 'Allergic reactions, diarrhea, injection site reactions',
                'interactions' => 'Warfarin, probenecid, calcium-containing solutions',
                'storage_conditions' => 'Store in refrigerator, protect from light',
                'administration_route' => 'Intravenous/Intramuscular',
                'dosage_adult' => '2g once daily for 7-10 days',
                'dosage_child' => '50-75mg/kg once daily for 7-10 days',
                'dosage_elderly' => 'Same as adult, monitor renal function',
                'treatment_duration' => '7-10 days',
                'is_active' => true,
            ],
        ];

        // COMBINED MALARIA + TYPHOID DRUGS
        $combinedDrugs = [
            [
                'name' => 'Artemether-Lumefantrine + Ciprofloxacin',
                'generic_name' => 'Combined Antimalarial and Antibiotic',
                'drug_code' => 'ALCF001',
                'category' => 'Combined Therapy',
                'dosage_form' => 'Tablet',
                'strength' => '20mg/120mg + 500mg',
                'indications' => 'Treatment of combined malaria and typhoid fever',
                'contraindications' => 'Pregnancy (first trimester), severe liver disease, hypersensitivity to any component',
                'side_effects' => 'Nausea, vomiting, dizziness, photosensitivity, tendon problems',
                'interactions' => 'Warfarin, anticonvulsants, antacids, iron supplements',
                'storage_conditions' => 'Store below 30°C, protect from light',
                'administration_route' => 'Oral',
                'dosage_adult' => 'Artemether-Lumefantrine: 4 tablets twice daily for 3 days; Ciprofloxacin: 500mg twice daily for 10 days',
                'dosage_child' => 'Weight-based dosing for both drugs',
                'dosage_elderly' => 'Same as adult, monitor for adverse effects',
                'treatment_duration' => '10 days (continue ciprofloxacin after artemether-lumefantrine)',
                'is_active' => true,
            ],
        ];

        // Combine all drugs
        $allDrugs = array_merge($malariaDrugs, $typhoidDrugs, $combinedDrugs);

        // Create drugs in database
        foreach ($allDrugs as $drug) {
            Drug::create($drug);
        }

        $this->command->info('Table 1 Drug Administration seeded successfully!');
        $this->command->info('Total drugs created: ' . count($allDrugs));
        
        // Display summary
        $this->command->info('Drug Classification Summary:');
        $this->command->info('Malaria Drugs: ' . count($malariaDrugs));
        $this->command->info('Typhoid Drugs: ' . count($typhoidDrugs));
        $this->command->info('Combined Therapy Drugs: ' . count($combinedDrugs));
        
        // Display drug categories
        $categories = Drug::distinct()->pluck('category');
        $this->command->info('Drug Categories: ' . $categories->implode(', '));
    }
}
