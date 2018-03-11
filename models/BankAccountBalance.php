<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "bank_account_balance".
 *
 * @property int $id
 * @property string $date
 * @property int $bank_account_id
 * @property int $balance
 * @property int $paid
 *
 * @property BankAccount $bankAccount
 */
class BankAccountBalance extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'bank_account_balance';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['date'], 'safe'],
            [['bank_account_id'], 'required'],
            [['bank_account_id', 'balance', 'paid'], 'default', 'value' => null],
            [['bank_account_id', 'balance', 'paid'], 'integer'],
            [['date', 'bank_account_id'], 'unique', 'targetAttribute' => ['date', 'bank_account_id']],
            [['bank_account_id'], 'exist', 'skipOnError' => true, 'targetClass' => BankAccount::className(), 'targetAttribute' => ['bank_account_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'date' => Yii::t('app', 'Date'),
            'bank_account_id' => Yii::t('app', 'Bank Account ID'),
            'balance' => Yii::t('app', 'Balance'),
            'paid' => Yii::t('app', 'Paid'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getBankAccount()
    {
        return $this->hasOne(BankAccount::className(), ['id' => 'bank_account_id']);
    }
}
