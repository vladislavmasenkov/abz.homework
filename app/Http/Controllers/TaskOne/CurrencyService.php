<?php

namespace App\Http\Controllers\TaskOne;

class CurrencyService
{
    const CURRENCY_CODE_USD = 'USD';
    const CURRENCY_CODE_EUR = 'EUR';

    public static function updateCurrencyRate($currencies)
    {
        foreach ($currencies as $key => $currency) {
            if($currency['currencycode'] == self::CURRENCY_CODE_USD) {
                $currencies[$key]['rate'] = 25.55;
            }
            if($currency['currencycode'] == self::CURRENCY_CODE_EUR) {
                $currencies[$key]['rate'] = 28.35;
            }
        }
        return $currencies;
    }
}
