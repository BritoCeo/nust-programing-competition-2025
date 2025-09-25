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
        Schema::create('diagnoses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('patient_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('doctor_id')->constrained('users')->onDelete('cascade');
            $table->json('symptoms')->nullable();
            $table->string('diagnosis');
            $table->decimal('confidence_score', 5, 2)->default(0.00);
            $table->text('treatment_plan')->nullable();
            $table->text('notes')->nullable();
            $table->json('patient_data')->nullable();
            $table->json('analysis_data')->nullable();
            $table->enum('status', ['tentative', 'confirmed', 'rejected'])->default('tentative');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('diagnoses');
    }
};