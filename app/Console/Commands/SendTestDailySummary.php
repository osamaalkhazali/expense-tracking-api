<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use App\Jobs\SendDailyExpenseSummaryEmail;

class SendTestDailySummary extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'email:daily-summary {user_id?}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send daily expense summary email to a user or all users';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $userId = $this->argument('user_id');

        if ($userId) {
            $user = User::find($userId);
            if (!$user) {
                $this->error("User with ID {$userId} not found!");
                return 1;
            }

            $this->info("Dispatching daily summary email for {$user->email}...");
            SendDailyExpenseSummaryEmail::dispatch($user);
            $this->info("Job dispatched to queue successfully!");
        } else {
            $users = User::all();
            $count = $users->count();

            $this->info("Dispatching daily summary emails for {$count} users...");

            $users->each(function ($user) {
                SendDailyExpenseSummaryEmail::dispatch($user);
                $this->line("- Dispatched for {$user->email}");
            });

            $this->info("All jobs dispatched successfully!");
        }

        return 0;
    }
}
