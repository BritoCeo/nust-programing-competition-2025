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
        Schema::create('symptoms', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('description')->nullable();
            $table->string('category')->nullable(); // e.g., 'fever', 'respiratory', 'gastrointestinal'
            $table->enum('severity_level', ['mild', 'moderate', 'severe', 'critical'])->default('moderate');
            $table->boolean('is_common')->default(false);
            $table->json('related_symptoms')->nullable(); // JSON array of related symptom IDs
            $table->timestamps();
            
            $table->index(['category', 'is_common']);
            $table->index('name');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('symptoms');
    }
};
