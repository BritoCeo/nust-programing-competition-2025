<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\ExpertSystemRule;
use App\Models\Disease;
use App\Models\Symptom;

class Table1ExpertSystemRulesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     * This implements the exact expert system rules from Table 1 requirements
     */
    public function run(): void
    {
        // Clear existing rules to avoid conflicts
        ExpertSystemRule::query()->delete();

        $malaria = Disease::where('name', 'Malaria')->first();
        $typhoid = Disease::where('name', 'Typhoid Fever')->first();

        if (!$malaria || !$typhoid) {
            $this->command->error('Diseases not found. Please run the disease seeder first.');
            return;
        }

        // Get symptoms by strength level
        $symptoms = Symptom::all()->keyBy('name');

        // MALARIA EXPERT SYSTEM RULES (Table 1)

        // Rule 1: Very Strong Signs (VSs) - Requires X-ray
        ExpertSystemRule::create([
            'disease_id' => $malaria->id,
            'required_symptoms' => [
                $symptoms['Abdominal Pain']->id,
                $symptoms['Vomiting']->id,
                $symptoms['Sore Throat']->id
            ],
            'optional_symptoms' => [],
            'min_symptoms_required' => 2, // At least 2 VSs symptoms
            'confidence_level' => 'very_strong',
            'priority_order' => 1,
            'rule_description' => 'Very Strong Signs (VSs) of Malaria - requires chest X-ray in addition to drug administration',
            'requires_xray' => true,
            'is_active' => true,
        ]);

        // Rule 2: Strong Signs (Ss) - Drug administration only
        ExpertSystemRule::create([
            'disease_id' => $malaria->id,
            'required_symptoms' => [
                $symptoms['Headache']->id,
                $symptoms['Fatigue']->id,
                $symptoms['Cough']->id,
                $symptoms['Constipation']->id
            ],
            'optional_symptoms' => [],
            'min_symptoms_required' => 2, // At least 2 Ss symptoms
            'confidence_level' => 'strong',
            'priority_order' => 2,
            'rule_description' => 'Strong Signs (Ss) of Malaria - drug administration only (no chest X-ray)',
            'requires_xray' => false,
            'is_active' => true,
        ]);

        // Rule 3: Weak Signs (Ws) - Drug administration only
        ExpertSystemRule::create([
            'disease_id' => $malaria->id,
            'required_symptoms' => [
                $symptoms['Chest Pain']->id,
                $symptoms['Back Pain']->id,
                $symptoms['Muscle Pain']->id
            ],
            'optional_symptoms' => [],
            'min_symptoms_required' => 1, // At least 1 Ws symptom
            'confidence_level' => 'weak',
            'priority_order' => 3,
            'rule_description' => 'Weak Signs (Ws) of Malaria - drug administration only (no chest X-ray)',
            'requires_xray' => false,
            'is_active' => true,
        ]);

        // Rule 4: Very Weak Signs (VWs) - Drug administration only
        ExpertSystemRule::create([
            'disease_id' => $malaria->id,
            'required_symptoms' => [
                $symptoms['Diarrhea']->id,
                $symptoms['Sweating']->id,
                $symptoms['Rash']->id,
                $symptoms['Loss of Appetite']->id
            ],
            'optional_symptoms' => [],
            'min_symptoms_required' => 2, // At least 2 VWs symptoms
            'confidence_level' => 'very_weak',
            'priority_order' => 4,
            'rule_description' => 'Very Weak Signs (VWs) of Malaria - drug administration only (no chest X-ray)',
            'requires_xray' => false,
            'is_active' => true,
        ]);

        // TYPHOID EXPERT SYSTEM RULES (Table 1)

        // Rule 5: Very Strong Signs (VSs) - Requires X-ray
        ExpertSystemRule::create([
            'disease_id' => $typhoid->id,
            'required_symptoms' => [
                $symptoms['Abdominal Pain']->id,
                $symptoms['Stomach Issues']->id
            ],
            'optional_symptoms' => [],
            'min_symptoms_required' => 2, // Both VSs symptoms
            'confidence_level' => 'very_strong',
            'priority_order' => 1,
            'rule_description' => 'Very Strong Signs (VSs) of Typhoid Fever - requires chest X-ray in addition to drug administration',
            'requires_xray' => true,
            'is_active' => true,
        ]);

        // Rule 6: Strong Signs (Ss) - Drug administration only
        ExpertSystemRule::create([
            'disease_id' => $typhoid->id,
            'required_symptoms' => [
                $symptoms['Headache']->id,
                $symptoms['Persistent High Fever']->id
            ],
            'optional_symptoms' => [],
            'min_symptoms_required' => 2, // Both Ss symptoms
            'confidence_level' => 'strong',
            'priority_order' => 2,
            'rule_description' => 'Strong Signs (Ss) of Typhoid Fever - drug administration only (no chest X-ray)',
            'requires_xray' => false,
            'is_active' => true,
        ]);

        // Rule 7: Weak Signs (Ws) - Drug administration only
        ExpertSystemRule::create([
            'disease_id' => $typhoid->id,
            'required_symptoms' => [
                $symptoms['Weakness']->id,
                $symptoms['Tiredness']->id
            ],
            'optional_symptoms' => [],
            'min_symptoms_required' => 1, // At least 1 Ws symptom
            'confidence_level' => 'weak',
            'priority_order' => 3,
            'rule_description' => 'Weak Signs (Ws) of Typhoid Fever - drug administration only (no chest X-ray)',
            'requires_xray' => false,
            'is_active' => true,
        ]);

        // Rule 8: Very Weak Signs (VWs) - Drug administration only
        ExpertSystemRule::create([
            'disease_id' => $typhoid->id,
            'required_symptoms' => [
                $symptoms['Rash']->id,
                $symptoms['Loss of Appetite']->id
            ],
            'optional_symptoms' => [],
            'min_symptoms_required' => 1, // At least 1 VWs symptom
            'confidence_level' => 'very_weak',
            'priority_order' => 4,
            'rule_description' => 'Very Weak Signs (VWs) of Typhoid Fever - drug administration only (no chest X-ray)',
            'requires_xray' => false,
            'is_active' => true,
        ]);

        // COMBINED MALARIA + TYPHOID RULES
        // Rule 9: Combined VSs symptoms - Requires X-ray
        ExpertSystemRule::create([
            'disease_id' => $malaria->id, // Primary disease
            'required_symptoms' => [
                $symptoms['Abdominal Pain']->id,
                $symptoms['Vomiting']->id,
                $symptoms['Stomach Issues']->id
            ],
            'optional_symptoms' => [
                $symptoms['Sore Throat']->id
            ],
            'min_symptoms_required' => 3, // At least 3 VSs symptoms
            'confidence_level' => 'very_strong',
            'priority_order' => 1,
            'rule_description' => 'Combined Very Strong Signs (VSs) of Malaria and Typhoid - requires chest X-ray in addition to drug administration',
            'requires_xray' => true,
            'is_active' => true,
        ]);

        // Rule 10: Combined Ss symptoms - Drug administration only
        ExpertSystemRule::create([
            'disease_id' => $malaria->id, // Primary disease
            'required_symptoms' => [
                $symptoms['Headache']->id,
                $symptoms['Fatigue']->id,
                $symptoms['Persistent High Fever']->id
            ],
            'optional_symptoms' => [
                $symptoms['Cough']->id,
                $symptoms['Constipation']->id
            ],
            'min_symptoms_required' => 3, // At least 3 Ss symptoms
            'confidence_level' => 'strong',
            'priority_order' => 2,
            'rule_description' => 'Combined Strong Signs (Ss) of Malaria and Typhoid - drug administration only (no chest X-ray)',
            'requires_xray' => false,
            'is_active' => true,
        ]);

        $this->command->info('Table 1 Expert System Rules seeded successfully!');
        $this->command->info('Total rules created: ' . ExpertSystemRule::count());
        
        // Display summary
        $this->command->info('Rule Classification Summary:');
        $this->command->info('Malaria Rules: ' . ExpertSystemRule::where('disease_id', $malaria->id)->count());
        $this->command->info('Typhoid Rules: ' . ExpertSystemRule::where('disease_id', $typhoid->id)->count());
        $this->command->info('Rules requiring X-ray: ' . ExpertSystemRule::where('requires_xray', true)->count());
        $this->command->info('Rules for drug administration only: ' . ExpertSystemRule::where('requires_xray', false)->count());
    }
}
