<?php

/**
 * @var $this yii\web\View
 * @var Product[] $products
 */
use \app\models\Product;

$this->title = \Yii::t('app', 'Products');
$this->params['breadcrumbs'][] = $this->title;

echo \yii\grid\GridView::widget([
    'tableOptions' => ['class' => 'table'],
    'dataProvider' => new \yii\data\ArrayDataProvider([
        'allModels'  => $products,
        'pagination' => [
            'pageSize' => 20,
        ],
    ]),
    'columns' => [
        [
            'label' => \Yii::t('app', 'ID'),
            'value' => function (Product $model) {
                return $model->id;
            },
        ],
        [
            'label' => \Yii::t('app', 'Name'),
            'value' => function (Product $model) {
                return $model->name;
            },
        ],
        [
            'label' => \Yii::t('app', 'Description'),
            'value' => function (Product $model) {
                return $model->description;
            },
        ],
        [
            'label' => \Yii::t('app', '1C Code'),
            'value' => function (Product $model) {
                return $model->code_1c;
            },
        ],
    ],
]);
