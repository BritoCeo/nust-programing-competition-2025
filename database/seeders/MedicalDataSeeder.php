<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\MedicalRecord;
use App\Models\Appointment;
use App\Models\Treatment;
use App\Models\Pharmacy;
use App\Models\DrugAdministration;
use App\Models\Symptom;
use App\Models\Disease;
use App\Models\ExpertSystemRule;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;

class MedicalDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create sample users with roles
        $this->createUsers();
        
        // Create sample medical data
        $this->createMedicalData();
        
        // Create sample symptoms and diseases
        $this->createSymptomsAndDiseases();
        
        // Create sample expert system rules
        $this->createExpertSystemRules();
    }

    private function createUsers(): void
    {
        // Create admin user
        $admin = User::firstOrCreate([
            'email' => 'admin@mesmtf.com'
        ], [
            'name' => 'Dr. Admin',
            'email' => 'admin@mesmtf.com',
            'password' => Hash::make('password'),
            'phone' => '+1234567890',
            'address' => '123 Medical Center, City',
            'date_of_birth' => '1980-01-01',
            'gender' => 'male',
            'medical_license_number' => 'MD001',
            'specialization' => 'General Medicine',
            'is_active' => true,
        ]);
        $admin->assignRole('admin');

        // Create doctor users
        $doctor1 = User::firstOrCreate([
            'email' => 'sarah.johnson@mesmtf.com'
        ], [
            'name' => 'Dr. Sarah Johnson',
            'email' => 'sarah.johnson@mesmtf.com',
            'password' => Hash::make('password'),
            'phone' => '+1234567891',
            'address' => '456 Health Street, City',
            'date_of_birth' => '1985-05-15',
            'gender' => 'female',
            'medical_license_number' => 'MD002',
            'specialization' => 'Internal Medicine',
            'is_active' => true,
        ]);
        $doctor1->assignRole('doctor');

        // Create patient users
        $patient1 = User::firstOrCreate([
            'email' => 'john.doe@example.com'
        ], [
            'name' => 'John Doe',
            'email' => 'john.doe@example.com',
            'password' => Hash::make('password'),
            'phone' => '+1234567893',
            'address' => '321 Patient Lane, City',
            'date_of_birth' => '1990-08-10',
            'gender' => 'male',
            'is_active' => true,
        ]);
        $patient1->assignRole('patient');

        // Create pharmacist
        $pharmacist = User::firstOrCreate([
            'email' => 'lisa.wilson@mesmtf.com'
        ], [
            'name' => 'Pharm. Lisa Wilson',
            'email' => 'lisa.wilson@mesmtf.com',
            'password' => Hash::make('password'),
            'phone' => '+1234567895',
            'address' => '987 Pharmacy Street, City',
            'date_of_birth' => '1987-07-25',
            'gender' => 'female',
            'medical_license_number' => 'PH001',
            'specialization' => 'Clinical Pharmacy',
            'is_active' => true,
        ]);
        $pharmacist->assignRole('pharmacist');
    }

    private function createMedicalData(): void
    {
        $patients = User::role('patient')->get();
        $doctors = User::role('doctor')->get();

        // Create sample medical records
        foreach ($patients as $patient) {
            foreach ($doctors as $doctor) {
                MedicalRecord::create([
                    'patient_id' => $patient->id,
                    'doctor_id' => $doctor->id,
                    'record_number' => MedicalRecord::generateRecordNumber(),
                    'visit_date' => now()->subDays(rand(1, 30)),
                    'chief_complaint' => 'Fever and headache for ' . rand(1, 7) . ' days',
                    'history_of_present_illness' => 'Patient reports fever, headache, and general malaise.',
                    'past_medical_history' => 'No significant past medical history.',
                    'vital_signs' => [
                        'blood_pressure' => rand(110, 140) . '/' . rand(70, 90),
                        'temperature' => rand(98, 103) . '.' . rand(0, 9),
                        'pulse' => rand(70, 100),
                    ],
                    'physical_examination' => 'General appearance: ill-looking.',
                    'assessment' => 'Fever of unknown origin, possible viral infection.',
                    'plan' => 'Symptomatic treatment, rest, follow-up in 3 days.',
                    'status' => 'active',
                ]);
            }
        }
    }

    private function createSymptomsAndDiseases(): void
    {
        // Create symptoms
        $symptoms = [
            ['name' => 'Fever', 'description' => 'Elevated body temperature above normal', 'severity_level' => 'moderate', 'category' => 'fever', 'is_common' => true],
            ['name' => 'Headache', 'description' => 'Pain in the head or neck area', 'severity_level' => 'mild', 'category' => 'neurological', 'is_common' => true],
            ['name' => 'Nausea', 'description' => 'Feeling of sickness with inclination to vomit', 'severity_level' => 'mild', 'category' => 'gastrointestinal', 'is_common' => true],
            ['name' => 'Vomiting', 'description' => 'Forceful ejection of stomach contents', 'severity_level' => 'moderate', 'category' => 'gastrointestinal', 'is_common' => true],
            ['name' => 'Diarrhea', 'description' => 'Frequent loose or liquid bowel movements', 'severity_level' => 'moderate', 'category' => 'gastrointestinal', 'is_common' => true],
        ];

        foreach ($symptoms as $symptom) {
            Symptom::create($symptom);
        }

        // Create diseases
        $diseases = [
            [
                'name' => 'Malaria',
                'description' => 'A life-threatening disease caused by parasites transmitted through mosquito bites',
                'icd10_code' => 'B50',
                'treatment_guidelines' => 'Antimalarial medications, supportive care, prevention measures'
            ],
            [
                'name' => 'Typhoid Fever',
                'description' => 'A bacterial infection caused by Salmonella typhi',
                'icd10_code' => 'A01.0',
                'treatment_guidelines' => 'Antibiotic therapy, supportive care, fluid replacement'
            ],
        ];

        foreach ($diseases as $disease) {
            Disease::create($disease);
        }
    }

    private function createExpertSystemRules(): void
    {
        $malaria = Disease::where('name', 'Malaria')->first();
        $typhoid = Disease::where('name', 'Typhoid Fever')->first();
        
        $fever = Symptom::where('name', 'Fever')->first();
        $headache = Symptom::where('name', 'Headache')->first();
        $nausea = Symptom::where('name', 'Nausea')->first();
        $vomiting = Symptom::where('name', 'Vomiting')->first();
        $diarrhea = Symptom::where('name', 'Diarrhea')->first();

        // Malaria - High Confidence Rule
        ExpertSystemRule::create([
            'disease_id' => $malaria->id,
            'required_symptoms' => [$fever->id, $headache->id],
            'optional_symptoms' => [$nausea->id, $vomiting->id],
            'min_symptoms_required' => 2,
            'confidence_level' => 'very_strong',
            'priority_order' => 1,
            'rule_description' => 'High probability of malaria based on fever and headache with possible gastrointestinal symptoms',
            'requires_xray' => false,
            'is_active' => true,
        ]);

        // Malaria - Moderate Confidence Rule
        ExpertSystemRule::create([
            'disease_id' => $malaria->id,
            'required_symptoms' => [$fever->id],
            'optional_symptoms' => [$headache->id, $nausea->id],
            'min_symptoms_required' => 1,
            'confidence_level' => 'strong',
            'priority_order' => 2,
            'rule_description' => 'Moderate probability of malaria based on fever with additional symptoms',
            'requires_xray' => false,
            'is_active' => true,
        ]);

        // Typhoid Fever - High Confidence Rule
        ExpertSystemRule::create([
            'disease_id' => $typhoid->id,
            'required_symptoms' => [$fever->id, $nausea->id],
            'optional_symptoms' => [$vomiting->id, $diarrhea->id],
            'min_symptoms_required' => 2,
            'confidence_level' => 'very_strong',
            'priority_order' => 1,
            'rule_description' => 'High probability of typhoid fever based on fever and nausea with gastrointestinal symptoms',
            'requires_xray' => false,
            'is_active' => true,
        ]);

        // Typhoid Fever - Moderate Confidence Rule
        ExpertSystemRule::create([
            'disease_id' => $typhoid->id,
            'required_symptoms' => [$fever->id],
            'optional_symptoms' => [$nausea->id, $vomiting->id, $diarrhea->id],
            'min_symptoms_required' => 1,
            'confidence_level' => 'strong',
            'priority_order' => 2,
            'rule_description' => 'Moderate probability of typhoid fever based on fever with gastrointestinal symptoms',
            'requires_xray' => false,
            'is_active' => true,
        ]);
    }
}
