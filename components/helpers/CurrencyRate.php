<?php

namespace app\components\helpers;

use microinginer\CbRFRates\CBRF;

class CurrencyRate
{
    public static function getRateForToday($currency_code)
    {
        /*
        $rates = [
            'USD' => 60,
            'EUR' => 70,
            'RUB' => 1,
            'GBP' => 80,
            'JPY' => 0.5,
            'CAD' => 45,
            'CNY' => 10,
        ];
        return $rates[$currency_code];
        */
        $cbrf = new CBRF(['cached' => true]);
        return $cbrf->one($currency_code)['value'];
    }
}