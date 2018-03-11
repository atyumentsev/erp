<?php

namespace app\controllers;

use app\models\LoginForm;
use app\models\User;
use Yii;
use yii\filters\AccessControl;
use yii\helpers\Url;
use yii\web\Controller;
use yii\web\Response;

class SiteController extends Controller
{
    public $layout = 'main.php';
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['logout', 'switch-locale'],
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
     * @inheritdoc
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => \yii\web\ErrorAction::class,
            ],
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
        ];
    }

    /**
     * Displays homepage.
     *
     * @return string
     */
    public function actionIndex()
    {
        if (!Yii::$app->user->isGuest) {
            if (YII_ENV_TEST) {
                return $this->render('login_success');
            } else {
                return $this->redirect(Url::to('/payment-requests/index'));
            }
        } else {
            if (YII_ENV_TEST) {
                return 'failed to login';
            }
            return $this->redirect(Url::to('/site/login'));
        }
    }

    /**
     * Login action.
     *
     * @return Response|string
     */
    public function actionLogin()
    {
        if (!Yii::$app->user->isGuest) {
            return $this->redirect(Url::to('/payment-requests/index'));
        }

        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            return $this->redirect(Url::to('/payment-requests/index'));
        }
        return $this->render('login', [
            'model' => $model,
        ]);
    }

    /**
     * Logout action.
     *
     * @return Response
     */
    public function actionLogout() : Response
    {
        Yii::$app->user->logout();

        return $this->redirect(Url::to('/site/login'));
    }

    /**
     * Switch Locale
     *
     * @param string $locale
     */
    public function actionSwitchLocale(string $locale = 'en-US', $ret_url = null)
    {
        $languageFile = \Yii::getAlias('@app/config/language.php');
        $languageConfig = require $languageFile;
        if (!in_array($locale, $languageConfig['languages'])) {
            $locale = $languageConfig['languages'][0];
        }
        /** @var User $User */
        $User = \Yii::$app->user->identity;
        $User->locale = $locale;
        $User->save();
        if (!$ret_url) {
            $this->goBack();
        } else {
            $this->redirect($ret_url);
        }

    }
}
