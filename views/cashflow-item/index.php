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

<?php if ($i_can_create) : ?>
    <p>
        <?= Html::a(Yii::t('app', 'Create Cashflow Item'), ['create'], ['class' => 'btn btn-success']) ?>
    </p>
<?php endif; ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            'id',
            'code_1c',
            'short_name',
            'name',
            'parent_name',
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
