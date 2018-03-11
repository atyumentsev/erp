<?php

namespace app\controllers;

use yii\filters\AccessControl;
use app\components\Controller;

class FinanceController extends Controller
{
    public $layout = 'finance.php';
    /**
     * @inheritdoc
     */
    public function behaviors() : array
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
        ];
    }

    /**
     * Displays homepage.
     *
     * @return string
     */
    public function actionIndex() : string
    {
        return $this->render('index');
    }
}
