<?php

use yii\helpers\Html;
use yii\grid\GridView;
use \app\models\BankAccount;

/**
 * @var yii\web\View $this
 * @var yii\data\ActiveDataProvider $dataProvider
 * @var bool $i_can_view
 * @var bool $i_can_update
 * @var bool $i_can_create
 * @var bool $i_can_delete
 */

$this->title = Yii::t('app', 'Bank Accounts');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="bank-account-index">

    <p>
        <?= Html::a(Yii::t('app', 'Create Bank Account'), ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            'id',
            [
                'label' => \Yii::t('app', 'Affiliate'),
                'value' => function (BankAccount $model) {
                    return $model->affiliate->name;
                }
            ],
            [
                'label' => \Yii::t('app', 'Bank'),
                'value' => function (BankAccount $model) {
                    return $model->bank->name;
                }
            ],
            'short_name',
            'name',
            'account_number',
            [
                'label' => \Yii::t('app', 'Currency'),
                'value' => function (BankAccount $model) {
                    return $model->currency->code;
                }
            ],
            [
                'class' => yii\grid\ActionColumn::class,
                'template' => '{view} {update} {delete}',
                'visibleButtons' => [
                    'view' => $i_can_view,
                    'update' => $i_can_update,
                    'delete' => $i_can_delete,
                ],
            ],
        ],
    ]); ?>
</div>
