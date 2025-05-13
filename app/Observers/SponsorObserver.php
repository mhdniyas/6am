<?php

namespace App\Observers;

use App\Models\Sponsor;
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\Auth;

class SponsorObserver
{
    /**
     * Handle the Sponsor "created" event.
     */
    public function created(Sponsor $sponsor): void
    {
        // Notify admins when a new sponsor is created
        $this->notifyAdmins('New Sponsor Added', 
            "{$sponsor->name} has been added as a sponsor with a commitment of \${$sponsor->committed_amount}.");
    }

    /**
     * Handle the Sponsor "updated" event.
     */
    public function updated(Sponsor $sponsor): void
    {
        // Check for balance changes
        if ($sponsor->isDirty('committed_amount') || $sponsor->isDirty('paid_amount')) {
            $balance = $sponsor->committed_amount - $sponsor->paid_amount;
            
            // If there's still a balance and it's been more than 30 days
            if ($balance > 0 && $sponsor->created_at->diffInDays(now()) > 30) {
                $this->notifyAdmins('Sponsor Balance Alert', 
                    "{$sponsor->name} still has an outstanding balance of \${$balance}.");
            }
        }
    }

    /**
     * Helper method to notify admins
     */
    private function notifyAdmins(string $title, string $message): void
    {
        // In a real application, you would fetch all admin users and notify them
        // For this example, we'll just create a notification for the current user if one exists
        if (Auth::check()) {
            Notification::make()
                ->title($title)
                ->body($message)
                ->success()
                ->send();
        }
    }

    /**
     * Handle the Sponsor "deleted" event.
     */
    public function deleted(Sponsor $sponsor): void
    {
        $this->notifyAdmins('Sponsor Removed', 
            "{$sponsor->name} has been removed from sponsors.");
    }

    /**
     * Handle the Sponsor "restored" event.
     */
    public function restored(Sponsor $sponsor): void
    {
        $this->notifyAdmins('Sponsor Restored', 
            "{$sponsor->name} has been restored as a sponsor.");
    }

    /**
     * Handle the Sponsor "force deleted" event.
     */
    public function forceDeleted(Sponsor $sponsor): void
    {
        //
    }
}
