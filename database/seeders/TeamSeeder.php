<?php

namespace Database\Seeders;

use App\Models\Team;
use Illuminate\Database\Seeder;

class TeamSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $teams = [
            [
                'team_name' => 'Red Dragons',
                'contact_person' => 'John Smith',
                'email' => 'john@example.com',
                'phone' => '555-123-4567',
                'owner' => 'Dragon Sports LLC',
                'cash_paid' => 1200.00,
            ],
            [
                'team_name' => 'Blue Eagles',
                'contact_person' => 'Sarah Johnson',
                'email' => 'sarah@example.com',
                'phone' => '555-987-6543',
                'owner' => 'Eagle Enterprises',
                'cash_paid' => 800.00,
            ],
            [
                'team_name' => 'Green Giants',
                'contact_person' => 'Mike Wilson',
                'email' => 'mike@example.com',
                'phone' => '555-456-7890',
                'owner' => 'Giant Athletics',
                'cash_paid' => 1500.00,
            ],
            [
                'team_name' => 'Yellow Jackets',
                'contact_person' => 'Lisa Brown',
                'email' => 'lisa@example.com',
                'phone' => '555-321-6547',
                'owner' => 'Jacket Sports',
                'cash_paid' => 950.00,
            ],
            [
                'team_name' => 'Purple Panthers',
                'contact_person' => 'David Lee',
                'email' => 'david@example.com',
                'phone' => '555-789-4561',
                'owner' => 'Panther Athletics',
                'cash_paid' => 1100.00,
            ],
        ];
        
        foreach ($teams as $team) {
            Team::create($team);
        }
    }
}
