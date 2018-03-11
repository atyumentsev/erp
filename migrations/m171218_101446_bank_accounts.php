<?php

use yii\db\Migration;
use app\models\Bank;
use app\models\Affiliate;
use app\models\BankAccount;
use app\models\Currency;

/**
 * Class m171218_101446_bank_accounts
 */
class m171218_101446_bank_accounts extends Migration
{
    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        $currency = new Currency([
            'id' => 344,
            'code' => 'HKD',
            'name' => 'Hong Kong dollar',
            'units' => 2,
            'sign' => 'HK$',
        ]);
        $currency->save();

        $banks = [
            [
                'short_name' => 'HSBC',
                'name' => 'HSBC',
            ],
            [
                'short_name' => 'Hellenic Bank',
                'name' => 'Hellenic Bank Public Co. Ltd.',
            ],
            [
                'short_name' => 'NORVIK BANKA',
                'name' => 'AS "NORVIK BANKA"',
            ],
        ];

        foreach ($banks as $_bank) {
            $bank = new Bank($_bank);
            if (!$bank->save()) {
                print_r($bank->errors);
                return false;
            }
        }

        $banks_hash = array_column(
            Bank::find()
                ->select('short_name, id')
                ->asArray()
                ->all(),
            'id', 'short_name'
        );

        $affiliates_hash = array_column(
            Affiliate::find()
                ->select('short_name, id')
                ->asArray()
                ->all(),
            'id', 'short_name'
        );

