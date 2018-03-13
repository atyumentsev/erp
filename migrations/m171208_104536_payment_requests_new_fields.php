<?php

use yii\db\Migration;
use app\models\Bank;
use app\models\BankAccount;
use app\models\Affiliate;


/**
 * Class m171208_104536_approval_process
 */
class m171208_104536_payment_requests_new_fields extends Migration
{
    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        $this->createTable('bank', [
            'id'            => $this->primaryKey(),
            'short_name'    => $this->string(30)->notNull(),
            'name'          => $this->string(100)->notNull(),
            'created_at'    => $this->integer()->notNull(),
            'updated_at'    => $this->integer()->notNull(),
        ]);

        $this->createTable('bank_account', [
            'id'            => $this->primaryKey(),
            'bank_id'       => $this->integer()->notNull(),
            'affiliate_id'  => $this->integer()->notNull(),
            'short_name'    => $this->string(30)->notNull(),
            'name'          => $this->string(100)->notNull(),
            'account_number' => $this->string(100)->notNull(),
            'currency_id'   => $this->integer()->notNull(),
            'created_at'    => $this->integer()->notNull(),
            'updated_at'    => $this->integer()->notNull(),
        ]);

        $this->addForeignKey(
            'pr_ba_bank',
            'bank_account',
            'bank_id',
            'bank',
            'id'
        );

        $this->addForeignKey(
            'pr_ba_currency',
            'bank_account',
            'currency_id',
            'currency',
            'id'
        );

        $this->addForeignKey(
            'pr_ba_affiliate',
            'bank_account',
            'affiliate_id',
            'affiliate',
            'id'
        );

        $this->addColumn('payment_request', 'desired_payment_date', $this->date());
        $this->addColumn('payment_request', 'invoice_recepient_affiliate_id', $this->integer());
        $this->addColumn('payment_request', 'bank_account_id', $this->integer());
        $this->addColumn('payment_request', 'has_documents', $this->boolean());
        $this->addColumn('payment_request', 'payment_order_number', $this->string(40));

        $this->addForeignKey(
            'pr_fk_recepient',
            'payment_request',
            'invoice_recepient_affiliate_id',
            'affiliate',
            'id'
        );

        $this->addForeignKey(
            'pr_fk_bank_account',
            'payment_request',
            'bank_account_id',
            'bank_account',
            'id'
        );

        $banks = [
            [
                'short_name' => 'УРАЛСИБ',
                'name' => 'Филиал ПАО «БАНК УРАЛСИБ» в г. Санкт-Петербург',
            ],
            [
                'short_name' => 'ФК Открытие',
                'name' => 'Филиал С-Петербург ПАО Банка «ФК Открытие» г. Санкт-Петербург',
            ],
            [
                'short_name' => 'Сбербанк',
                'name' => 'Северо-Западный банк ПАО «Сбербанк России»',
            ],
        ];

        $banks_hash = [];

        foreach ($banks as $_bank) {
            $bank = new Bank($_bank);
            if (!$bank->save()) {
                print_r($bank->errors);
                return false;
            }
            $banks_hash[$_bank['short_name']] = $bank->id;
        }

        $affiliates_hash = array_column(
            Affiliate::find()
            ->select('short_name, id')
            ->asArray()
            ->all(),
            'id', 'short_name'
        );

        //$affiliate['short_name'] =>
        $bank_accounts = [
            [
                'affiliate_short_name' => 'ЛМ',
                'accounts' => [
                    [
                        'account_number' => '40702810722050000119',
                        'short_name' => 'УРАЛСИБ руб',
                        'name' => 'УРАЛСИБ руб',
                        'bank_id' => $banks_hash['УРАЛСИБ'],
                        'currency_id' => 643,
                    ],
                    [
                        'account_number' => '40702810800050001890',
                        'short_name' => 'ФК Открытие руб',
                        'name' => 'ФК Открытие руб',
                        'bank_id' => $banks_hash['ФК Открытие'],
                        'currency_id' => 643,
                    ],
                    [
                        'account_number' => '40702810555080002465',
                        'short_name' => 'СБ руб',
                        'name' => 'Сбербанк руб',
                        'bank_id' => $banks_hash['Сбербанк'],
                        'currency_id' => 643,
                    ],
                ],
            ],
            [
                'affiliate_short_name' => 'Л',
                'accounts' => [
                    [
                        'account_number' => '40702810339000001719',
                        'short_name' => 'СБ руб',
                        'name' => 'Сбербанк руб',
                        'bank_id' => $banks_hash['Сбербанк'],
                        'currency_id' => 643,
                    ],
                ],
            ],
            [
                'affiliate_short_name' => 'ЛММ',
                'accounts' => [
                    [
                        'account_number' => '40702810755080002728',
                        'short_name' => 'СБ руб',
                        'name' => 'Сбербанк руб',
                        'bank_id' => $banks_hash['Сбербанк'],
                        'currency_id' => 643,
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
        $this->dropColumn('payment_request', 'desired_payment_date');
        $this->dropColumn('payment_request', 'invoice_recepient_affiliate_id');
        $this->dropColumn('payment_request', 'bank_account_id');
        $this->dropColumn('payment_request', 'has_documents');
        $this->dropColumn('payment_request', 'payment_order_number');

        $this->dropTable('bank_account');
        $this->dropTable('bank');
    }
}
