<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model app\models\LoginForm */

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

$this->title = \Yii::t('app', 'Login');
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="row">
<div class="col-md-3  col-md-offset-2">
<img src="/img/lumex_logo.jpg">
</div>
</div>
<div class="row">
    <div class="col-md-3 col-md-offset-4">

    <h1><?= Html::encode($this->title) ?></h1>
    <p style="padding-top: 20px;padding-bottom:10px"><?= \Yii::t('app', 'Please fill out the following fields to login:'); ?></p>

    <?php $form = ActiveForm::begin([
        'id' => 'login-form',
        'layout' => 'horizontal',
        'fieldConfig' => [
            'template' => "<div class=\"col-lg-4\">{label}</div>\n<div class=\"col-lg-8\">{input}</div>\n<div class=\"col-md-offset-4 col-xs-8\">{error}</div>",
            'labelOptions' => ['class' => 'col-lg-1 control-label'],
        ],
    ]); ?>

        <?= $form->field($model, 'username')->textInput(['autofocus' => true])->label(\Yii::t('app', 'Username')) ?>

        <?= $form->field($model, 'password')->passwordInput()->label(\Yii::t('app', 'Password')) ?>

        <?= $form->field($model, 'rememberMe')->checkbox([
            'template' => "<div class=\"col-lg-offset-4 col-lg-4\">{input} {label}</div>\n<div class=\"col-lg-8\">{error}</div>",
        ])->label(\Yii::t('app', 'Remember Me')) ?>

        <div class="form-group">
            <div class="col-lg-offset-4 col-lg-11">
                <?= Html::submitButton(\Yii::t('app', 'Log In'), ['class' => 'btn btn-primary', 'name' => 'login-button']) ?>
            </div>
        </div>

    <?php ActiveForm::end(); ?>
    </div>
</div>
