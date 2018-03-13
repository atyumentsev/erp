<?php

namespace app\models;

use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;

/**
 * Class CashflowItem
 * @package app\models
 *
 * @property integer $id
 * @property string $code
 * @property string $name
 * @property string $fullname
 * @property string $description
 * @property integer $updated_at
 * @property integer $created_at
 */
class Currency extends ActiveRecord
{
    const CODE_CAD = 124;
    const CODE_CNY = 156;
    const CODE_HKD = 344;
    const CODE_JPY = 392;
    const CODE_RUB = 643;
    const CODE_GBP = 826;
    const CODE_USD = 840;
    const CODE_EUR = 978;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'currency';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'code', 'name', 'units'], 'required'],
            [['id', 'units', 'created_at', 'updated_at'], 'integer'],
            ['code', 'string', 'max' => 5],
            ['name', 'string', 'max' => 100],
            ['sign', 'string', 'max' => 10],
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
     * Конверт суммы из старших единиц в юниты
     *
     * @param $amount
     *
     * @return mixed
     */
    public function getMoneyAmountInUnits($amount)
    {
        return $amount * pow(10, $this->units);
    }

    /**
     * Конверт суммы из юнитов в старшие единицы
     *
     * @param $amountInUnits
     *
     * @return float
     */
    public function getMoneyAmountFromUnits($amountInUnits)
    {
        return round($amountInUnits / pow(10, $this->units), 2);
    }


    /**
     * Get currency as formatted string
     *
     * @param int $amount
     * @param bool $withSign
     * @param string $decPoint
     * @param string $thousandsSep
     * @return string
     */
    public function getFormattedAmount($amount, $withSign = false, $decPoint = '.', $thousandsSep = ',')
    {
        $res = $withSign
            ? html_entity_decode('&#'.hexdec($this->sign) . ';')
            : '';
        $res .= number_format($this->getMoneyAmountFromUnits($amount), $this->units, $decPoint, $thousandsSep);

        return $res;
    }

    /**
     * @return array
     */
    public static function getAllCurrenciesAsCodes()
    {
        return self::find()->select('code')->indexBy('id')->column();
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
