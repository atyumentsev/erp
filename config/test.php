<?php

/**
 * Application configuration shared by all test types
 */

$config = require __DIR__ . '/web.php';

$test_config = [
    'id' => 'basic-tests',
    'components' => [
        'db' => require __DIR__ . '/test_db.php',
        'mailer' => [
            'useFileTransport' => true,
        ],
        'request' => [
            'cookieValidationKey' => 'test',
            'enableCsrfValidation' => false,
        ],
    ],
];

return \yii\helpers\ArrayHelper::merge($config, $test_config);
