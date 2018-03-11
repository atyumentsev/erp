<?php

namespace app\models;

use Yii;
use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "bank_account".
 *
 * @property int $id
 * @property int $bank_id
 * @property int $affiliate_id
 * @property string $short_name
 * @property string $name
 * @property int $currency_id
 * @property int $created_at
 * @property int $updated_at
 *
 * @property Bank $bank
 * @property Currency $currency
 * @property Affiliate $affiliate
 * @property PaymentRequest[] $paymentRequests
 */
class BankAccount extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'bank_account';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['bank_id', 'affiliate_id', 'currency_id', 'account_number', 'short_name', 'name'], 'required'],
            [['bank_id', 'currency_id', 'affiliate_id', 'created_at', 'updated_at'], 'integer'],
            [['short_name'], 'string', 'max' => 30],
            [['account_number'], 'string', 'max' => 100],
            [['name'], 'string', 'max' => 100],
            [['bank_id'], 'exist', 'skipOnError' => true, 'targetClass' => Bank::className(), 'targetAttribute' => ['bank_id' => 'id']],
            [['currency_id'], 'exist', 'skipOnError' => true, 'targetClass' => Currency::className(), 'targetAttribute' => ['currency_id' => 'id']],
            [['affiliate_id'], 'exist', 'skipOnError' => true, 'targetClass' => Affiliate::className(), 'targetAttribute' => ['affiliate_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'timestamp' => [
                'class' => TimestampBehavior::class,
            ],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'bank_id' => Yii::t('app', 'Bank'),
            'currency_id' => Yii::t('app', 'Currency'),
            'affiliate_id' => Yii::t('app', 'Affiliate'),
            'short_name' => Yii::t('app', 'Short Name'),
            'name' => Yii::t('app', 'Name'),
            'account_number' => Yii::t('app', 'Account Number'),
            'created_at' => Yii::t('app', 'Created At'),
            'updated_at' => Yii::t('app', 'Updated At'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getBank()
    {
        return $this->hasOne(Bank::className(), ['id' => 'bank_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAffiliate()
    {
        return $this->hasOne(Affiliate::className(), ['id' => 'affiliate_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCurrency()
    {
        return $this->hasOne(Currency::className(), ['id' => 'currency_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPaymentRequests()
    {
        return $this->hasMany(PaymentRequest::className(), ['bank_account_id' => 'id']);
    }

    /**
     * @return array
     */
    public static function getDropdownList() : array
    {
        $currencies = self::find()
            ->select('id, name')
            ->asArray()
            ->all();

        return [null => ''] + array_column($currencies, 'name', 'id');
    }
}
