<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;
use App\Models\User;
use App\Jobs\SendDailyExpenseSummaryEmail;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Schedule::call(function () {
    User::all()->each(function ($user) {
        SendDailyExpenseSummaryEmail::dispatch($user);
    });
})->dailyAt('08:00')->name('send-daily-expense-summary')->withoutOverlapping();
