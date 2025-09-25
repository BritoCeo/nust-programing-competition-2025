<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('medical_records', function (Blueprint $table) {
            $table->id();
            $table->foreignId('patient_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('doctor_id')->constrained('users')->onDelete('cascade');
            $table->string('record_number')->unique();
            $table->date('visit_date');
            $table->text('chief_complaint')->nullable();
            $table->text('history_of_present_illness')->nullable();
            $table->text('past_medical_history')->nullable();
            $table->text('family_history')->nullable();
            $table->text('social_history')->nullable();
            $table->text('vital_signs')->nullable(); // JSON: blood_pressure, temperature, pulse, etc.
            $table->text('physical_examination')->nullable();
            $table->text('assessment')->nullable();
            $table->text('plan')->nullable();
            $table->json('attachments')->nullable(); // File paths for medical documents
            $table->enum('status', ['active', 'archived', 'deleted'])->default('active');
            $table->timestamps();
            
            $table->index(['patient_id', 'visit_date']);
            $table->index(['doctor_id', 'visit_date']);
            $table->index('record_number');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('medical_records');
    }
};
