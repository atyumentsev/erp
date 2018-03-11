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
 * @property string $short_name
 * @property string $name
 * @property string $full_name
 * @property string $description
 * @property integer $updated_at
 * @property integer $created_at
 */
class Department extends ActiveRecord
{

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'department';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name'], 'required'],
            [['name', 'full_name', 'description', 'code_1c', 'short_name'], 'string'],
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
    public static function getDropdownList() : array
    {
        $currencies = self::find()
            ->select('id, name')
            ->asArray()
            ->all();

        return [null => ''] + array_column($currencies, 'name', 'id');
    }
}
