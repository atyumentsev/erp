<?php

use yii\helpers\Url;
use yii\grid\GridView;

/**
 * @var yii\web\View $this
 * @var yii\data\ActiveDataProvider $dataProvider
 * @var bool $i_can_create
 * @var bool $i_can_update
 * @var bool $i_can_delete
 */

$this->title = Yii::t('app', 'Ranking Tuning');
$this->params['breadcrumbs'][] = $this->title;
?>
<?= $this->render('_tabs', [
    'active_tab' => 'cashflow_items',
]) ?>
<div class="ranking-index">

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            [
                'label' => \Yii::t('app', 'Cashflow Item'),
                'value' => function ($arr) { return $arr['name']; }
            ],
            [
                'label' => \Yii::t('app', 'Priority'),
                'value' => function ($arr) { return $arr['priority']; }
            ],
            [
                'class' => yii\grid\ActionColumn::class,
                'template' => '{update} {delete}',
                'visibleButtons' => [
                    'update' => $i_can_update,
                    'delete' => function ($model) use($i_can_delete) {
                        return !empty($model['id']) && $i_can_delete;
                    },
                ],
                'urlCreator' => function ($action, $model) {
                    if (empty($model['id'])) {
                        return Url::to(['create', 'entity_type' => 'cashflow_item', 'entity_id' => $model['entity_id']]);
                    } else {
                        return Url::to([$action, 'id' => $model['id']]);
                    }
                },
            ],
        ],
    ]); ?>
</div>
