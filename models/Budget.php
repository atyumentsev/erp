<?php

namespace app\models;

use Yii;
use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "budget".
 *
 * @property int $id
 * @property int $budget_period_id
 * @property int $cashflow_item_id
 * @property int $budget_cfr_id
 * @property int $amount
 * @property int $created_at
 * @property int $updated_at
 *
 * @property BudgetCfr $budgetCfr
 * @property BudgetPeriod $budgetPeriod
 * @property CashflowItem $cashflowItem
 */
class Budget extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'budget';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['budget_period_id', 'cashflow_item_id', 'budget_cfr_id', 'amount'], 'required'],
            [['budget_period_id', 'cashflow_item_id', 'budget_cfr_id', 'amount', 'created_at', 'updated_at'], 'integer'],
            [['budget_period_id', 'cashflow_item_id', 'budget_cfr_id'], 'unique', 'targetAttribute' => ['budget_period_id', 'cashflow_item_id', 'budget_cfr_id']],
            [['budget_cfr_id'], 'exist', 'skipOnError' => true, 'targetClass' => BudgetCfr::className(), 'targetAttribute' => ['budget_cfr_id' => 'id']],
            [['budget_period_id'], 'exist', 'skipOnError' => true, 'targetClass' => BudgetPeriod::className(), 'targetAttribute' => ['budget_period_id' => 'id']],
            [['cashflow_item_id'], 'exist', 'skipOnError' => true, 'targetClass' => CashflowItem::className(), 'targetAttribute' => ['cashflow_item_id' => 'id']],
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
            'budget_period_id' => Yii::t('app', 'Budget Period ID'),
            'cashflow_item_id' => Yii::t('app', 'Cashflow Item ID'),
            'budget_cfr_id' => Yii::t('app', 'Budget Cfr ID'),
            'amount' => Yii::t('app', 'Amount'),
            'created_at' => Yii::t('app', 'Created At'),
            'updated_at' => Yii::t('app', 'Updated At'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getBudgetCfr()
    {
        return $this->hasOne(BudgetCfr::className(), ['id' => 'budget_cfr_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getBudgetPeriod()
    {
        return $this->hasOne(BudgetPeriod::className(), ['id' => 'budget_period_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCashflowItem()
    {
        return $this->hasOne(CashflowItem::className(), ['id' => 'cashflow_item_id']);
    }
}
