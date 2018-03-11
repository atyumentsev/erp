<?php

namespace app\controllers;

use app\models\BudgetCFR;
use app\models\BudgetPeriod;
use app\models\CashflowItem;
use app\models\Currency;
use app\models\search\BudgetSearch;
use Yii;
use app\models\Budget;
use \app\components\Controller;
use yii\filters\AccessControl;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * BudgetController implements the CRUD actions for Budget model.
 */
class BudgetController extends Controller
{
    public $layout = 'finance.php';
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
                        'roles' => ['@'],
                    ],
                ],
            ],
        ];
    }

    /**
     * Lists all Budget models.
     * @return mixed
     */
    public function actionIndex($budget_cfr_id = null)
    {
        $cfr_list = BudgetCFR::getDropdownList();

        if (empty ($cfr_list[$budget_cfr_id])) {
            $budget_cfr_id = array_keys($cfr_list)[0];
        }

        l($budget_cfr_id);

        $budget_periods = BudgetPeriod::find()
            ->where(['BETWEEN', 'date_start', '2017-01-01', '2017-12-31'])
            ->indexBy('id')
            ->orderBy('date_start')
            ->all();

        $currency = Currency::findOne(['code' => 'RUB']);

        $cashflow_items = CashflowItem::find()
            ->indexBy('id')
            ->orderBy('id')
            ->all();

        $model = new BudgetSearch();
        $model->load(\Yii::$app->request->get());

        $budget_items = $model->find()
            ->select('budget_period_id, cashflow_item_id, amount')
            ->where(['budget_cfr_id' => $budget_cfr_id])
            ->andWhere(['IN', 'budget_period_id', array_keys($budget_periods)])
            ->asArray()
            ->all();

        $budget_items_hash = [];

        foreach ($budget_items as $item) {
            $budget_items_hash[$item['budget_period_id']][$item['cashflow_item_id']] = $item['amount'];
        }

        return $this->render('index', [
            'cfr_id' => $budget_cfr_id,
            'cfr_list' => $cfr_list,
            'model' => $model,
            'budget_periods' => $budget_periods,
            'currency' => $currency,
            'cashflow_items' => $cashflow_items,
            'budget_items_hash' => $budget_items_hash,
        ]);
    }

    /**
     * Displays a single Budget model.
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
     * Creates a new Budget model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Budget();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing Budget model.
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
        ]);
    }

    /**
     * Deletes an existing Budget model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the Budget model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Budget the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Budget::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
    }
}
