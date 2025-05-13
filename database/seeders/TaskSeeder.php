<?php

namespace Database\Seeders;

use App\Models\Sponsor;
use App\Models\Task;
use App\Models\Team;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class TaskSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $teams = Team::all();
        $sponsors = Sponsor::all();
        
        if ($teams->isEmpty() && $sponsors->isEmpty()) {
            $this->command->info('No teams or sponsors found. Please run the team and sponsor seeders first.');
            return;
        }
        
        // Sample tasks for teams
        $teamTasks = [
            'Submit final roster',
            'Complete medical forms',
            'Pay entry fee',
            'Order team uniforms',
            'Confirm practice schedule',
            'Submit logo for program',
            'Complete liability forms',
            'Provide team bio for website',
            'Confirm travel arrangements',
            'Send player information',
        ];
        
        // Sample tasks for sponsors
        $sponsorTasks = [
            'Send logo for program',
            'Confirm sponsor table setup',
            'Submit ad copy for website',
            'Complete payment',
            'Confirm signage requirements',
            'Sign sponsorship agreement',
            'Provide promotional items',
            'Confirm representative attendance',
            'Review sponsorship benefits',
            'Schedule social media promotion',
        ];
        
        // Create team tasks
        foreach ($teams as $team) {
            $numTasks = rand(1, 4);
            $selectedTasks = array_rand($teamTasks, $numTasks);
            
            if (!is_array($selectedTasks)) {
                $selectedTasks = [$selectedTasks];
            }
            
            foreach ($selectedTasks as $taskIndex) {
                $dueDate = Carbon::now()->addDays(rand(-5, 30));
                $done = $dueDate < Carbon::now() ? (rand(0, 1) ? true : false) : false;
                
                Task::create([
                    'task_name' => $teamTasks[$taskIndex],
                    'assigned_to' => $team->contact_person,
                    'due_date' => $dueDate,
                    'done' => $done,
                    'team_id' => $team->id,
                    'notes' => 'Task for ' . $team->team_name,
                ]);
            }
        }
        
        // Create sponsor tasks
        foreach ($sponsors as $sponsor) {
            $numTasks = rand(1, 3);
            $selectedTasks = array_rand($sponsorTasks, $numTasks);
            
            if (!is_array($selectedTasks)) {
                $selectedTasks = [$selectedTasks];
            }
            
            foreach ($selectedTasks as $taskIndex) {
                $dueDate = Carbon::now()->addDays(rand(-5, 30));
                $done = $dueDate < Carbon::now() ? (rand(0, 1) ? true : false) : false;
                
                Task::create([
                    'task_name' => $sponsorTasks[$taskIndex],
                    'assigned_to' => $sponsor->contact_person,
                    'due_date' => $dueDate,
                    'done' => $done,
                    'sponsor_id' => $sponsor->id,
                    'notes' => 'Task for ' . $sponsor->name,
                ]);
            }
        }
    }
}
