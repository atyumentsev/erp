<?php

namespace app\models\forms;

use app\models\BankAccount;
use app\models\BankAccountBalance;

class BankAccountBalanceForm extends BankAccountBalance
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['date'], 'safe'],
            [['bank_account_id'], 'required'],
            [['bank_account_id', 'balance', 'paid'], 'integer'],
            [['balanceReadable'], 'double'],
            [['date', 'bank_account_id'], 'unique', 'targetAttribute' => ['date', 'bank_account_id']],
            [['bank_account_id'], 'exist', 'skipOnError' => true, 'targetClass' => BankAccount::className(), 'targetAttribute' => ['bank_account_id' => 'id']],
        ];
    }

    /**
     * @return float
     */
    public function getBalanceReadable()
    {
        return $this->bankAccount->currency->getMoneyAmountFromUnits($this->balance);
    }

    /**
     * @param $balance
     */
    public function setBalanceReadable($balance)
    {
        $this->balance = $this->bankAccount->currency->getMoneyAmountInUnits($balance);
    }
}
