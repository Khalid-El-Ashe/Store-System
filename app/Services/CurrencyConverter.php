<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

 class CurrencyConverter {
    private $apiKey;
    protected $baseUrl = "https://free.currconv.com/api/v7";

    public function __construct($apiKey) {
        $this->apiKey = $apiKey;
    }

    public function convert(string $fromCurrency, string $toCurrency, float $amount = 1.0): float {
        $q = "{$fromCurrency}_{$toCurrency}";
        $response = Http::baseUrl($this->baseUrl)->get('/convert', [
            'q' => $q,
            'compact' => 'y',
            'apiKey' => $this->apiKey,
        ]);

        $result = $response->json();
        return $result[$q]['val'] * $amount;
        // $url = "{$this->baseUrl}/{$fromCurrency}";

        // $response = file_get_contents($url);
        // if ($response === false) {
        //     throw new \Exception("Error fetching exchange rates.");
        // }

        // $data = json_decode($response, true);
        // if (!isset($data['rates'][$toCurrency])) {
        //     throw new \Exception("Invalid currency code: {$toCurrency}");
        // }

        // $rate = $data['rates'][$toCurrency];
        // return $amount * $rate;
    }
 }
