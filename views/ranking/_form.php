<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use app\models\Ranking;

/**
 * @var yii\web\View $this
 * @var app\models\Ranking $model
 * @var array $entity_types
 * @var string $entity_type
 * @var array $list
 */
$entityTypeLabel = empty($entity_types[$entity_type]) ? \Yii::t('app', 'Entity') : $entity_types[$entity_type];
?>

<div class="ranking-form">

    <?php $form = ActiveForm::begin(); ?>
<?php if (empty($entity_type)) : ?>
    <?= $form->field($model, 'entity_type')->dropDownList($entity_types) ?>
<?php else : ?>
    <?= $form->field($model, 'entity_type')->hiddenInput()->label(false) ?>
<?php endif; ?>
    <?php
    $entityName = (empty($list[$model->entity_id])) ? '' : $list[$model->entity_id];

    if (in_array($entity_type, [Ranking::ENTITY_TYPE_COUNTERAGENT, Ranking::ENTITY_TYPE_USER])) :
        echo $form->field($model, 'entity_id')->label($entityTypeLabel)
            ->widget(\kartik\select2\Select2::classname(), [
                'options' => ['placeholder' => \Yii::t('app', '')],
                'language' => 'ru',
                'data' => $list,
                'initValueText' => $entityName,
                'pluginOptions' => [
                    'allowClear' => true,
                    'minimumInputLength' => 3,
                ],
            ]);
    else :
        echo $form->field($model, 'entity_id')->dropDownList($list);
    endif;
    ?>

    <?= $form->field($model, 'priority')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Save'), ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
<?php
$this->registerJs(
    '$("#ranking-entity_type").change(function() {
        window.location = "' . $_SERVER['REDIRECT_URL'] . '?id=' . $model->id .'&entity_type=" + $("#ranking-entity_type").val();
    })');