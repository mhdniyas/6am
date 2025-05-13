<?php

namespace App\Providers;

use App\Models\Expense;
use App\Models\Sponsor;
use App\Observers\ExpenseObserver;
use App\Observers\SponsorObserver;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Register model observers
        Sponsor::observe(SponsorObserver::class);
        Expense::observe(ExpenseObserver::class);
    }
}
