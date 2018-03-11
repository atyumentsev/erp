<?php

namespace app\components\helpers;

use app\models\BankAccount;
use app\models\Currency;
use app\models\PaymentRequest;

class CurrencyHelper
{
    public static $currencies = [];
    /** @var Currency */
    public static $rubCurrency = null;

    const CURRENCY_CODE_RUB = 643;

    public static function getConversionRate(int $currency_id, float $percent = null)
    {
        self::init();
        $currency = self::$currencies[$currency_id];
        $ret = $currency->code;
        if (!empty($percent)) {
            $ret .= \Yii::t('app', '(currency rate +{conversion_rate}%)', [
                    'conversion_rate' => $percent]
            );
        }
        return $ret;
    }

    public static function convertToRubUnits($original_currency_id, $conversion_percent, $amount)
    {
        self::init();
        if (empty($amount)) {
            return $amount;
        }
        if ($original_currency_id == self::CURRENCY_CODE_RUB) {
            return $amount;
        }
        $originalCurrency = self::$currencies[$original_currency_id];
        $multiplier = is_numeric($conversion_percent) ? 1 + $conversion_percent / 100 : 1;
        return (int)$amount * CurrencyRate::getRateForToday($originalCurrency->code) * $multiplier;
    }

    public static function convertToBankAccountCurrencyUnits(PaymentRequest $paymentRequest, ?BankAccount $account)
    {
        self::init();

        if ($paymentRequest->original_currency_id == $account->currency_id) {
            return $paymentRequest->required_payment;
        }

        if ($account === null) {

        }
    }

    public static function init()
    {
        if (count(self::$currencies) > 0) {
            return;
        }
        self::$currencies = Currency::find()->indexBy('id')->all();
        self::$rubCurrency = self::$currencies[self::CURRENCY_CODE_RUB];
    }
}