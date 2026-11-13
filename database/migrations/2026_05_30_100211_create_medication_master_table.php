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
        Schema::create('medication_master', function (Blueprint $table) {
            $table->uuid('id')->primary();

            $table->string('name')->unique();

            $table->string('category');

            $table->enum('unit_of_measure', [
                'tablet',
                'capsule',
                'vial',
                'ampoule',
                'bottle',
                'syrup',
                'cream',
                'ointment',
                'inhaler',
                'patch',
                'drop',
                'suppository',
                'injection',
            ]);

            $table->boolean('is_controlled_substance')->default(false);

            $table->boolean('is_active')->default(true);

            $table->timestamps();

            // Indexes
            $table->index('category');
            $table->index('unit_of_measure');
            $table->index('is_controlled_substance');
            $table->index('is_active');
            $table->index('created_at');
            $table->index('updated_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('medication_master');
    }
};