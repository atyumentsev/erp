<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model app\models\LoginForm */

use yii\helpers\Html;

$this->title = \Yii::t('app', 'Login success');
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
    </div>
</div>
