<?php

namespace app\controllers;

use app\models\service\ViewableFields;
use app\components\Controller;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;

/**
 * BankController implements the CRUD actions for Bank model.
 */
class UserSettingsController extends Controller
{
    public $layout = 'finance';
    public $enableCsrfValidation = false;

    public function beforeAction($action)
    {
        $this->view->params['breadcrumbs'][] = [
            'label' => \Yii::t('app', 'User Settings'),
        ];
        return parent::beforeAction($action);
    }

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'payment-requests-save' => ['POST'],
                ],
            ],
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
     * Displays a single Bank model.
     * @return mixed
     */
    public function actionPaymentRequests()
    {
        $available_fields = ViewableFields::getAvailableFields();
        $active_fields = ViewableFields::getUserFields(\Yii::$app->user->id);
        return $this->render('payment_requests', [
            'available_fields' => $available_fields,
            'active_fields' => $active_fields,
        ]);
    }

    public function actionPaymentRequestsSave()
    {
        ViewableFields::saveUserFields(\Yii::$app->user->id, array_keys(\Yii::$app->request->post()));
        $this->redirect('payment-requests');
    }
}
