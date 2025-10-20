<?php

namespace App\Jobs;

use App\Mail\DailyExpenseSummaryMail;
use App\Models\User;
use App\Models\Expense;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class SendDailyExpenseSummaryEmail implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $user;

    /**
     * Create a new job instance.
     */
    public function __construct(User $user)
    {
        $this->user = $user;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        Log::info('[Daily Email Job] Started processing', [
            'user_id' => $this->user->id,
            'email' => $this->user->email
        ]);

        $yesterday = Carbon::yesterday();

        Log::info('[Daily Email Job] Looking for expenses', [
            'user_id' => $this->user->id,
            'date' => $yesterday->toDateString()
        ]);

        $expenses = Expense::where('user_id', $this->user->id)
            ->whereDate('expense_date', $yesterday)
            ->get();

        Log::info('[Daily Email Job] Expenses found', [
            'user_id' => $this->user->id,
            'count' => $expenses->count()
        ]);

        if ($expenses->isEmpty()) {
            $summary = collect();
            $total = 0;
        } else {
            $summary = $expenses->groupBy('category')
                ->map(function ($categoryExpenses) {
                    return round($categoryExpenses->sum('amount'), 2);
                });
            $total = round($expenses->sum('amount'), 2);
        }

        Log::info('[Daily Email Job] Sending email', [
            'user_id' => $this->user->id,
            'email' => $this->user->email,
            'total' => $total,
            'categories' => $summary->count()
        ]);

        Mail::to($this->user->email)->send(
            new DailyExpenseSummaryMail($this->user, $summary, $total, $yesterday)
        );

        Log::info('[Daily Email Job] Email sent successfully', [
            'user_id' => $this->user->id,
            'email' => $this->user->email
        ]);
    }
}
