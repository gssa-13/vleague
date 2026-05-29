<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Seed base roles and permissions first
        $this->call(RolesAndPermissionsSeeder::class);

        // Create a default super-admin user for local development
        $superAdmin = User::factory()->create([
            'name' => 'Super Admin',
            'email' => 'admin@vleague.test',
        ]);
        $superAdmin->assignRole('super-admin');
    }
}
