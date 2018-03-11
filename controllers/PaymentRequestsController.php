<?php

namespace app\controllers;

use app\components\helpers\CurrencyHelper;
use app\models\Affiliate;
use app\models\BankAccount;
use app\models\Budget;
use app\models\BudgetCFR;
use app\models\BudgetPeriod;
use app\models\CashflowItem;
use app\models\Currency;
use app\models\Department;
use app\models\forms\PaymentRequestForm;
use app\models\PaymentRequest;
use app\models\Product;
use app\models\search\PaymentRequestSearch;
use app\models\service\ViewableFields;
use app\models\Urgency;
use app\models\User;
use yii\data\ActiveDataProvider;
use yii\filters\AccessControl;
use app\components\Controller;
use yii\web\NotFoundHttpException;

class PaymentRequestsController extends Controller
{
    public $layout = 'finance.php';
    /**
     * @inheritdoc
     */
    public function behaviors() : array
    {
        //return [];
        return [
            'access' => [
                'class' => AccessControl::class,
                'rules' => [
                    [
                        'allow' => true,
                        'actions' => ['index', 'view'],
                        'roles' => ['pr.view'],
                    ],
                    [
                        'allow' => true,
                        'actions' => ['create', 'copy'],
                        'roles' => ['pr.create'],
                    ],
                    [
                        'allow' => true,
                        'actions' => ['update'],
                        'roles' => ['pr.update'],
                        'roleParams' => function() {
                            return ['pr' => PaymentRequest::findOne(\Yii::$app->request->get('id'))];
                        },
                    ],
                    [
                        'allow' => true,
                        'actions' => ['cancel'],
                        'roles' => ['pr.cancel'],
                        'roleParams' => function() {
                            return ['pr' => PaymentRequest::findOne(\Yii::$app->request->get('id'))];
                        },
                    ],
                    [
                        'allow' => true,
                        'actions' => ['approve'],
                        'roles' => ['pr.approve'],
                        'roleParams' => function() {
                            return ['pr' => PaymentRequest::findOne(\Yii::$app->request->get('id'))];
                        },
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
        $searchModel = new PaymentRequestSearch();
        $searchModel->load(\Yii::$app->request->get());
        $query = $searchModel->search();
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);
        $fields = ViewableFields::getUserFields(\Yii::$app->user->id);
        return $this->render('index', [
            'departments' => Department::getDropdownList(),
            'cashflow_items' => CashflowItem::getDropdownList(),
            'payers' => Affiliate::getPayerDropdownList(),
            'users' => User::getDropdownList(),
            'searchModel'  => $searchModel,
            'dataProvider' => $dataProvider,
            'fields' => $fields,
            'i_can_create' => \Yii::$app->user->can('pr.create'),
        ]);
    }

    public function actionCreate()
    {
        $model = new PaymentRequestForm();
        if (\Yii::$app->request->isPost
            && $model->load(\Yii::$app->request->post())
            && $model->validate()
            && $model->save()) {
            return $this->redirect('index');
        }

        $currencies = Currency::getDropdownList();
        $products = Product::getDropdownList();
        $cashflow_items = CashflowItem::getDropdownList();
        $departments = Department::getDropdownList();
        $users = User::getDropdownList();
        $urgency = Urgency::getDropdownList();
        $affiliates = Affiliate::getDropdownList();
        $payers = Affiliate::getPayerDropdownList();

        return $this->render('create', [
            'currencies'        => $currencies,
            'products'          => $products,
            'cashflow_items'    => $cashflow_items,
            'departments'       => $departments,
            'users'             => $users,
            'payers'            => $payers,
            'urgency'           => $urgency,
            'affiliates'        => $affiliates,
            'bank_accounts_hash' => $this->getBankAccountsHash(),
            'model'             => $model,
        ]);
    }

    public function actionView($id)
    {
        $invoice = PaymentRequest::findOne(['id' => $id]);
        if (!$invoice) {
            throw new NotFoundHttpException('PaymentRequest # ' . $id . ' is not found');
        }

        $invoice->originalCurrency = Currency::findOne($invoice->original_currency_id);

        $period = BudgetPeriod::getBudgetPeriod($invoice->due_date);

        $budget_data = [];

        if (isset($period) > 0) {
            $budget_data['period'] = $period;
            $budget_cfr_list = BudgetCFR::find()
                ->where(['department_id' => $invoice->customer_department_id])
                ->indexBy('id')
                ->all();

            $budget_data['cfr_list'] = $budget_cfr_list;

            $budget_for_period = Budget::find()
                ->where([
                    'budget_period_id' => $period->id,
                    'cashflow_item_id' => $invoice->cashflow_item_id,
                    'budget_cfr_id' => array_keys($budget_cfr_list),
                ])
                ->sum('amount');

            $budget_data['budget'] = abs($budget_for_period);

            $performance_list = PaymentRequest::find()
                ->select('original_currency_id, conversion_percent, required_payment, required_payment_rub')
                ->where([
                    'customer_department_id' => $invoice->customer_department_id,
                    'cashflow_item_id' => $invoice->cashflow_item_id,
                ])
                ->andWhere(['between', 'due_date', $period->date_start, $period->date_finish])
                ->asArray()
                ->all();

            $performance = 0;

            foreach ($performance_list as $row) {
                if (isset($row['required_payment_rub'])) {
                    $performance += $row['required_payment_rub'];
                } else {
                    $performance += CurrencyHelper::convertToRubUnits(
                        $row['original_currency_id'],
                        $row['conversion_percent'],
                        $row['required_payment']
                    );
                }
            }

            $budget_data['performance'] = $performance;
            $budget_data['rest'] = $budget_data['budget'] - $budget_data['performance'];
        }

        return $this->render('view', [
            'invoice' => $invoice,
            'budget_data' => $budget_data,
            'rubCurrency' => Currency::findOne(['code' => 'RUB']),
            'originalCurrency' => Currency::findOne(['id' => $invoice->original_currency_id]),
            'i_can_update' => \Yii::$app->user->can('pr.update'),
            'i_can_cancel' => \Yii::$app->user->can('pr.cancel'),
            'i_can_approve' => \Yii::$app->user->can('pr.approve'),
            'i_can_create' => \Yii::$app->user->can('pr.create'),
        ]);
    }

    public function actionUpdate($id)
    {
        $model = PaymentRequestForm::findOne($id);
        if (!$model) {
            throw new NotFoundHttpException('PaymentRequest # ' . $id . ' is not found');
        }
        if (\Yii::$app->request->isPost
            && $model->load(\Yii::$app->request->post())
            && $model->validate()
            && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        $currencies = Currency::getDropdownList();
        $products = Product::getDropdownList();
        $cashflow_items = CashflowItem::getDropdownList();
        $departments = Department::getDropdownList();
        $users = User::getDropdownList();
        $urgency = Urgency::getDropdownList();
        $affiliates = Affiliate::getDropdownList();
        $payers = Affiliate::getPayerDropdownList();

        return $this->render('update', [
            'currencies'        => $currencies,
            'products'          => $products,
            'cashflow_items'    => $cashflow_items,
            'departments'       => $departments,
            'users'             => $users,
            'payers'            => $payers,
            'urgency'           => $urgency,
            'affiliates'        => $affiliates,
            'bank_accounts_hash' => $this->getBankAccountsHash(),
            'model'             => $model,
        ]);
    }

    public function actionCopy($id)
    {
        $model = PaymentRequestForm::findOne($id);
        if (!$model) {
            throw new NotFoundHttpException('PaymentRequest # ' . $id . ' is not found');
        }

        $model->isNewRecord = true;
        $model->id = null;

        if (\Yii::$app->request->isPost
            && $model->load(\Yii::$app->request->post())
            && $model->validate()
            && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        $currencies = Currency::getDropdownList();
        $products = Product::getDropdownList();
        $cashflow_items = CashflowItem::getDropdownList();
        $departments = Department::getDropdownList();
        $users = User::getDropdownList();
        $urgency = Urgency::getDropdownList();
        $affiliates = Affiliate::getDropdownList();
        $payers = Affiliate::getPayerDropdownList();

        return $this->render('create', [
            'currencies'        => $currencies,
            'products'          => $products,
            'cashflow_items'    => $cashflow_items,
            'departments'       => $departments,
            'users'             => $users,
            'payers'            => $payers,
            'urgency'           => $urgency,
            'affiliates'        => $affiliates,
            'bank_accounts_hash' => $this->getBankAccountsHash(),
            'model'             => $model,
        ]);
    }

    public function actionCancel($id)
    {
        /** @var PaymentRequest $model */
        $model = PaymentRequest::findOne($id);
        if (!$model) {
            throw new NotFoundHttpException('PaymentRequest # ' . $id . ' is not found');
        }

        $model->status = PaymentRequest::STATUS_CANCELLED;
        $model->save();
        \Yii::info('Payment Request #' . $id . 'was cancelled', 'payment_request');

        return $this->redirect(['view', 'id' => $model->id]);
    }

    public function actionApprove($id)
    {
        /** @var PaymentRequest $model */
        $model = PaymentRequest::findOne($id);
        if (!$model) {
            throw new NotFoundHttpException('PaymentRequest # ' . $id . ' is not found');
        }

        $model->status = PaymentRequest::STATUS_APPROVED;
        $model->save();
        \Yii::info('Payment Request #' . $id . 'was approved', 'payment_request');

        return $this->redirect(['view', 'id' => $model->id]);
    }

    private function getBankAccountsHash() : array
    {
        /** @var BankAccount[] $bank_accounts */
        $bank_accounts = BankAccount::find()->with('bank')->all();
        $bank_accounts_hash = [];

        foreach ($bank_accounts as $account) {
            $bank_accounts_hash[$account->affiliate_id][$account->id] = $account->name;
        }
        return $bank_accounts_hash;
    }
}
