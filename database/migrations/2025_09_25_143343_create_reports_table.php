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
        Schema::create('reports', function (Blueprint $table) {
            $table->id();
            $table->string('report_name');
            $table->enum('report_type', ['patient_summary', 'diagnosis_report', 'treatment_report', 'appointment_report', 'pharmacy_report', 'expert_system_report', 'custom'])->default('custom');
            $table->text('description')->nullable();
            $table->json('report_data')->nullable(); // JSON data for the report
            $table->json('filters')->nullable(); // JSON filters applied to generate report
            $table->date('report_date');
            $table->date('period_start')->nullable();
            $table->date('period_end')->nullable();
            $table->foreignId('generated_by')->constrained('users')->onDelete('cascade');
            $table->string('file_path')->nullable(); // Path to generated report file
            $table->enum('status', ['generating', 'completed', 'failed', 'archived'])->default('generating');
            $table->timestamps();
            
            $table->index(['report_type', 'report_date']);
            $table->index(['generated_by', 'report_date']);
            $table->index(['status', 'report_date']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reports');
    }
};
