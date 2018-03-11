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
 * @property string $short_name
 * @property string $name
 * @property string $full_name
 * @property string $description
 * @property integer $flags
 * @property integer $updated_at
 * @property integer $created_at
 */
class CashflowItem extends ActiveRecord
{
    const FLAG_FOR_INVOICES     = 0x1;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'cashflow_item';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name'], 'required'],
            [['name', 'full_name', 'description', 'code_1c', 'short_name'], 'string'],
            ['flags', 'integer'],
            ['name', 'unique'],
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
    public static function getInvoiceDropdownList() : array
    {
        $currencies = self::find()
            ->select('id, name')
            ->where(new Expression('flags & ' . self::FLAG_FOR_INVOICES . ' > 0'))
            ->orderBy('id ASC')
            ->asArray()
            ->all();

        return [null => ''] + array_column($currencies, 'name', 'id');
    }

    public static function getDropdownList() : array
    {
        $items = self::find()
            ->select('id, name')
            ->orderBy('name')
            ->asArray()
            ->all();

        return [null => ''] + array_column($items, 'name', 'id');
    }
}
