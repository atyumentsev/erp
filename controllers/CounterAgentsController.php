<?php

namespace app\controllers;

use app\components\Controller;
use app\models\CounterAgent;
use app\models\search\CounterAgentSearch;
use yii\filters\AccessControl;

class CounterAgentsController extends Controller
{
    public $layout = 'finance.php';

    public function beforeAction($action)
    {
        $this->view->params['breadcrumbs'][] = [
            'label' => \Yii::t('app', 'CounterAgents'),
            'url' => ['/counter-agents'],
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
        $searchModel = new CounterAgentSearch();
        $dataProvider = $searchModel->search(\Yii::$app->request->get());

        return $this->render('index', [
            'dataProvider' => $dataProvider,
            'searchModel' => $searchModel,
        ]);
    }

    public function actionFind(string $q)
    {
        $searchModel = new CounterAgentSearch();
        $searchModel->name = $q;

        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $out = ['results' => ['id' => '', 'text' => '']];
        if (!is_null($q)) {
            $query = CounterAgent::find()
                ->select('id, name as text')
                ->where(['ilike', 'name', $q])
                ->limit(20);
            $command = $query->createCommand();
            $data = $command->queryAll();
            $out['results'] = array_values($data);
        }
        return $out;
    }
}
