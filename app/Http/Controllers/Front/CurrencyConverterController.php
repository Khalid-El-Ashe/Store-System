<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Services\CurrencyConverter;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Session;

class CurrencyConverterController extends Controller
{
    public function index() {
        dd('Currency Converter Index'); //
    }

    public function store(Request $request) {

        dd($request->all());

        // Validate the request data
        $request->validate([
            'currency_code' => 'required|string|size:3',
            // 'to_currency' => 'required|string|max:3',
            // 'amount' => 'required|numeric|min:0',
        ]);

        $baseCurrency = config('app.currency');
        $currencyCode = $request->input('currency_code');

        $cacheKey = 'currency_rate_' . $currencyCode;

        // $rate = Cache::get('curreny_rates', []);
        $rate = Cache::get($cacheKey, 0);

        if(!$rate) { // this check if the value of currency selected by user is not in Cache so i need to get the rate from API
            // i need make object of CurrencyConverter
            $converter = app('currency.converter'); // new CurrencyConverter(config('services.currency_converter.api_key'));
            $rate = $converter->convert($baseCurrency, $currencyCode);

            // $rates[$currencyCode] = $rate;

            // i need save the rate in the Cache
            Cache::put($cacheKey, $rate, now()->addMinutes(60));
        }

        Session::put('currency_code', $currencyCode);

        // Session::put('currency_rate', $rate);

        return redirect()->back();
        // return response()->json([
        //     'message' => 'Currency updated successfully',
        //     'currency_code' => $currencyCode,
        //     'rate' => $rate,
        // ]); // this is the response that i need to return to the user
    }
}
