<?php

namespace App\Jobs;

use App\Services\FirebaseService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class LogFirebaseEventJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public string $event;
    public array $data;

    /**
     * Create a new job instance.
     */
    public function __construct(string $event, array $data)
    {
        $this->event = $event;
        $this->data = $data;
    }

    /**
     * Execute the job.
     */
    public function handle(FirebaseService $firebase): void
    {
        Log::info('LogFirebaseEventJob::handle started', ['event' => $this->event]);

        try {
            $firebase->logEvent($this->event, $this->data);

            Log::info('Firebase event job processed', [
                'event' => $this->event,
                'data' => $this->data
            ]);
        } catch (\Exception $e) {
            Log::error('Firebase event job failed', [
                'event' => $this->event,
                'error' => $e->getMessage()
            ]);

            throw $e;
        }
    }
}
