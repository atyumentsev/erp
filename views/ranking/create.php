<?php

/**
 * @var yii\web\View $this
 * @var app\models\Ranking $model
 * @var array $entity_types
 * @var string $entity_id
 * @var string $entity_type
 * @var array $list
 */

$this->title = Yii::t('app', 'Create Ranking');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Rankings'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="ranking-create">

    <?= $this->render('_form', [
        'model' => $model,
        'entity_types' => $entity_types,
        'entity_type' => $entity_type,
        'entity_id' => $entity_id,
        'list' => $list,
    ]) ?>

</div>
