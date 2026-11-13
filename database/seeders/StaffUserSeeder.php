<?php

namespace Database\Seeders;

use App\Models\Clinic;
use App\Models\StaffUser;
use App\Models\Tenant;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class StaffUserSeeder extends Seeder
{
    /**
     * Seed default staff users for each tenant.
     *
     * Creates:
     * - One owner
     * - One doctor
     * - One receptionist
     *
     * Updates existing records when a matching tenant/email
     * combination already exists.
     */
    public function run(): void
    {
        /**
         * Retrieve all tenants.
         */
        $tenants = Tenant::all();

        foreach ($tenants as $tenant) {

            /**
             * Retrieve clinics for the current tenant and
             * index them by city for easy access.
             */
            $clinics = Clinic::withoutGlobalScopes()
                ->where('tenant_id', $tenant->id)
                ->get()
                ->keyBy('city');

            $algiers = $clinics->get('Algiers');
            $oran = $clinics->get('Oran');

            /**
             * Ensure required clinics exist before seeding staff.
             */
            if (!$algiers || !$oran) {
                throw new \Exception(
                    "Required clinics (Algiers and Oran) not found for tenant {$tenant->name}"
                );
            }

            /**
             * Default attributes applied to all staff users.
             */
            $defaults = [
                'tenant_id' => $tenant->id,
                'is_active' => true,
                'revoked_at' => null,
                'revoked_by_user_id' => null,
                'last_login_at' => null,
            ];

            /**
             * Staff users to seed for the current tenant.
             */
            $staffDefinitions = [
                [
                    'home_clinic_id' => $algiers->id,
                    'first_name' => 'Karim',
                    'last_name' => 'Benali',
                    'phone_number' => '+213550000010',
                    'email' => 'karim@cliniquebenali.dz',
                    'password_hash' => Hash::make('Admin1234'),
                    'role' => 'owner',
                    'specialization' => null,
                ],

                [
                    'home_clinic_id' => $algiers->id,
                    'first_name' => 'Ahmed',
                    'last_name' => 'Meziane',
                    'phone_number' => '+213550000011',
                    'email' => 'ahmed.meziane@cliniquebenali.dz',
                    'password_hash' => Hash::make('Doctor1234'),
                    'role' => 'doctor',
                    'specialization' => 'General Medicine',
                ],

                [
                    'home_clinic_id' => $oran->id,
                    'first_name' => 'Samira',
                    'last_name' => 'Hadj',
                    'phone_number' => '+213550000012',
                    'email' => 'samira.hadj@cliniquebenali.dz',
                    'password_hash' => Hash::make('Recep1234'),
                    'role' => 'receptionist',
                    'specialization' => null,
                ],
            ];

            foreach ($staffDefinitions as $staffUserData) {

                /**
                 * Merge staff-specific data with default attributes.
                 */
                $staffUserData = array_merge(
                    $defaults,
                    $staffUserData
                );

                /**
                 * Create the staff user if it does not exist,
                 * otherwise update the existing record.
                 */
                StaffUser::updateOrCreate(
                    [
                        'tenant_id' => $tenant->id,
                        'email' => $staffUserData['email'],
                    ],
                    $staffUserData
                );
            }
        }
    }
}