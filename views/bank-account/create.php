<?php

/**
 * @var yii\web\View $this
 * @var app\models\BankAccount $model
 * @var yii\widgets\ActiveForm $form
 * @var array $banks
 * @var array $affiliates
 * @var array $currencies
 */

$this->title = Yii::t('app', 'Create Bank Account');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Bank Accounts'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="bank-account-create">

    <?= $this->render('_form', [
        'model' => $model,
        'banks' => $banks,
        'affiliates' => $affiliates,
        'currencies' => $currencies,
    ]) ?>

</div>
