<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Ranking Tuning');
$this->params['breadcrumbs'][] = $this->title;
?>
<?= $this->render('_tabs', [
    'active_tab' => 'index',
]) ?>
<div class="ranking-index">

    <p>
        <?= Html::a(Yii::t('app', 'Create Ranking'), ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            'id',
            'entityTypeReadable',
            'entityName',
            'priority',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
</div>
