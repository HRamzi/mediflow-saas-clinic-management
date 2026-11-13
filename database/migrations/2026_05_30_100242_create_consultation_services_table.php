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
        Schema::create('consultation_services', function (Blueprint $table) {
            $table->uuid('id')->primary();

            $table->foreignUuid('tenant_id')
                ->references('id')
                ->on('tenants')
                ->onDelete('cascade');

            $table->foreignUuid('consultation_id')
                ->references('id')
                ->on('consultations')
                ->onDelete('cascade');

            $table->foreignUuid('invoice_id')
                ->references('id')
                ->on('invoices')
                ->onDelete('cascade');

            $table->string('service_name');

            $table->string('service_category', 100)
                ->nullable();

            $table->integer('quantity')
                ->default(1);

            $table->decimal('unit_price', 10, 2);

            $table->decimal('total_price', 10, 2);

            $table->foreignUuid('performed_by')
                ->nullable()
                ->references('id')
                ->on('staff_users')
                ->nullOnDelete();

            $table->timestamps();

            // Indexes
            $table->index('tenant_id');
            $table->index('consultation_id');
            $table->index('invoice_id');
            $table->index('service_name');
            $table->index('service_category');
            $table->index('performed_by');
            $table->index('created_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('consultation_services');
    }
};