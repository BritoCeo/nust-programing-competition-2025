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
        Schema::create('drug_administrations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('patient_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('treatment_id')->constrained('treatments')->onDelete('cascade');
            $table->foreignId('pharmacy_id')->constrained('pharmacies')->onDelete('cascade');
            $table->string('drug_name');
            $table->string('drug_code')->nullable(); // Generic or brand code
            $table->text('dosage');
            $table->string('frequency'); // e.g., 'twice daily', 'every 8 hours'
            $table->integer('quantity_prescribed');
            $table->integer('quantity_dispensed')->default(0);
            $table->date('prescription_date');
            $table->date('dispense_date')->nullable();
            $table->date('expiry_date')->nullable();
            $table->text('administration_instructions')->nullable();
            $table->text('side_effects_notes')->nullable();
            $table->enum('status', ['prescribed', 'dispensed', 'in_progress', 'completed', 'discontinued'])->default('prescribed');
            $table->foreignId('prescribed_by')->constrained('users')->onDelete('cascade');
            $table->foreignId('dispensed_by')->nullable()->constrained('users')->onDelete('cascade');
            $table->timestamps();
            
            $table->index(['patient_id', 'prescription_date']);
            $table->index(['pharmacy_id', 'dispense_date']);
            $table->index(['status', 'prescription_date']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('drug_administrations');
    }
};
