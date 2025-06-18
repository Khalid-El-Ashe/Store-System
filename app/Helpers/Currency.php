<?php

namespace App\Helpers; // todo: you must to add this namespace to your file app.config.php in aliases array

use NumberFormatter;

// this class is Helper class for Curreny
class Currency // i build this class to handle currency formatting and conversion
{
    /**
     * Format the given amount to a currency string.
     *
     * @param float $amount
     * @param string $currencySymbol
     * @return string
     */
    public static function format(float $amount, $currency = null): string
    {
        $formatter = new NumberFormatter(config('app.locale'), NumberFormatter::CURRENCY);
        if ($currency === null) {
            // If no currency is provided, use the default currency symbol
            $currency = config('app.currency', 'USD'); // Default to '$' if not set in config i added it by me
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
