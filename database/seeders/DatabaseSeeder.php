<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Create admin user
        User::factory()->create([
            'name' => 'Admin User',
            'email' => 'admin@example.com',
            'password' => bcrypt('password'),
            'role' => 'admin',
        ]);
        
        // Create organizer user
        User::factory()->create([
            'name' => 'Organizer User',
            'email' => 'organizer@example.com',
            'password' => bcrypt('password'),
            'role' => 'organizer',
        ]);
        
        // Call the custom seeders in the correct order
        $this->call([
            TeamSeeder::class,
            SponsorSeeder::class,
            ExpenseSeeder::class,
            TaskSeeder::class,
        ]);
    }
}
