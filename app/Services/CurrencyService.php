<?php

namespace App\Services;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Exception;

class CurrencyService
{
    private const API_URL = 'https://api.currencyfreaks.com/v2.0/rates/latest';
    private const CACHE_KEY = 'currency_rates';
    private const CACHE_HOURS = 12;

    /**
     * Convert amount between currencies
     */
    public function convert(float $amount, string $from, string $to): float
    {
        $from = strtoupper($from);
        $to = strtoupper($to);

        if ($from === $to) {
            return round($amount, 2);
        }

        $data = $this->getRates();
        if (!$data) {
            Log::warning('Conversion skipped - rates unavailable', compact('from', 'to', 'amount'));
            return round($amount, 2);
        }

        $rates = $data['rates'];
        $fromRate = $rates[$from] ?? null;
        $toRate = $rates[$to] ?? null;

        if (!$fromRate || !$toRate) {
            Log::error('Conversion failed - currency not found', compact('from', 'to'));
            return round($amount, 2);
        }

        $converted = ($amount / $fromRate) * $toRate;
        Log::info('Currency converted', compact('from', 'to', 'amount', 'converted'));

        return round($converted, 2);
    }

    /**
     * Get cached or fresh exchange rates
     */
    private function getRates(): ?array
    {
        return Cache::remember(self::CACHE_KEY, now()->addHours(self::CACHE_HOURS), function () {
            return $this->fetchRates();
        });
    }

    /**
     * Fetch rates from API
     */
    private function fetchRates(): ?array
    {
        $apiKey = config('services.currency.api_key');
        if (!$apiKey) {
            Log::error('API key missing');
            return null;
        }

        try {
            $response = Http::withOptions(['verify' => false])
                ->timeout(10)
                ->get(self::API_URL, ['apikey' => $apiKey]);

            if (!$response->successful()) {
                Log::error('API request failed', ['status' => $response->status()]);
                return null;
            }

            $data = $response->json();
            $rates = [];

            foreach ($data['rates'] ?? [] as $code => $rate) {
                $rates[strtoupper($code)] = (float) $rate;
            }

            Log::info('Rates fetched successfully', ['count' => count($rates)]);

            return [
                'base' => strtoupper($data['base'] ?? 'USD'),
                'rates' => $rates
            ];
        } catch (Exception $e) {
            Log::error('API error', ['error' => $e->getMessage()]);
            return null;
        }
    }
}
