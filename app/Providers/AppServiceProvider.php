<?php

namespace App\Providers;

use App\Models\Expense;
use App\Policies\ExpensePolicy;
use App\Services\CurrencyService;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        // Register CurrencyService as singleton
        $this->app->singleton(CurrencyService::class, function ($app) {
            return new CurrencyService();
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        \Illuminate\Support\Facades\Schema::defaultStringLength(191);

        // Register policies
        Gate::policy(Expense::class, ExpensePolicy::class);
    }
}
