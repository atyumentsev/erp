<?php

$params = require __DIR__ . '/params.php';
$db = require __DIR__ . '/db.php';

if (!function_exists('l')) {
    function l($data, $show_file_line = true, $clear_file = false)
    {
        //$log_file = '/home/atyumentsev/log.txt';
        $project_home = dirname(dirname(dirname(__FILE__))) . '/';
        $log_file = $project_home . 'log.txt';

        if (!is_writable($log_file)) {
            return;
        }
        //if (is_file('/home/atyumentsev/log.txt')) {
        if ($show_file_line) {
            $debug = debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, 2);
            file_put_contents($log_file, date("Y-m-d H:i:s") . " PID: " . posix_getpid() . " VAR: " . str_replace($project_home, '', $debug[0]['file']) . ":" . $debug[0]['line'] . PHP_EOL, $clear_file ? null : FILE_APPEND);
        }
        file_put_contents($log_file, date("Y-m-d H:i:s") . " PID: " . posix_getpid() . " VAR: " . var_export($data, true) . PHP_EOL . PHP_EOL, $clear_file ? null : FILE_APPEND);
        //}
    }
}


$config = [
    'id' => 'basic',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log'],
    'aliases' => [
        '@bower' => '@vendor/bower-asset',
        '@npm'   => '@vendor/npm-asset',
    ],
    'components' => [
        'request' => [
            // !!! insert a secret key in the following (if it is empty) - this is required by cookie validation
            'cookieValidationKey' => 'qwer',
        ],
        'cache' => [
            'class' => 'yii\caching\FileCache',
        ],
        'user' => [
            'identityClass' => 'app\models\User',
            'enableAutoLogin' => true,
        ],
        'errorHandler' => [
            'errorAction' => 'site/error',
        ],
        'mailer' => [
            'class' => 'yii\swiftmailer\Mailer',
            // send all mails to a file by default. You have to set
            // 'useFileTransport' to false and configure a transport
            // for the mailer to send real emails.
            'useFileTransport' => true,
        ],
        'log' => [
            'traceLevel' => YII_DEBUG ? 3 : 0,
            'targets' => [
                [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['error', 'warning'],
                ],
            ],
        ],
        'db' => $db,
        'urlManager' => [
            'enablePrettyUrl' => true,
            'showScriptName' => false,
            'rules' => [
            ],
        ],

        'i18n' => [
            'translations' => [
                '*' => [
                    'class' => 'yii\i18n\PhpMessageSource',
                    //'basePath' => '@app/messages',
                    'sourceLanguage' => 'en-US',
                    'fileMap' => [
                        'app' => 'app.php',
                        'admin' => 'admin.php',
                        'login' => 'login.php',
                        'app/error' => 'error.php',
                    ],
                ],
            ],
        ],
        'authManager' => [
            'class' => \app\components\rbac\DbManager::class,
            'defaultRoles' => ['USER'],
            //'cache' => 'cacheArray'
        ],
    ],
    // set target language to be Russian
    'language' => 'en-US',

    // set source language to be English
    'sourceLanguage' => 'en-US',

    'params' => $params,
];

if (YII_ENV_DEV) {
    // configuration adjustments for 'dev' environment
    $config['bootstrap'][] = 'debug';
    $config['modules']['debug'] = [
        'class' => 'yii\debug\Module',
        // uncomment the following to add your IP if you are not connecting from localhost.
        //'allowedIPs' => ['127.0.0.1', '::1'],
    ];

    $config['bootstrap'][] = 'gii';
    $config['modules']['gii'] = [
        'class' => 'yii\gii\Module',
        // uncomment the following to add your IP if you are not connecting from localhost.
        //'allowedIPs' => ['127.0.0.1', '::1'],
    ];
}

return $config;
