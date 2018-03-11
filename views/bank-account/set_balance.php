<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/**
 * @var yii\web\View $this
 * @var app\models\BankAccountBalance $model
 * @var string $date
 * @var yii\widgets\ActiveForm $form
 */

$this->title = Yii::t('app', 'Set Balance for {name}, {date}', [
    'name' => $model->bankAccount->name,
    'date' => $date,
]);
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Bank Accounts'), 'url' => ['balance']];
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="balance-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'balanceReadable')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Save'), ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
