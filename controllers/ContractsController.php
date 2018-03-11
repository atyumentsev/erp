<?php

namespace app\controllers;

use app\components\Controller;
use app\models\Affiliate;
use app\models\Contract;
use app\models\search\ContractSearch;
use yii\filters\AccessControl;

class ContractsController extends Controller
{
    public $layout = 'finance.php';

    /**
     * @param \yii\base\Action $action
     * @return bool
     */
    public function beforeAction($action) : bool
    {
        $this->view->params['breadcrumbs'][] = [
            'label' => \Yii::t('app', 'Contracts'),
            'url' => ['/contracts'],
        ];
        return parent::beforeAction($action);
    }

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
     * @return string
     */
    public function actionIndex() : string
    {
        $searchModel = new ContractSearch();
        $dataProvider = $searchModel->search(\Yii::$app->request->get());
        $affiliates = Affiliate::getDropdownList();
        $affiliates = [null => ''] + $affiliates;

        return $this->render('index', [
            'affiliates' => $affiliates,
            'dataProvider' => $dataProvider,
            'searchModel' => $searchModel,
        ]);
    }

    /**
     * @param string|null $counteragent_id
     * @param string|null $q
     * @return array
     */
    public function actionFind(string $counteragent_id = null, string $q = null) : array
    {
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $out = ['results' => ['id' => '', 'text' => '']];
        if (!empty($q) || !empty($counteragent_id)) {
            $query = Contract::find()
                ->select('id, name as text')
                ->andFilterWhere(['counteragent_id' => $counteragent_id])
                ->andFilterWhere(['ilike', 'name', $q]);
            $command = $query->createCommand();
            $data = $command->queryAll();
            $out['results'] = array_values($data);
        }
        return $out;
    }
}
