<?php

use yii\helpers\Url;

/**
 * @var yii\web\View $this
 * @var array $currencies
 * @var array $products
 * @var array $cashflow_items
 * @var array $departments
 * @var array $affiliates
 * @var array $users
 * @var array $payers
 * @var array $urgency
 * @var array $bank_accounts_hash
 * @var app\models\forms\PaymentRequestForm $model
 */
$this->params['breadcrumbs'][] = [
    'label' => \Yii::t('app', 'Payment Requests'),
    'url' => ['/payment-requests/index'],
];

$this->title = \Yii::t('app', 'Update Payment Request #' . $model->internal_number);
$this->params['breadcrumbs'][] = $this->title;
?>

<?php
echo $this->render('_form', [
    'currencies'        => $currencies,
    'products'          => $products,
    'cashflow_items'    => $cashflow_items,
    'departments'       => $departments,
    'users'             => $users,
    'payers'            => $payers,
    'urgency'           => $urgency,
    'affiliates'        => $affiliates,
    'bank_accounts_hash' => $bank_accounts_hash,
    'model'             => $model,
    'action'            => Url::to(['update', 'id' => $model->id]),
]);
?>
