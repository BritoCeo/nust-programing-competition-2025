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
        Schema::table('drugs', function (Blueprint $table) {
            $table->string('administration_route')->nullable()->after('storage_conditions');
            $table->text('dosage_adult')->nullable()->after('administration_route');
            $table->text('dosage_child')->nullable()->after('dosage_adult');
            $table->text('dosage_elderly')->nullable()->after('dosage_child');
            $table->string('treatment_duration')->nullable()->after('dosage_elderly');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('drugs', function (Blueprint $table) {
            $table->dropColumn([
                'administration_route',
                'dosage_adult',
                'dosage_child',
                'dosage_elderly',
                'treatment_duration'
            ]);
        });
    }
};