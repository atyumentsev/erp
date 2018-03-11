<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/**
 * @var yii\web\View $this
 * @var app\models\BankAccount $model
 * @var yii\widgets\ActiveForm $form
 * @var array $banks
 * @var array $affiliates
 * @var array $currencies
 */
?>

<div class="bank-account-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'bank_id')->dropDownList($banks) ?>
    <?= $form->field($model, 'affiliate_id')->dropDownList($affiliates) ?>
    <?= $form->field($model, 'currency_id')->dropDownList($currencies) ?>

    <?= $form->field($model, 'short_name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>
    <?= $form->field($model, 'account_number')->textInput(['maxlength' => true]) ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Save'), ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
