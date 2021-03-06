<?php

use yii\helpers\Html;
use yii\grid\GridView;

/**
 * @var yii\web\View $this
 * @var yii\data\ActiveDataProvider $dataProvider
 * @var bool $i_can_view
 * @var bool $i_can_update
 * @var bool $i_can_create
 * @var bool $i_can_delete
 */

$this->title = Yii::t('app', 'Cashflow Items');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="cashflow-item-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a(Yii::t('app', 'Create Cashflow Item'), ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            'code_1c',
            'parent_name',
            'short_name',
            'name',
            //'full_name',
            //'description',
            //'flags',
            //'created_at',
            //'updated_at',

            [
                'class' => yii\grid\ActionColumn::class,
                'template' => '{view} {update} {delete}',
                'visibleButtons' => [
                    'view' => $i_can_view,
                    'update' => $i_can_update,
                    'delete' => $i_can_delete,
                ],
            ],
        ],
    ]); ?>
</div>
