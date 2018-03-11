<?php

/* @var $this yii\web\View */
/* @var $model app\models\Budget */

$this->title = Yii::t('app', 'Create Budget');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Budgets'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="budget-create">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
