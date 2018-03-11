<?php

/**
 * @var yii\web\View $this
 * @var app\models\Ranking $model
 * @var array $entity_types
 * @var string $entity_id
 * @var string $entity_type
 * @var array $list
 */

$this->title = Yii::t('app', 'Update Ranking: {nameAttribute}', [
    'nameAttribute' => $model->id,
]);
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Rankings'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Update');
?>
<div class="ranking-update">

    <?= $this->render('_form', [
        'model' => $model,
        'entity_types' => $entity_types,
        'entity_type' => $entity_type,
        'entity_id' => $entity_id,
        'list' => $list,
    ]) ?>

</div>
