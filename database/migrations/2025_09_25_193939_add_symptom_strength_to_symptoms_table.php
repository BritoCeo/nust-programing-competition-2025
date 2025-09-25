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
        Schema::table('symptoms', function (Blueprint $table) {
            $table->enum('symptom_strength', ['very_strong', 'strong', 'weak', 'very_weak'])->default('weak')->after('is_common');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('symptoms', function (Blueprint $table) {
            $table->dropColumn('symptom_strength');
        });
    }
};