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
 * @property string $full_name
 * @property string $inn
 * @property string $kpp
 * @property string $ib_code
 * @property string $type
 * @property integer $updated_at
 * @property integer $created_at
 */
class CounterAgent extends ActiveRecord
{

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'counteragent';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name'], 'required'],
            [['name', 'full_name', 'inn', 'kpp', 'type', 'code_1c'], 'string'],
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
