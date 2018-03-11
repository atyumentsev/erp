<?php

namespace app\models;

use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "budget_period".
 *
 * @property int $id
 * @property string $name
 * @property string $date_start
 * @property string $date_finish
 * @property int $created_at
 * @property int $updated_at
 *
 * @property Budget[] $budgets
 */
class BudgetPeriod extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'budget_period';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name', 'date_start', 'date_finish'], 'required'],
            [['date_start', 'date_finish'], 'date'],
            [['created_at', 'updated_at'], 'default', 'value' => null],
            [['created_at', 'updated_at'], 'integer'],
            [['name'], 'string', 'max' => 255],
            [['name'], 'unique'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => \Yii::t('app', 'Name'),
            'date_start' => \Yii::t('app', 'Date Start'),
            'date_finish' => \Yii::t('app', 'Date Finish'),
            'created_at' => \Yii::t('app', 'Created At'),
            'updated_at' => \Yii::t('app', 'Updated At'),
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
     * @return \yii\db\ActiveQuery
     */
    public function getBudgets()
    {
        return $this->hasMany(Budget::className(), ['budget_period_id' => 'id']);
    }

    public static function getBudgetPeriod($due_date)
    {
        $period = self::find()
            ->where('date_start <= :due_date AND date_finish >= :due_date', ['due_date' => $due_date])
            ->one();
        return $period;
    }
}
