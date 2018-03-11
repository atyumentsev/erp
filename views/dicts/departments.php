<?php

/**
 * @var $this yii\web\View
 * @var \app\models\Department[] $departments
 */
use \app\models\Department;

$this->title = \Yii::t('app', 'Departments');
$this->params['breadcrumbs'][] = $this->title;

echo \yii\grid\GridView::widget([
    'tableOptions' => ['class' => 'table'],
    'dataProvider' => new \yii\data\ArrayDataProvider([
        'allModels'  => $departments,
        'pagination' => [
            'pageSize' => 20,
        ],
    ]),
    'columns' => [
        [
            'label' => \Yii::t('app', 'ID'),
            'value' => function (Department $model) {
                return $model->id;
            },
        ],
        [
            'label' => \Yii::t('app', 'Short Name'),
            'value' => function (Department $model) {
                return $model->short_name;
            },
        ],
        [
            'label' => \Yii::t('app', 'Name'),
            'value' => function (Department $model) {
                return $model->name;
            },
        ],
    ],
]);
