<?php

use app\models\BudgetPeriod;
use app\models\CashflowItem;
use app\models\Currency;
use app\models\search\BudgetSearch;
use yii\bootstrap\ActiveForm;
use yii\bootstrap\Html;

/**
 * @var yii\web\View $this
 * @var array $cfr_list
 * @var integer $cfr_id
 * @var BudgetSearch $model
 * @var BudgetPeriod[] $budget_periods
 * @var Currency $currency
 * @var CashflowItem[] $cashflow_items
 * @var array $budget_items_hash
 */

$this->title = Yii::t('app', 'Budget for {cfr}', ['cfr' => $cfr_list[$cfr_id]]);
$this->params['breadcrumbs'][] = $this->title;
?>

<?php
$form = ActiveForm::begin([
    'action' => '/budget/index',
    'layout' => 'horizontal',
    'method' => 'get',
    'options' => ['id' => 'budget-show-form'],
    'fieldConfig' => [
        'template' => "<div class=\"col-md-5\" style='white-space: nowrap'>{label}</div><div class=\"col-md-6\">{input}</div>",
    ],
]);
?>
<style>
    #budget-show-table td:nth-child(2),
    #budget-show-table td:nth-child(3),
    #budget-show-table td:nth-child(4),
    #budget-show-table td:nth-child(5)
    {
        text-align: right;
    }

    #budget-show-table th:nth-child(2),
    #budget-show-table th:nth-child(3),
    #budget-show-table th:nth-child(4),
    #budget-show-table th:nth-child(5)
    {
        text-align: center;
    }
</style>
<div class="row">
    <div class="col-md-6">
    <?= $form->field($model, 'budget_cfr_id')->dropDownList($cfr_list)->label(\Yii::t('app', 'Finance Responsibility Center'))->error(false) ?>
    </div>
    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Show'), ['class' => 'btn btn-success']) ?>
    </div>
</div>

<?php ActiveForm::end(); ?>

<table class="table table-striped table-bordered" id="budget-show-table">
    <thead>
    <tr>
    <th><?= \Yii::t('app', 'Cashflow Item') ?></th>
    <?php foreach ($budget_periods as $period) : ?>
    <th><?= $period->name ?></th>
    <?php endforeach; ?>
    </tr>
    </thead>
    <tbody>
    <?php foreach ($cashflow_items as $cashflow_item) : ?>
        <tr>
            <td><?= $cashflow_item->name?>
            <?php foreach ($budget_periods as $period) : ?>
            <td><?= isset($budget_items_hash[$period->id][$cashflow_item->id]) ? $currency->getFormattedAmount($budget_items_hash[$period->id][$cashflow_item->id]) : null ?></td>
            <?php endforeach; ?>
        </tr>
    <?php endforeach; ?>
    </tbody>
</table>
