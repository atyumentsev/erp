<?php

/**
 * @var $this yii\web\View
 * @var \app\models\User[] $users
 */
use \app\models\User;

$this->title = \Yii::t('app', 'Users');
$this->params['breadcrumbs'][] = $this->title;

echo \yii\grid\GridView::widget([
    'tableOptions' => ['class' => 'table'],
    'dataProvider' => new \yii\data\ArrayDataProvider([
        'allModels'  => $users,
        'pagination' => [
            'pageSize' => 20,
        ],
    ]),
    //'layout' => "{items}",
    'columns' => [
        [
            'label' => \Yii::t('app', 'ID'),
            'value' => function (User $model) {
                return $model->id;
            },
        ],
        [
            'label' => \Yii::t('app', 'Username'),
            'value' => function (User $model) {
                return $model->username;
            },
        ],
        [
            'label' => \Yii::t('app', 'Name'),
            'value' => function (User $model) {
                return $model->name;
            },
        ],
        [
            'label' => \Yii::t('app', '1C Code'),
            'value' => function (User $model) {
                return $model->code_1c;
            },
        ],
        [
            'label' => \Yii::t('app', 'Parent Name'),
            'value' => function (User $model) {
                return $model->parent_name;
            },
        ],
    ],
]);
