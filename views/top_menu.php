<?php

use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;

?>
<style>
    .container {
        width: auto !important;
    }
</style>

<?php
NavBar::begin([
    'brandLabel' => Yii::$app->name,
    'brandUrl' => Yii::$app->homeUrl,
    'options' => [
        'class' => 'navbar navbar-inverse navbar-fixed-top',
        'role' => 'navigation',
    ],
]);

$locales = [
    'ru-RU' => '/img/locales/rus.gif',
    'en-US' => '/img/locales/eng.gif',
];

$items = [
    //['label' => 'Home', 'url' => ['/site/index']],
    Yii::$app->user->isGuest ? (
    ['label' => \Yii::t('app', 'Login'), 'url' => ['/site/login']]
    ) : (
    ['label' => \Yii::t('app', 'Logout ({username})', ['username' => Yii::$app->user->identity->username]), 'url' => ['/site/logout']]
    )
];

foreach ($locales as $locale => $img) {
    $items[] = [
        'label' => '<img src="' . $img . '">',
        'url' => ['/site/switch-locale', 'locale' => $locale, 'ret_url' => \Yii::$app->request->url],
        'encode' => false,
        'linkOptions' => ['style' => 'padding-top: 12px; padding-bottom: 12px'],
    ];
}

echo Nav::widget([
    'options' => ['class' => 'navbar-nav navbar-right'],
    'items' => $items,
]);
NavBar::end();
