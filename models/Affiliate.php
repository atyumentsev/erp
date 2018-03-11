<?php

namespace app\models;

use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
use yii\db\Expression;

/**
 * Class CashflowItem
 * @package app\models
 *
 * @property integer $id
 * @property string $code_1c
 * @property string $name
 * @property string $short_name
 * @property string $prefix
 * @property integer $flags
 * @property integer $updated_at
 * @property integer $created_at
 */
class Affiliate extends ActiveRecord
{
    const FLAG_PAYERS = 0x1;

    public $signedAtRaw;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'affiliate';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name'], 'required'],
            [['name', 'prefix', 'code_1c', 'short_name'], 'string'],
            [['flags'], 'integer'],
            ['code_1c', 'unique'],
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

    /**
     * @return array
     */
    public static function getPayerDropdownList() : array
    {
        $currencies = self::find()
            ->select('id, name')
            ->where(new Expression('flags & ' . self::FLAG_PAYERS . ' > 0'))
            ->asArray()
            ->all();

        return [null => ''] + array_column($currencies, 'name', 'id');
    }
}
