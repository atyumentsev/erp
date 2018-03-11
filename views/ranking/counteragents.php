<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Ranking Tuning');
$this->params['breadcrumbs'][] = $this->title;
?>
<?= $this->render('_tabs', [
    'active_tab' => 'counteragents',
]) ?>
<div class="ranking-index">

    <p>
        <?= Html::a(
            Yii::t('app', 'Create Ranking for CounterAgent'),
            ['create', 'entity_type' => 'counteragent'],
            ['class' => 'btn btn-success']
        ) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            [
                'label' => \Yii::t('app', 'Ranking ID'),
                'value' => function ($arr) { return $arr['id']; }
            ],
            [
                'label' => \Yii::t('app', 'CounterAgent'),
                'value' => function ($arr) { return $arr['name']; }
            ],
            [
                'label' => \Yii::t('app', 'Priority'),
                'value' => function ($arr) { return $arr['priority']; }
            ],
            [
                'class' => yii\grid\ActionColumn::class,
                'template' => '{update} {delete}',
            ],
        ],
    ]); ?>
</div>
