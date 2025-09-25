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
        Schema::create('treatments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('patient_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('doctor_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('diagnosis_id')->constrained('diagnoses')->onDelete('cascade');
            $table->string('treatment_plan')->nullable();
            $table->text('medications')->nullable(); // JSON array of prescribed medications
            $table->text('dosage_instructions')->nullable();
            $table->text('lifestyle_recommendations')->nullable();
            $table->text('follow_up_instructions')->nullable();
            $table->date('start_date');
            $table->date('end_date')->nullable();
            $table->enum('status', ['active', 'completed', 'discontinued', 'modified'])->default('active');
            $table->text('notes')->nullable();
            $table->timestamps();
            
            $table->index(['patient_id', 'start_date']);
            $table->index(['doctor_id', 'start_date']);
            $table->index(['diagnosis_id', 'status']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('treatments');
    }
};
