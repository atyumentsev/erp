<?php

namespace app\models;

use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;

/**
 * Class CashflowItem
 * @package app\models
 *
 * @property integer $id
 * @property string $code_1c
 * @property string $name
 * @property integer $currency_id
 * @property integer $counteragent_id
 * @property integer $affiliate_id
 * @property string $type
 * @property string $number
 * @property string $signed_at
 * @property integer $updated_at
 * @property integer $created_at
 *
 * @property Currency       $currency
 * @property CounterAgent   $counterAgent
 * @property Affiliate      $affiliate
 */
class Contract extends ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'contract';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name'], 'required'],
            [['name', 'type', 'number', 'code_1c'], 'string'],
            [['currency_id', 'counteragent_id', 'affiliate_id'], 'integer'],
            ['code_1c', 'unique'],
            ['signed_at', 'date', 'format' => 'php:Y-m-d'],
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

    public function getSignedAt()
    {
        if ($this->signed_at === null) {
            return null;
        }
        return date('d.m.Y', strtotime($this->signed_at));
    }

    /* --- Relations ------------------------------------------------------------------------------------------------ */

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCounterAgent()
    {
        return $this->hasOne(CounterAgent::class, ['id' => 'counteragent_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCurrency()
    {
        return $this->hasOne(Currency::class, ['id' => 'currency_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAffiliate()
    {
        return $this->hasOne(Affiliate::class, ['id' => 'affiliate_id']);
    }
}
