<?php

namespace Database\Seeders;

use App\Models\Expense;
use App\Models\Team;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class ExpenseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $expenseTypes = ['equipment', 'marketing', 'venue', 'travel', 'food', 'other'];
        $statusOptions = ['paid', 'pending'];
        
        // Ensure teams exist
        $teams = Team::all();
        if ($teams->isEmpty()) {
            $this->command->info('No teams found. Please run the team seeder first.');
            return;
        }
        
        // Create 20 sample expenses
        for ($i = 0; $i < 20; $i++) {
            $team = $teams->random();
            $expenseType = $expenseTypes[array_rand($expenseTypes)];
            $status = $statusOptions[array_rand($statusOptions)];
            $date = Carbon::now()->subDays(rand(1, 60));
            
            $description = match ($expenseType) {
                'equipment' => 'Sports ' . ['Balls', 'Uniforms', 'Gear', 'Shoes', 'Training Equipment'][array_rand(['Balls', 'Uniforms', 'Gear', 'Shoes', 'Training Equipment'])],
                'marketing' => ['Flyers', 'Social Media Ads', 'Banners', 'Website', 'Promotional Items'][array_rand(['Flyers', 'Social Media Ads', 'Banners', 'Website', 'Promotional Items'])],
                'venue' => ['Field Rental', 'Stadium Rental', 'Practice Space', 'Gym Rental', 'Court Fees'][array_rand(['Field Rental', 'Stadium Rental', 'Practice Space', 'Gym Rental', 'Court Fees'])],
                'travel' => ['Bus Rental', 'Flight Tickets', 'Hotel Stay', 'Gas Reimbursement', 'Taxi Services'][array_rand(['Bus Rental', 'Flight Tickets', 'Hotel Stay', 'Gas Reimbursement', 'Taxi Services'])],
                'food' => ['Team Dinner', 'Post-Game Meal', 'Snacks', 'Water & Drinks', 'Catering'][array_rand(['Team Dinner', 'Post-Game Meal', 'Snacks', 'Water & Drinks', 'Catering'])],
                'other' => ['Insurance', 'Medical Supplies', 'Office Supplies', 'Entry Fees', 'Miscellaneous'][array_rand(['Insurance', 'Medical Supplies', 'Office Supplies', 'Entry Fees', 'Miscellaneous'])],
            };
            
            $amount = match ($expenseType) {
                'equipment' => rand(100, 1000),
                'marketing' => rand(50, 500),
                'venue' => rand(200, 2000),
                'travel' => rand(300, 3000),
                'food' => rand(75, 750),
                'other' => rand(25, 250),
            };
            
            Expense::create([
                'expense_type' => $expenseType,
                'description' => $description,
                'amount' => $amount,
                'status' => $status,
                'date' => $date,
                'team_id' => $team->id,
                'notes' => 'Sample expense for demonstration purposes.',
            ]);
        }
    }
}
