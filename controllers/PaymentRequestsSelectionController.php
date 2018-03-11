<?php

namespace app\controllers;

use app\components\Controller;
use app\models\Affiliate;
use app\models\BankAccount;
use app\models\CashflowItem;
use app\models\Currency;
use app\models\Department;
use app\models\PaymentRequest;
use app\models\search\PaymentRequestSelectionSearch;
use app\models\service\RankingService;
use app\models\service\ViewableFields;
use app\models\User;
use yii\data\ArrayDataProvider;
use yii\filters\AccessControl;

class PaymentRequestsSelectionController extends Controller
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
        $searchModel = new PaymentRequestSelectionSearch();
        $searchModel->load(\Yii::$app->request->get());
        $query = $searchModel->search();
        $payment_requests = $query->all();

        $rankingService = new RankingService();
        $rankingService->init($payment_requests);

        $payment_requests = $rankingService->getRanked($searchModel->ranking);

        $dataProvider = new ArrayDataProvider([
            'allModels' => $payment_requests,
            'pagination' => [
                'pageSize' => 100,
            ],
            'sort' => [
                //'attributes' => ['id', 'name'],
            ],
        ]);

        $bank_accounts_hash = ['' => null];
        /** @var BankAccount[] $bank_accounts */
        $bank_accounts = BankAccount::find()->orderBy('bank_id')->all();
        $affiliates = Affiliate::getDropdownList();
        $currency = null;

        if ($searchModel->bank_account_id) {
            if ($searchModel->bankAccount) {
                $currency = $searchModel->bankAccount->currency;
            }
        }
        if (empty($currency)) {
            $currency = Currency::findOne(PaymentRequest::CURRENCY_CODE_RUB);
        }

        foreach ($bank_accounts as $account) {
            $bank_accounts_hash[$affiliates[$account->affiliate_id]][$account->id] = $account->name;
        }

        return $this->render('index', [
            'available_rankings' => ['' => null] + RankingService::getAvailableRankings(),
            'users' => User::getDropdownList(),
            'departments' => Department::getDropdownList(),
            'cashflow_items' => CashflowItem::getInvoiceDropdownList(),
            'payers' => Affiliate::getPayerDropdownList(),
            'searchModel'  => $searchModel,
            'dataProvider' => $dataProvider,
            'bank_accounts_hash' => $bank_accounts_hash,
            'i_can_view_ranking' => \Yii::$app->user->can('ranking.view'),
            'i_can_select' => \Yii::$app->user->can('pr.select'),
            'fields' => ViewableFields::getUserFields(\Yii::$app->user->id),
            'currency' => $currency,
        ]);
    }

    public function actionSelected() : string
    {
        $searchModel = new PaymentRequestSelectionSearch();
        $searchModel->load(\Yii::$app->request->get());
        $query = $searchModel->search();
        $payment_requests = $query->all();

        $rankingService = new RankingService();
        $rankingService->init($payment_requests);

        $payment_requests = $rankingService->getRanked($searchModel->ranking);

        $dataProvider = new ArrayDataProvider([
            'allModels' => $payment_requests,
            'pagination' => [
                'pageSize' => 100,
            ],
            'sort' => [
                //'attributes' => ['id', 'name'],
            ],
        ]);

        return $this->render('selected', [
            'searchModel'  => $searchModel,
            'dataProvider' => $dataProvider,
            'i_can_view_ranking' => \Yii::$app->user->can('ranking.view'),
            'i_can_select' => \Yii::$app->user->can('pr.select'),
            'i_can_send_to_accountant' => \Yii::$app->user->can('pr.send_to_accountant'),
            'fields' => ViewableFields::getUserFields(\Yii::$app->user->id),
        ]);
    }
}
