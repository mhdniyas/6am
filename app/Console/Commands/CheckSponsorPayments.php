<?php

namespace App\Console\Commands;

use App\Models\Sponsor;
use App\Models\User;
use Filament\Notifications\Notification;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

class CheckSponsorPayments extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:check-sponsor-payments';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check for sponsors with overdue payments and send reminders';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Checking for sponsors with outstanding balances...');
        
        // Find sponsors with outstanding balances
        $sponsors = Sponsor::all()->filter(function ($sponsor) {
            return ($sponsor->committed_amount - $sponsor->paid_amount) > 0;
        });
        
        if ($sponsors->isEmpty()) {
            $this->info('No sponsors with outstanding balances found.');
            return 0;
        }
        
        $this->info('Found ' . $sponsors->count() . ' sponsors with outstanding balances.');
        
        // Process each sponsor with outstanding balance
        foreach ($sponsors as $sponsor) {
            $balance = $sponsor->committed_amount - $sponsor->paid_amount;
            $this->info("Sponsor: {$sponsor->name}, Outstanding: \${$balance}");
            
            // In a real application, you would send emails to the sponsor here
            // For this example, we'll just log it
            $this->info("Sending reminder to {$sponsor->contact_person} at {$sponsor->email}");
            
            // Store a notification for admins
            $this->storeNotificationForAdmins(
                "Payment Reminder Sent",
                "A payment reminder was sent to {$sponsor->name} for an outstanding balance of \${$balance}."
            );
        }
        
        $this->info('Finished processing sponsors with outstanding balances.');
        return 0;
    }
    
    /**
     * Store notifications for admin users
     */
    private function storeNotificationForAdmins($title, $body)
    {
        // In a real application, you would get all admin users
        // For this example, we'll assume we have at least one admin
        $adminUser = User::where('role', 'admin')->first();
        
        if ($adminUser) {
            Notification::make()
                ->title($title)
                ->body($body)
                ->warning()
                ->sendToDatabase($adminUser);
        }
    }
}
