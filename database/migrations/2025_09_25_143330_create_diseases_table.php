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
        Schema::create('diseases', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('description')->nullable();
            $table->string('icd10_code')->nullable();
            $table->string('category')->nullable(); // e.g., 'infectious', 'chronic', 'acute'
            $table->text('symptoms')->nullable(); // JSON array of associated symptoms
            $table->text('treatment_guidelines')->nullable();
            $table->text('prevention_measures')->nullable();
            $table->boolean('requires_xray')->default(false);
            $table->boolean('is_contagious')->default(false);
            $table->timestamps();
            
            $table->index(['category', 'name']);
            $table->index('icd10_code');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('diseases');
    }
};
