<?php

namespace App\Helpers; // todo: you must to add this namespace to your file app.config.php in aliases array

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Session;
use NumberFormatter;

// this class is Helper class for Curreny
class Currency // i build this class to handle currency formatting and conversion
{

    // i need to make magic method to call the format method directly
    // يعني باختصار هذه الدالة بتخلي الكلاس كدالة
    public function __invoke(...$params)
    {
        return static::format(...$params);
    }

    /**
     * Format the given amount to a currency string.
     *
     * @param float $amount
     * @param string $currencySymbol
     * @return string
     */
    public static function format(float $amount, $currency = null): string
    {

        $baseCurrency = config('app.currency', 'USD'); // Default to 'USD' if not set in config
        
        $formatter = new NumberFormatter(config('app.locale'), NumberFormatter::CURRENCY);
        if ($currency === null) {
            // If no currency is provided, use the default currency symbol
            // $currency = config('app.currency', 'USD'); // Default to '$' if not set in config i added it by me

            // i need to get the currency from the if is in Session else i need to get the currency from config
            $currency = Session::get('currency_code', $baseCurrency);
        }
        if($currency != $baseCurrency) {
            $rate = Cache::get('currency_rate_' . $currency, 1);
            $amount *= $rate;
        }
        return $formatter->formatCurrency($amount, $currency);
    }

    /**
     * Convert the given amount from one currency to another.
     *
     * @param float $amount
     * @param float $conversionRate
     * @return float
     */
    public static function convert(float $amount, float $conversionRate): float
    {
        return $amount * $conversionRate;
    }
}
