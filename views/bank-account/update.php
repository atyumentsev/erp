<?php

/**
 * @var yii\web\View $this
 * @var app\models\BankAccount $model
 * @var yii\widgets\ActiveForm $form
 * @var array $banks
 * @var array $affiliates
 * @var array $currencies
 */

$this->title = Yii::t('app', 'Update Bank Account: {nameAttribute}', [
    'nameAttribute' => $model->name,
]);
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Bank Accounts'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Update');
?>
<div class="bank-account-update">

    <?= $this->render('_form', [
        'model' => $model,
        'banks' => $banks,
        'affiliates' => $affiliates,
        'currencies' => $currencies,
    ]) ?>

</div>
