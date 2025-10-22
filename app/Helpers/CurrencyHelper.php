<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class CurrencyHelper
{
    public static $symbols = [
        'GHS' => '₵',       // Ghanaian Cedi (now first/default)
        'USD' => '$',       // US Dollar
        'EUR' => '€',       // Euro
        'GBP' => '£',       // British Pound
        'NGN' => '₦',       // Nigerian Naira
        'KES' => 'KSh',     // Kenyan Shilling
        'ZAR' => 'R',       // South African Rand
        'JPY' => '¥',       // Japanese Yen
        'CAD' => 'CA$',     // Canadian Dollar
        'AUD' => 'A$',      // Australian Dollar
        'CNY' => '¥',       // Chinese Yuan
        'INR' => '₹',       // Indian Rupee
        'AED' => 'د.إ',     // UAE Dirham
        'GNF' => 'FG',      // Guinean Franc
        'XOF' => 'CFA',     // West African CFA franc
        'RWF' => 'RF',      // Rwandan Franc
        'ETB' => 'Br',      // Ethiopian Birr
    ];
    
    // Default rates in case API fails (approximate values)
    public static $defaultRates = [
        'GHS' => 1.0,       // Now base currency for defaults
        'USD' => 15.00,     // 1 GHS = 0.066 USD (approx)
        'EUR' => 0.061,     // 1 GHS = 0.061 EUR
        'GBP' => 0.052,     // 1 GHS = 0.052 GBP
        'NGN' => 99.07,     // 1 GHS = 99.07 NGN
        'KES' => 10.57,     // 1 GHS = 10.57 KES
        'ZAR' => 1.22,      // 1 GHS = 1.22 ZAR
        'JPY' => 9.97,      // 1 GHS = 9.97 JPY
        'CAD' => 0.090,     // 1 GHS = 0.090 CAD
        'AUD' => 0.100,     // 1 GHS = 0.100 AUD
        'CNY' => 0.48,      // 1 GHS = 0.48 CNY
        'INR' => 5.50,      // 1 GHS = 5.50 INR
        'AED' => 0.24,      // 1 GHS = 0.24 AED
        'GNF' => 568.0,     // 1 GHS = 568 GNF
        'XOF' => 39.63,     // 1 GHS = 39.63 XOF
        'RWF' => 85.87,     // 1 GHS = 85.87 RWF
        'ETB' => 3.73,      // 1 GHS = 3.73 ETB
    ];

    public static function symbol()
    {
        $c = strtoupper(session('currency', 'GHS'));
        return static::$symbols[$c] ?? '₵';
    }

    public static function getRates()
    {
        return Cache::remember('currency_rates', now()->addHours(24), function () {
            try {
                $apiKey = env('EXCHANGERATE_API_KEY');
                if (empty($apiKey)) {
                    throw new \Exception('Exchange rate API key not configured');
                }
                
                // First get USD rates (as most APIs support USD as base)
                $client = new \GuzzleHttp\Client();
                $response = $client->get("https://v6.exchangerate-api.com/v6/{$apiKey}/latest/USD");
                $data = json_decode($response->getBody(), true);
    
                if ($data['result'] !== 'success') {
                    throw new \Exception('API response not successful');
                }
                
                $usdRates = $data['conversion_rates'];
                
                // If GHS rate is available, calculate all rates relative to GHS
                if (isset($usdRates['GHS'])) {
                    $ghsRate = $usdRates['GHS'];
                    $rates = [];
                    
                    foreach ($usdRates as $currency => $rate) {
                        $rates[$currency] = $rate / $ghsRate;
                    }
                    
                    // Add GHS itself
                    $rates['GHS'] = 15.37;
                    
                    return $rates;
                }
                
                throw new \Exception('GHS rate not available in API response');
            } catch (\Exception $e) {
                Log::error("Failed to fetch exchange rates: " . $e->getMessage());
                Log::info("Falling back to default rates");
                return self::$defaultRates;
            }
        });
    }

    public static function format($amount)
    {
        $c = strtoupper(session('currency', 'GHS'));
        $rates = self::getRates();
        $rate = $rates[$c] ?? 1;
    
        $converted = ($c === 'GHS') ? $amount : $amount / $rate;
    
        return static::symbol() . number_format($converted, 2);
    }
}

if (! function_exists('currency')) {
    function currency($amount)
    {
        return \App\Helpers\CurrencyHelper::format($amount);
    }
}