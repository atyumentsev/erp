<?php

/* @var $this \yii\web\View */
/* @var $content string */

use app\widgets\Alert;
use yii\helpers\Html;
use yii\widgets\Breadcrumbs;
use app\assets\AppAsset;
$this->beginPage();
AppAsset::register($this);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">

    <!-- Bootstrap core CSS -->
    <link href="/css/bootstrap.min.css" rel="stylesheet">
    <?php $this->head() ?>

    <!-- Custom styles for this template -->
    <link href="/css/dashboard.css" rel="stylesheet">

    <!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
    <script src="/js/html5shiv.js"></script>
    <script src="/js/respond.min.js"></script>
    <![endif]-->
    <?= Html::csrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    <style>
        .nav-sidebar > .active > a {
            background-color: #428bca;
            color: #fff;
        }
    </style>
</head>

<body>

<?= $this->render('@app/views/top_menu.php'); ?>

<div class="container-fluid">
    <div class="row">
        <!-- LEFT MENU -->
<?php
$left_menu = [
    \Yii::t('app', 'Finance') => [
        ['url' => '/payment-requests/index', 'text' => Yii::t('app', 'Payment Requests')],
        ['url' => '/payment-requests-selection/index', 'text' => Yii::t('app', 'Payment Requests Selection')],
        ['url' => '/budget/index', 'text' => Yii::t('app', 'Budget')],
        ['url' => '/counter-agents/index', 'text' => Yii::t('app', 'Counteragents')],
        ['url' => '/contracts/index', 'text' => Yii::t('app', 'Contracts')],
    ],
    \Yii::t('app', 'Dictionaries') => [
        ['url' => '/user', 'text' => Yii::t('app', 'Users')],
        ['url' => '/cashflow-item', 'text' => Yii::t('app', 'Cashflow Items')],
        ['url' => '/product', 'text' => Yii::t('app', 'Products')],
        ['url' => '/department', 'text' => Yii::t('app', 'Departments')],
        ['url' => '/bank/index', 'text' => Yii::t('app', 'Banks')],
    ],
    \Yii::t('app', 'User Settings') => [
        ['url' => '/user-settings/payment-requests', 'text' => Yii::t('app', 'Payment Requests Fields')],
    ],
];

if (\Yii::$app->user->can('ADMIN')) {
    $left_menu[\Yii::t('app', 'RBAC')] = [
        ['url' => '/rbac/hierarchy', 'text' => Yii::t('app', 'Hierarchy')],
        ['url' => '/rbac/permission-checker', 'text' => Yii::t('app', 'Permission Checker')],
    ];
}

if (\Yii::$app->user->can('bank.view')) {
    $left_menu[\Yii::t('app', 'Finance')][] = ['url' => '/bank-account/index', 'text' => Yii::t('app', 'Bank Accounts')];
}
if (\Yii::$app->user->can('bank.balance.view')) {
    $left_menu[\Yii::t('app', 'Finance')][] = ['url' => '/bank-account/balance', 'text' => Yii::t('app', 'Accounts Balance')];
}
?>
        <div class="col-sm-3 col-md-2 sidebar" style="padding-left: 5px">
            <?php foreach ($left_menu as $name => $block) : ?>
            <h4><?= $name; ?></h4>
            <ul class="nav nav-sidebar">
                <?php foreach ($block as $item) : ?>
                <li class="<?php if ('/' . Yii::$app->request->pathInfo == $item['url']) echo 'active'; ?>"><?= Html::a($item['text'], $item['url']); ?></li>
                <?php endforeach; ?>
            </ul>
            <?php endforeach; ?>
        </div>

        <div class="col-sm-9 col-sm-offset-3 col-md-10 col-md-offset-2 main">

            <?= Breadcrumbs::widget([
                'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
                'options' => [
                    'class' => 'breadcrumb',
                    'style' => 'margin-top:30px',
                ]
            ]) ?>
            <?= Alert::widget() ?>
            <h1 class="page-header"><?= $this->title ?></h1>
            <!-- CONTENTS -->
            <?= $content ?>
            <!-- END OF CONTENTS -->
        </div>
    </div>
</div>

<!-- Bootstrap core JavaScript
================================================== -->
<!-- Placed at the end of the document so the pages load faster -->
<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>

