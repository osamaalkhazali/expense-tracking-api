<?php

namespace App\Services;

use App\Jobs\LogFirebaseEventJob;
use Kreait\Firebase\Factory;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Exception;

class FirebaseService
{
    protected $firebase;
    private const GA_MEASUREMENT_ID = 'G-W9EQBT87G3';
    private const GA_API_SECRET = 'sP83gdN6TtekB2G_gk3ttA';
    private const GA_ENDPOINT = 'https://www.google-analytics.com/mp/collect';

    public function __construct()
    {
        try {
            $this->firebase = (new Factory)
                ->withServiceAccount(config('services.firebase.credentials'));
        } catch (Exception $e) {
            Log::error('Firebase initialization failed', ['error' => $e->getMessage()]);
        }
    }

    /**
     * Dispatch event to queue for async processing
     */
    public function dispatchEvent(string $eventName, array $data = [])
    {
        LogFirebaseEventJob::dispatch($eventName, $data);

        Log::info('Firebase event dispatched to queue', [
            'event' => $eventName,
            'data' => $data
        ]);
    }

    /**
     * Log event to Firebase Analytics using Measurement Protocol
     */
    public function logEvent(string $eventName, array $data = [])
    {
        Log::info('FirebaseService::logEvent called', ['event' => $eventName, 'data' => $data]);

        try {
            // Send to Google Analytics via Measurement Protocol
            $this->sendToGoogleAnalytics($eventName, $data);

            Log::info('Firebase event logged successfully', [
                'event' => $eventName,
                'data' => $data
            ]);
        } catch (Exception $e) {
            Log::error('Firebase event logging failed', [
                'event' => $eventName,
                'error' => $e->getMessage()
            ]);
        }
    }

    /**
     * Send event to Google Analytics Measurement Protocol
     */
    private function sendToGoogleAnalytics(string $eventName, array $params)
    {
        Log::info('sendToGoogleAnalytics started', ['event' => $eventName]);

        try {
            $clientId = isset($params['user_id']) ? 'user_' . $params['user_id'] : uniqid('client_');

            $url = self::GA_ENDPOINT . '?measurement_id=' . self::GA_MEASUREMENT_ID . '&api_secret=' . self::GA_API_SECRET . '&debug_mode=1';

            $payload = [
                'client_id' => $clientId,
                'events' => [['name' => $eventName, 'params' => $params]]
            ];

            Log::info('Sending to GA', ['url' => $url, 'client_id' => $clientId]);

            $response = Http::withOptions(['verify' => false])
                ->timeout(3)
                ->post($url, $payload);

            Log::info('GA response received', ['status' => $response->status()]);

            if (!$response->successful()) {
                Log::warning('GA event failed', ['event' => $eventName, 'status' => $response->status()]);
            } else {
                Log::info('GA event sent successfully', ['event' => $eventName]);
            }
        } catch (Exception $e) {
            Log::error('GA error', ['event' => $eventName, 'error' => $e->getMessage()]);
        }
    }
}
