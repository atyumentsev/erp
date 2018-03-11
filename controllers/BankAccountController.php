<?php

namespace app\controllers;

use app\models\Affiliate;
use app\models\Bank;
use app\models\BankAccountBalance;
use app\models\Currency;
use app\models\forms\BankAccountBalanceForm;
use Yii;
use app\models\BankAccount;
use yii\data\ActiveDataProvider;
use app\components\Controller;
use yii\filters\AccessControl;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * BankAccountController implements the CRUD actions for BankAccount model.
 */
class BankAccountController extends Controller
{
    public $layout = 'finance';
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
            'access' => [
                'class' => AccessControl::class,
                'rules' => [
                    [
                        'allow' => true,
                        'actions' => ['index', 'view'],
                        'roles' => ['bank.view'],
                    ],
                    [
                        'allow' => true,
                        'actions' => ['create'],
                        'roles' => ['bank.create'],
                    ],
                    [
                        'allow' => true,
                        'actions' => ['update'],
                        'roles' => ['bank.update'],
                    ],
                    [
                        'allow' => true,
                        'actions' => ['delete'],
                        'roles' => ['bank.delete'],
                    ],
                    [
                        'allow' => true,
                        'actions' => ['balance'],
                        'roles' => ['bank.balance.view'],
                    ],
                    [
                        'allow' => true,
                        'actions' => ['set-balance'],
                        'roles' => ['bank.balance.edit'],
                    ],
                ],
            ],
        ];
    }

    /**
     * Lists all BankAccount models.
     * @return mixed
     */
    public function actionIndex()
    {
        $dataProvider = new ActiveDataProvider([
            'query' => BankAccount::find()
                    ->with('bank')
                    ->with('currency')
                    ->with('affiliate')
        ]);

        return $this->render('index', [
            'dataProvider' => $dataProvider,
            'i_can_view' => \Yii::$app->user->can('bank.view'),
            'i_can_create' => \Yii::$app->user->can('bank.create'),
            'i_can_update' => \Yii::$app->user->can('bank.update'),
            'i_can_delete' => \Yii::$app->user->can('bank.delete'),
        ]);
    }

    /**
     * Displays a single BankAccount model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new BankAccount model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new BankAccount();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('create', [
            'model' => $model,
            'banks' => Bank::getDropdownList(),
            'affiliates' => Affiliate::getDropdownList(),
            'currencies' => Currency::getDropdownList(),
        ]);
    }

    /**
     * Updates an existing BankAccount model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('update', [
            'model' => $model,
            'banks' => Bank::getDropdownList(),
            'affiliates' => Affiliate::getDropdownList(),
            'currencies' => Currency::getDropdownList(),
        ]);
    }

    /**
     * Deletes an existing BankAccount model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    public function actionBalance($date = null)
    {
        if (empty($date) || strtotime($date) == 0) {
            $date = date('d.m.Y');
            $date_db = date('Y-m-d');
        } else {
            $date_db = date('Y-m-d', strtotime($date));
        }

        /** @var Bank[] $banks */
        $banks = Bank::find()->orderBy('id')->all();
        /** @var BankAccount[] $accounts */
        $accounts = BankAccount::find()
            ->with('currency')
            ->all();

        $balances = BankAccountBalance::find()
            ->with('bankAccount.currency')
            ->where(['date' => $date_db])
            ->indexBy('bank_account_id')
            ->all();

        $accounts_by_bank = [];
        foreach ($accounts as $account) {
            $accounts_by_bank[$account->bank_id][] = $account;
        }
        return $this->render('balance', [
            'i_can_edit' => \Yii::$app->user->can('bank.balance.edit'),
            'i_can_pay' => \Yii::$app->user->can('pr.select'),
            'date' => $date,
            'banks' => $banks,
            'accounts_by_bank' => $accounts_by_bank,
            'balances' => $balances,
        ]);
    }

    /**
     * @param string $date
     * @param int $bank_account_id
     * @return string|\yii\web\Response
     */
    public function actionSetBalance(string $date, int $bank_account_id)
    {
        if (empty($date) || strtotime($date) == 0) {
            $date = date('d.m.Y');
            $date_db = date('Y-m-d');
        } else {
            $date_db = date('Y-m-d', strtotime($date));
        }
        $bab = BankAccountBalanceForm::findOne(['date' => $date_db, 'bank_account_id' => $bank_account_id]);
        if (!$bab) {
            $bab = new BankAccountBalanceForm(['date' => $date_db, 'bank_account_id' => $bank_account_id]);
        }

        if (\Yii::$app->request->isPost && $bab->load(\Yii::$app->request->post())) {
//            $balance = \Yii::$app->request->post('balanceReadable', 0);
//            $bab->balance = $balance;
            if (!$bab->save()) {
                $errors[] = $bab->getErrors();
                l($errors);
            } else {
                return $this->redirect(['balance', 'date' => $date]);
            }
        }
        return $this->render('set_balance', [
            'date' => $date,
            'model' => $bab,
        ]);
    }

    /**
     * Finds the BankAccount model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return BankAccount the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = BankAccount::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
    }
}
