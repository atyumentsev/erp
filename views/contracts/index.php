<?php

/**
 * @var $this yii\web\View
 * @var \yii\data\ActiveDataProvider $dataProvider
 * @var \app\models\search\ContractSearch $searchModel
 * @var array $affiliates
 */
use \app\models\Contract;
use \yii\bootstrap\ActiveForm;
use \yii\bootstrap\Html;
use yii\web\JsExpression;
use kartik\select2\Select2;

$this->title = \Yii::t('app', 'Contracts');
$this->params['breadcrumbs'][] = $this->title;
?>
<style>
    #contracts-table td:nth-child(7),
    #contracts-table td:nth-child(9),
    #contract-search-form div {
        white-space: nowrap;
        margin-bottom:10px;
    }

    #contract-search-form div.form-group {
        display: inline;
    }
    #contract-search-form div label{
        margin-top:5px;
    }

    div.contract-search {
        margin-bottom: 25px;
    }

</style>

<div class="contract-search">
    <?php
    $form = ActiveForm::begin([
        'action' => ['index'],
        'layout' => 'default',
        'method' => 'get',
        'options' => ['id' => 'contract-search-form'],
        'fieldConfig' => [
            'template' => "<div class=\"col-md-1\">{label}</div><div class=\"col-md-3\">{input}</div>",
        ],
    ]);
    ?>
    <div class="row">
    <?= $form->field($searchModel, 'name')->label(\Yii::t('app', 'Name')) ?>
    <?= $form->field($searchModel, 'code_1c')->label(\Yii::t('app', '1C Code')) ?>
    </div>
    <div class="row">
    <?= $form->field($searchModel, 'affiliate_id')->dropDownList($affiliates)->label(\Yii::t('app', 'Affiliate')); ?>

    <?php
    $counteragentName = (empty($searchModel->counterAgent)) ? '' : $searchModel->counterAgent->name;
    echo $form->field($searchModel, 'counteragent_id')->label(\Yii::t('app', 'CounterAgent'))
        ->widget(Select2::classname(), [
            'options' => ['placeholder' => \Yii::t('app', 'Search for a counteragent ...')],
            'language' => 'ru',
            'initValueText' => $counteragentName,
            'pluginOptions' => [
                'allowClear' => true,
                'minimumInputLength' => 3,
                'ajax' => [
                    'url' => '/counter-agents/find',
                    'dataType' => 'json',
                    'data' => new JsExpression('function(params) { return {q:params.term}; }')
                ],
                'escapeMarkup' => new JsExpression('function (markup) { return markup; }'),
                'templateResult' => new JsExpression('function(counteragent) { return counteragent.text; }'),
                'templateSelection' => new JsExpression('function(counteragent) { return counteragent.text; }'),
            ],
        ]);
    ?>
    </div>
    <div class="form-group" style="margin-left: 8.5%;">
        <?= Html::submitButton(\Yii::t('app', 'Search'), ['class' => 'btn btn-primary']) ?>
        <?= Html::a(\Yii::t('app', 'Reset'), ['index'], ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>
</div>

<div style="clear:both"></div>

<?php
echo \yii\grid\GridView::widget([
    'tableOptions' => ['class' => 'table'],
    'dataProvider' => $dataProvider,
    'options' => [
        'id' => 'contracts-table',
    ],
    'columns' => [
        [
            'label' => \Yii::t('app', 'ID'),
            'value' => function (Contract $model) {
                return $model->id;
            },
        ],
        [
            'label' => \Yii::t('app', '1C Code'),
            'value' => function (Contract $model) {
                return $model->code_1c;
            },
        ],
        [
            'label' => \Yii::t('app', 'Name'),
            'value' => function (Contract $model) {
                return $model->name;
            },
        ],
        [
            'label' => \Yii::t('app', 'Currency'),
            'value' => function (Contract $model) {
                return $model->currency->code;
            },
        ],
        [
            'label' => \Yii::t('app', 'CounterAgent'),
            'value' => function (Contract $model) {
                return $model->counterAgent->name;
            },
        ],
        [
            'label' => \Yii::t('app', 'Affiliate'),
            'value' => function (Contract $model) {
                return $model->affiliate->name;
            },
        ],
        [
            'label' => \Yii::t('app', 'Type'),
            'value' => function (Contract $model) {
                return $model->type;
            },
        ],
        [
            'label' => \Yii::t('app', 'Number'),
            'value' => function (Contract $model) {
                return $model->number;
            },
        ],
        [
            'label' => \Yii::t('app', 'Signed At'),
            'value' => function (Contract $model) {
                return $model->getSignedAt();
            },
        ],
    ],
]);
