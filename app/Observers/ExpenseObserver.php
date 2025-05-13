<?php

namespace App\Observers;

use App\Models\Expense;
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\Auth;

class ExpenseObserver
{
    /**
     * Handle the Expense "created" event.
     */
    public function created(Expense $expense): void
    {
        // Notify when a new expense is created
        $this->sendNotification('New Expense Added', 
            "A new {$expense->expense_type} expense of \${$expense->amount} has been added.");
            
        // If expense is linked to a team, check the team's balance
        if ($expense->team) {
            $this->checkTeamBalance($expense);
        }
    }

    /**
     * Handle the Expense "updated" event.
     */
    public function updated(Expense $expense): void
    {
        // If expense status changed to "pending", notify the team owner
        if ($expense->isDirty('status') && $expense->status === 'pending' && $expense->team) {
            $this->sendNotification('Expense Status Updated', 
                "An expense of \${$expense->amount} for {$expense->team->team_name} is pending approval.");
        }
        
        // If expense amount changed and is linked to a team, check the team's balance
        if ($expense->isDirty('amount') && $expense->team) {
            $this->checkTeamBalance($expense);
        }
    }
    
    /**
     * Helper method to check team balance after expense changes
     */
    private function checkTeamBalance(Expense $expense): void
    {
        $team = $expense->team;
        $totalDue = $team->getTotalDueAttribute();
        $balance = $team->getBalanceAttribute();
        
        if ($balance > 500) {
            $this->sendNotification('Team Balance Alert', 
                "{$team->team_name} now has a high balance of \${$balance}.");
        }
    }
    
    /**
     * Helper method to send notifications
     */
    private function sendNotification(string $title, string $message): void
    {
        // In a real application, you would target specific users
        // For this example, we'll just create a notification for the current user if one exists
        if (Auth::check()) {
            Notification::make()
                ->title($title)
                ->body($message)
                ->warning()
                ->send();
        }
    }

    /**
     * Handle the Expense "deleted" event.
     */
    public function deleted(Expense $expense): void
    {
        if ($expense->team) {
            $this->sendNotification('Expense Removed', 
                "An expense of \${$expense->amount} for {$expense->team->team_name} has been removed.");
        }
    }

    /**
     * Handle the Expense "restored" event.
     */
    public function restored(Expense $expense): void
    {
        if ($expense->team) {
            $this->sendNotification('Expense Restored', 
                "An expense of \${$expense->amount} for {$expense->team->team_name} has been restored.");
        }
    }

    /**
     * Handle the Expense "force deleted" event.
     */
    public function forceDeleted(Expense $expense): void
    {
        //
    }
}