        $bank_accounts = [
            [
                'affiliate_short_name' => 'HKI',
                'accounts' => [
                    [
                        'account_number' => '652-212523-838',
                        'short_name' => 'HKI HKD S',
                        'name' => 'HKI HKD Savings',
                        'bank_id' => $banks_hash['HSBC'],
                        'currency_id' => 344,
                    ],
                    [
                        'account_number' => '652-212523-838',
                        'short_name' => 'HKI HKD C',
                        'name' => 'HKI HKD Current',
                        'bank_id' => $banks_hash['HSBC'],
                        'currency_id' => 344,
                    ],
                    [
                        'account_number' => '652-212523-838',
                        'short_name' => 'HKI EUR',
                        'name' => 'HKI EUR Savings',
                        'bank_id' => $banks_hash['HSBC'],
                        'currency_id' => 978,
                    ],
                    [
                        'account_number' => '652-212523-838',
                        'short_name' => 'HKI GBP',
                        'name' => 'HKI GBP Savings',
                        'bank_id' => $banks_hash['HSBC'],
                        'currency_id' => 826,
                    ],
                    [
                        'account_number' => '652-212523-838',
                        'short_name' => 'HKI USD',
                        'name' => 'HKI USD Savings',
                        'bank_id' => $banks_hash['HSBC'],
                        'currency_id' => 840,
                    ],
                ],
            ],
            [
                'affiliate_short_name' => 'Lx',
                'accounts' => [
                    [
                        'account_number' => '240-07-372249-01',
                        'short_name' => 'Lx HB USD',
                        'name' => 'Lx HB USD',
                        'bank_id' => $banks_hash['Hellenic Bank'],
                        'currency_id' => 840,
                    ],
                    [
                        'account_number' => '240-01-372249-01',
                        'short_name' => 'Lx HB EUR',
                        'name' => 'Lx HB Euro',
                        'bank_id' => $banks_hash['Hellenic Bank'],
                        'currency_id' => 978,
                    ],
                ],
            ],
            [
                'affiliate_short_name' => 'Lx',
                'accounts' => [
                    [
                        'account_number' => 'LV49LATB0006020188795',
                        'short_name' => 'Lx NB USD',
                        'name' => 'Lx Norvik USD',
                        'bank_id' => $banks_hash['NORVIK BANKA'],
                        'currency_id' => 840,
                    ],
                    [
                        'account_number' => 'LV55LATB0006100132304',
                        'short_name' => 'Lx NB EUR',
                        'name' => 'Lx Norvik Euro',
                        'bank_id' => $banks_hash['NORVIK BANKA'],
                        'currency_id' => 978,
                    ],
                    [
                        'account_number' => 'LV17LATB0006140022298',
                        'short_name' => 'Lx NB GBP',
                        'name' => 'Lx Norvik GBP',
                        'bank_id' => $banks_hash['NORVIK BANKA'],
                        'currency_id' => 826,
                    ],
                    [
                        'account_number' => 'LV28LATB0006070002456',
                        'short_name' => 'Lx NB CAD',
                        'name' => 'Lx Norvik CAD',
                        'bank_id' => $banks_hash['NORVIK BANKA'],
                        'currency_id' => 124,
                    ],
                ],
            ],
            [
                'affiliate_short_name' => 'LI',
                'accounts' => [
                    [
                        'account_number' => '240-07-815432-01',
                        'short_name' => 'LI HB USD',
                        'name' => 'LI HB USD',
                        'bank_id' => $banks_hash['Hellenic Bank'],
                        'currency_id' => 840,
                    ],
                    [
                        'account_number' => '240-01-815432-01',
                        'short_name' => 'LI HB EUR',
                        'name' => 'LI HB Euro',
                        'bank_id' => $banks_hash['Hellenic Bank'],
                        'currency_id' => 978,
                    ],
                ],
            ],
            [
                'affiliate_short_name' => 'LI',
                'accounts' => [
                    [
                        'account_number' => 'LV83LATB0006020187213',
                        'short_name' => 'LIF NB USD',
                        'name' => 'LIF Norvik USD',
                        'bank_id' => $banks_hash['NORVIK BANKA'],
                        'currency_id' => 840,
                    ],
                ],
            ],
            [
                'affiliate_short_name' => 'ЛМ',
                'accounts' => [
                    // сбербанк
                    [
                        'account_number' => '40702978355080000127',
                        'short_name' => 'ЛМ СБ EUR Р',
                        'name' => 'Сбербанк Расчетный, EUR',
                        'bank_id' => $banks_hash['Сбербанк'],
                        'currency_id' => 978,
                    ],
                    [
                        'account_number' => '40702840755080000091',
                        'short_name' => 'ЛМ СБ USD Р',
                        'name' => 'Сбербанк Расчетный, USD',
                        'bank_id' => $banks_hash['Сбербанк'],
                        'currency_id' => 840,
                    ],
                    [
                        'account_number' => '40702978655081000127',
                        'short_name' => 'ЛМ СБ EUR Т',
                        'name' => 'Сбербанк Транзитный, EUR',
                        'bank_id' => $banks_hash['Сбербанк'],
                        'currency_id' => 978,
                    ],
                    [
                        'account_number' => '40702840055081000091',
                        'short_name' => 'ЛМ СБ USD Т',
                        'name' => 'Сбербанк Транзитный, USD',
                        'bank_id' => $banks_hash['Сбербанк'],
                        'currency_id' => 840,
                    ],
                    // НОМОС-БАНК
                    [
                        'account_number' => '40702978800050000238',
                        'short_name' => 'ЛМ Открытие EUR Текущий',
                        'name' => 'ЛМ Открытие Текущий, EUR',
                        'bank_id' => $banks_hash['ФК Открытие'],
                        'currency_id' => 978,
                    ],
                    [
                        'account_number' => '40702840000050000247',
                        'short_name' => 'ЛМ Открытие USD Текущий',
                        'name' => 'Открытие Текущий, USD',
                        'bank_id' => $banks_hash['ФК Открытие'],
                        'currency_id' => 840,
                    ],
                    [
                        'account_number' => '40702978300051000261',
                        'short_name' => 'ЛМ Открытие EUR Т',
                        'name' => 'Открытие Транзитный, EUR',
                        'bank_id' => $banks_hash['ФК Открытие'],
                        'currency_id' => 978,
                    ],
                    [
                        'account_number' => '40702840500051000267',
                        'short_name' => 'ЛМ Открытие USD Т',
                        'name' => 'Открытие Транзитный, USD',
                        'bank_id' => $banks_hash['ФК Открытие'],
                        'currency_id' => 840,
                    ],
                    // уралсиб
                    [
                        'account_number' => '40702978622050000119',
                        'short_name' => 'ЛМ Уралсиб EUR Текущий',
                        'name' => 'ЛМ Уралсиб Текущий, EUR',
                        'bank_id' => $banks_hash['УРАЛСИБ'],
                        'currency_id' => 978,
                    ],
                    [
                        'account_number' => '40702840022005000119',
                        'short_name' => 'ЛМ Уралсиб USD Текущий',
                        'name' => 'ЛМ Уралсиб Текущий, USD',
                        'bank_id' => $banks_hash['УРАЛСИБ'],
                        'currency_id' => 840,
                    ],
                    [
                        'account_number' => '40702978522053000119',
                        'short_name' => 'ЛМ Уралсиб EUR Т',
                        'name' => 'ЛМ Уралсиб Транзитный, EUR',
                        'bank_id' => $banks_hash['УРАЛСИБ'],
                        'currency_id' => 978,
                    ],
                    [
                        'account_number' => '40702840922053000119',
                        'short_name' => 'ЛМ Уралсиб USD Т',
                        'name' => 'ЛМ Уралсиб Транзитный, USD',
                        'bank_id' => $banks_hash['УРАЛСИБ'],
                        'currency_id' => 840,
                    ],
                ],
            ],
        ];

        foreach ($bank_accounts as $data) {
            $affiliate_id = $affiliates_hash[$data['affiliate_short_name']];
            foreach ($data['accounts'] as $_account) {
                $_account['affiliate_id'] = $affiliate_id;
                $account = new BankAccount($_account);
                if (!$account->save()) {
                    print_r($account->errors);
                    return false;
                }
            }
        }

        return true;
    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        $account_numbers = [
            '652-212523-838',
            '240-07-372249-01',
            '240-01-372249-01',
            'LV49LATB0006020188795',
            'LV55LATB0006100132304',
            'LV17LATB0006140022298',
            'LV28LATB0006070002456',
            '240-07-815432-01',
            '240-01-815432-01',
            'LV83LATB0006020187213',
            '40702978800050000238',
            '40702978800050000238',
            '40702978300051000261',
            '40702978355080000127',
            '40702840755080000091',
            '40702978655081000127',
            '40702840055081000091',
            '40702840500051000267',
            '40702840000050000247',
            '40702978622050000119',
            '40702840022005000119',
            '40702978522053000119',
            '40702840922053000119',
        ];
        BankAccount::deleteAll(['account_number' => $account_numbers]);

        $banks = [
            'HSBC',
            'Hellenic Bank',
            'NORVIK BANKA',
        ];
        Bank::deleteAll(['short_name' => $banks]);

        Currency::deleteAll(['id' => 344]);
    }
}
