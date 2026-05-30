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
        Schema::create('pharmacy_stocks', function (Blueprint $table) {
            $table->uuid('id')->primary();

            $table->foreignUuid('tenant_id')
                ->references('id')
                ->on('tenants')
                ->onDelete('cascade');

            $table->foreignUuid('clinic_id')
                ->references('id')
                ->on('clinics')
                ->onDelete('cascade');

            $table->foreignUuid('medication_id')
                ->references('id')
                ->on('medication_master')
                ->onDelete('restrict');

            $table->integer('current_quantity');

            $table->integer('low_stock_threshold');

            $table->date('expiry_date');

            $table->boolean('is_expired')
                ->default(false);

            $table->string('supplier_name')
                ->nullable();

            $table->dateTime('last_restocked_at')
                ->nullable();

            $table->timestamps();

            // Indexes
            $table->index('tenant_id');
            $table->index('clinic_id');
            $table->index('medication_id');
            $table->index('expiry_date');
            $table->index('is_expired');
            $table->index('current_quantity');
            $table->index('low_stock_threshold');
            $table->index('updated_at');

            // Unique Constraints
            $table->unique(
                ['tenant_id', 'clinic_id', 'medication_id', 'expiry_date'],
                'pharmacy_stocks_unique'
            );
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pharmacy_stocks');
    }
};
