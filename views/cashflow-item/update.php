<?php

/* @var $this yii\web\View */
/* @var $model app\models\CashflowItem */

$this->title = Yii::t('app', 'Update Cashflow Item: {nameAttribute}', [
    'nameAttribute' => $model->name,
]);
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Cashflow Items'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Update');
?>
<div class="cashflow-item-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
