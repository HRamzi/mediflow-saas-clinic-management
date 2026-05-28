<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Tenant;

class TenantSeeder extends Seeder
{
    /**
     * Seed the tenants table.
     *
     * This seeder creates the primary tenant (organization)
     * that owns clinics, staff, and all related resources.
     *
     * It uses updateOrCreate to ensure idempotency:
     * - Prevents duplicate tenants on repeated seeding
     * - Keeps tenant data up to date if modified
     *
     * Assumptions:
     * - Tenant email is globally unique
     * - This represents a root or initial tenant for the system
     *
     * @return void
     */
    public function run(): void
    {
        /**
         * Create or update the main tenant record.
         *
         * Matching criteria:
         * - email (acts as a unique identifier)
         *
         * Behavior:
         * - If tenant exists → update its data
         * - Otherwise → create a new tenant
         */
        Tenant::updateOrCreate(
            [
                'email' => 'contact@cliniquebenali.dz',
            ],
            [
                'name' => 'Clinique Benali',
                'owner_name' => 'Dr. Karim Benali',
                'phone' => '+213550000000',
                'country' => 'Algeria',
                'status' => 'active',
                'subscription_plan' => 'professional',
            ]
        );
    }
}