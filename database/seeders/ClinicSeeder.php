<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Tenant;
use App\Models\Clinic;

class ClinicSeeder extends Seeder
{
    /**
     * Seed clinics for all tenants.
     *
     * This seeder loops through every tenant and ensures that
     * a predefined set of clinics exists for each one.
     *
     * It uses updateOrCreate to guarantee idempotency:
     * - No duplicate clinics will be created on re-run
     * - Existing records will be updated if data changes
     *
     * Assumptions:
     * - Tenants already exist in the database
     * - The combination of (tenant_id, email) is unique
     *
     * @return void
     */
    public function run(): void
    {
        /**
         * Retrieve all tenants.
         *
         */
        $tenants = Tenant::all();

        foreach ($tenants as $tenant) {

            /**
             * Static clinic definitions.
             *
             * These represent the default clinics that should exist
             * for every tenant in the system.
             */
            $clinics = [
                [
                    'name' => 'Clinique Benali Algiers',
                    'city' => 'Algiers',
                    'address' => '12 Rue Didouche Mourad, Algiers',
                    'phone_number' => '+213550000001',
                    'email' => 'algiers@cliniquebenali.dz',
                    'operating_hours_start' => '08:00:00',
                    'operating_hours_end' => '18:00:00',
                    'min_receptionists_per_shift' => 2,
                    'min_nurses_per_shift' => 3,
                    'min_doctors_per_shift' => 2,
                    'status' => 'active'
                ],
                [
                    'name' => 'Clinique Benali Oran',
                    'city' => 'Oran',
                    'address' => '45 Boulevard Millénaire, Oran',
                    'phone_number' => '+213550000002',
                    'email' => 'oran@cliniquebenali.dz',
                    'operating_hours_start' => '08:00:00',
                    'operating_hours_end' => '18:00:00',
                    'min_receptionists_per_shift' => 2,
                    'min_nurses_per_shift' => 2,
                    'min_doctors_per_shift' => 1,
                    'status' => 'active'
                ],
                [
                    'name' => 'Clinique Benali Tlemcen',
                    'city' => 'Tlemcen',
                    'address' => '8 Rue de Fès, Tlemcen',
                    'phone_number' => '+213550000003',
                    'email' => 'tlemcen@cliniquebenali.dz',
                    'operating_hours_start' => '08:00:00',
                    'operating_hours_end' => '18:00:00',
                    'min_receptionists_per_shift' => 2,
                    'min_nurses_per_shift' => 2,
                    'min_doctors_per_shift' => 1,
                    'status' => 'active'
                ]
            ];

            foreach ($clinics as $clinicData) {

                /**
                 * Inject tenant context into clinic data.
                 *
                 * This ensures the clinic is correctly associated
                 * with the current tenant.
                 */
                $clinicData['tenant_id'] = $tenant->id;

                /**
                 * Create or update the clinic record.
                 *
                 * Matching criteria:
                 * - tenant_id
                 * - email (acts as a unique identifier per tenant)
                 *
                 * Behavior:
                 * - If a matching clinic exists → it is updated
                 * - Otherwise → a new clinic is created
                 */
                Clinic::updateOrCreate(
                    [
                        'tenant_id' => $tenant->id,
                        'email' => $clinicData['email'],
                    ],
                    $clinicData
                );
            }
        }
    }
}
