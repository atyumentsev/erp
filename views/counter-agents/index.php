<?php

/**
 * @var $this yii\web\View
 * @var \yii\data\ActiveDataProvider $dataProvider
 * @var \app\models\search\CounterAgentSearch $searchModel
 */
use \app\models\CounterAgent;
use \yii\bootstrap\ActiveForm;
use \yii\bootstrap\Html;


$this->title = \Yii::t('app', 'Counteragents');
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="counter-agent-search">
    <?php
    $form = ActiveForm::begin([
        'action' => ['index'],
        'layout' => 'horizontal',
        'method' => 'get',
        'fieldConfig' => [
            'template' => "<div class=\"col-lg-1\" style='white-space:nowrap;'>{label}</div>\n<div class=\"col-lg-4\">{input}</div>",
            //'labelOptions' => ['class' => 'col-lg-1 control-label'],
        ],
    ]);
    ?>

    <?= $form->field($searchModel, 'name')->label(\Yii::t('app', 'Name')) ?>
    <?= $form->field($searchModel, 'code_1c')->label(\Yii::t('app', '1C Code')) ?>

    <div class="form-group" style="margin-left: 8.5%">
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
    'columns' => [
        [
            'label' => \Yii::t('app', 'ID'),
            'value' => function (CounterAgent $model) {
                return $model->id;
            },
        ],
        [
            'label' => \Yii::t('app', 'Name'),
            'value' => function (CounterAgent $model) {
                return $model->name;
            },
        ],
        [
            'label' => \Yii::t('app', 'INN'),
            'value' => function (CounterAgent $model) {
                return $model->inn;
            },
        ],
        [
            'label' => \Yii::t('app', 'KPP'),
            'value' => function (CounterAgent $model) {
                return $model->kpp;
            },
        ],
        [
            'label' => \Yii::t('app', '1C Code'),
            'value' => function (CounterAgent $model) {
                return $model->code_1c;
            },
        ],
        [
            'label' => \Yii::t('app', 'IB Code'),
            'value' => function (CounterAgent $model) {
                return $model->ib_code;
            },
        ],
        [
            'label' => \Yii::t('app', 'Type'),
            'value' => function (CounterAgent $model) {
                return $model->type;
            },
        ],
        [
            'label' => \Yii::t('app', 'Full Name'),
            'value' => function (CounterAgent $model) {
                return $model->full_name;
            },
        ],
    ],
]);
