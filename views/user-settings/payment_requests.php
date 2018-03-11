<?php
use yii\helpers\Url;
/**
 * @var $this yii\web\View
 * @var array $active_fields
 * @var array $available_fields
 */

$this->title = \Yii::t('app', 'Payment Requests Fields');
$this->params['breadcrumbs'][] = $this->title;

?>

<form method="post" action="payment-requests-save">
<?php foreach ($available_fields as $field => $name): ?>
    <div class="row">
        <div class="col-md-1 col-md-offset-1"><input type="checkbox" name="<?=$field?>" id="<?=$field?>"<?= in_array($field, $active_fields) ? ' checked' : ''?>></div>
        <div class="col-md-6"><label for="<?=$field?>"><?=$name ?></label></div>
    </div>
<?php endforeach; ?>

<input type="submit" value="<?=\Yii::t('app', 'Save'); ?>" class="btn-success btn">
<a style="margin-bottom: 0;" class="btn" href="<?= Url::to('/payment-requests')?>"><?=\Yii::t('app', 'Back to Payment Requests'); ?></a>
</form>

