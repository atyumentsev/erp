<?php

namespace app\components;

use app\models\User;

class Controller extends \yii\web\Controller
{
    public function beforeAction($action)
    {
        if (!\Yii::$app->user->isGuest) {
            /** @var User $user */
            $user = \Yii::$app->user->identity;
            \Yii::$app->language = $user->locale;
        }
//        $this->view->params['breadcrumbs'] = [];

        $this->view->registerCss('body { font-family: Arial, sans-serif !important; }');

        return parent::beforeAction($action);
    }
}