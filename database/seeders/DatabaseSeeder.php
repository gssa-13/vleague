<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    private const SUPER_ADMIN_ROLE = 'super-admin';

    private const SUPER_ADMIN_USERS = [
        [
            'name' => 'LS Admin',
            'email' => 'admin@ls.mx',
        ],
    ];

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Seed base roles and permissions first
        $this->call(RolesAndPermissionsSeeder::class);
        $this->call(NavigationItemsSeeder::class);

        // Create default super-admin users for local development.
        foreach (self::SUPER_ADMIN_USERS as $superAdminUser) {
            $this->seedSuperAdminUser($superAdminUser['name'], $superAdminUser['email']);
        }
    }

    private function seedSuperAdminUser(string $name, string $email): void
    {
        $superAdmin = User::firstOrCreate(
            ['email' => $email],
            [
                'name' => $name,
                'email_verified_at' => now(),
                'password' => Hash::make('password'),
            ],
        );
        $superAdmin->assignRole(self::SUPER_ADMIN_ROLE);
    }
}
