<?php

/* @var $this yii\web\View */
/* @var $model app\models\Budget */

$this->title = Yii::t('app', 'Update Budget: {nameAttribute}', [
    'nameAttribute' => $model->id,
]);
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Budgets'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Update');
?>
<div class="budget-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
