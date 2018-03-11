<?php

/**
 * @var $this yii\web\View
 * @var \app\models\CashflowItem[] $items
 */
use \app\models\CashflowItem;

$this->title = \Yii::t('app', 'Cashflow Items');
$this->params['breadcrumbs'][] = $this->title;

echo \yii\grid\GridView::widget([
    'tableOptions' => ['class' => 'table'],
    'dataProvider' => new \yii\data\ArrayDataProvider([
        'allModels'  => $items,
        'pagination' => [
            'pageSize' => 20,
        ],
    ]),
    'columns' => [
        [
            'label' => \Yii::t('app', 'ID'),
            'value' => function (CashflowItem $model) {
                return $model->id;
            },
        ],
        [
            'label' => \Yii::t('app', 'Name'),
            'value' => function (CashflowItem $model) {
                return $model->name;
            },
        ],
        [
            'label' => \Yii::t('app', 'Parent Name'),
            'value' => function (CashflowItem $model) {
                return $model->parent_name;
            },
        ],
        [
            'label' => \Yii::t('app', 'Description'),
            'value' => function (CashflowItem $model) {
                return $model->description;
            },
        ],
        [
            'label' => \Yii::t('app', '1C Code'),
            'value' => function (CashflowItem $model) {
                return $model->code_1c;
            },
        ],
    ],
]);
