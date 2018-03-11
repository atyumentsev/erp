<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\CashflowItem */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="cashflow-item-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'code_1c')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'short_name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'full_name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'description')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'flags')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Save'), ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
