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
        Schema::create('expert_system_rules', function (Blueprint $table) {
            $table->id();
            $table->foreignId('disease_id')->constrained('diseases')->onDelete('cascade');
            $table->json('required_symptoms'); // JSON array of required symptom IDs
            $table->json('optional_symptoms')->nullable(); // JSON array of optional symptom IDs
            $table->integer('min_symptoms_required')->default(1);
            $table->enum('confidence_level', ['very_strong', 'strong', 'weak', 'very_weak']);
            $table->integer('priority_order')->default(1);
            $table->text('rule_description')->nullable();
            $table->boolean('requires_xray')->default(false);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            
            $table->index(['disease_id', 'confidence_level']);
            $table->index(['is_active', 'priority_order']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('expert_system_rules');
    }
};
