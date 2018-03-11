<?php

/* @var $this yii\web\View */
/* @var $model app\models\CashflowItem */

$this->title = Yii::t('app', 'Create Cashflow Item');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Cashflow Items'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="cashflow-item-create">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
