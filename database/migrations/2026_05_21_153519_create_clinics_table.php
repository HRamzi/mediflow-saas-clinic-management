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
        Schema::create('clinics', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('tenant_id')->references('id')->on('tenants')->onDelete('cascade');
            $table->string('name', 100);
            $table->string('city', 100);
            $table->text('address');
            $table->string('phone_number', 20);
            $table->string('email', 255);
            $table->time('operating_hours_start');
            $table->time('operating_hours_end');
            $table->integer('min_receptionists_per_shift');
            $table->integer('min_nurses_per_shift');
            $table->integer('min_doctors_per_shift');
            $table->enum('status', ['active', 'inactive'])->default('active');
            $table->timestamps();

            // indexes
            $table->index('tenant_id');
            $table->index('status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('clinics');
    }
};
