<?php

namespace app\controllers;

use app\models\CashflowItem;
use app\models\Department;
use app\models\DueDate;
use app\models\Urgency;
use app\models\User;
use Yii;
use app\models\Ranking;
use yii\data\ActiveDataProvider;
use app\components\Controller;
use yii\data\ArrayDataProvider;
use yii\filters\AccessControl;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * RankingController implements the CRUD actions for Ranking model.
 */
class RankingController extends Controller
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
                        'actions' => ['index', 'view', 'counteragents', 'urgency', 'due-date', 'users', 'cashflow-items', 'departments'],
                        'roles' => ['ranking.view'],
                    ],
                    [
                        'allow' => true,
                        'actions' => ['create'],
                        'roles' => ['ranking.create'],
                    ],
                    [
                        'allow' => true,
                        'actions' => ['update'],
                        'roles' => ['ranking.update'],
                    ],
                    [
                        'allow' => true,
                        'actions' => ['delete'],
                        'roles' => ['ranking.delete'],
                    ],
                ],
            ],
        ];
    }

    /**
     * Lists all Ranking models.
     * @return mixed
     */
    public function actionIndex()
    {
        $dataProvider = new ActiveDataProvider([
            'query' => Ranking::find(),
        ]);

        return $this->render('index', [
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionCounteragents()
    {
        $data = Ranking::find()
            ->select('ranking.id, ranking.priority, counteragent.name')
            ->where(['entity_type' => Ranking::ENTITY_TYPE_COUNTERAGENT])
            ->join('INNER JOIN', 'counteragent', 'ranking.entity_id = counteragent.id')
            ->asArray()
            ->indexBy('id')
            ->orderBy('priority DESC')
            ->all();

        $dataProvider = new ArrayDataProvider([
            'allModels' => $data,
            'pagination' => [
                'pageSize' => 100,
            ],
            'sort' => [
                //'attributes' => ['id', 'name'],
            ],
        ]);

        return $this->render('counteragents', [
            'dataProvider' => $dataProvider,
            'i_can_update' => \Yii::$app->user->can('ranking.update'),
            'i_can_create' => \Yii::$app->user->can('ranking.create'),
            'i_can_delete' => \Yii::$app->user->can('ranking.delete'),
        ]);
    }

    public function actionUsers()
    {
        $data = Ranking::find()
            ->select('ranking.id, ranking.priority, u.name')
            ->join("INNER JOIN", "user u", "ranking.entity_id = u.id AND entity_type = '" . Ranking::ENTITY_TYPE_USER . "'")
            ->asArray()
            ->orderBy('priority DESC')
            ->all();

        $dataProvider = new ArrayDataProvider([
            'allModels' => $data,
            'pagination' => [
                'pageSize' => 100,
            ],
            'sort' => [
                //'attributes' => ['id', 'name'],
            ],
        ]);

        return $this->render('users', [
            'dataProvider' => $dataProvider,
            'i_can_update' => \Yii::$app->user->can('ranking.update'),
            'i_can_create' => \Yii::$app->user->can('ranking.create'),
            'i_can_delete' => \Yii::$app->user->can('ranking.delete'),
        ]);
    }

    public function actionCashflowItems()
    {
        $data = CashflowItem::find()
            ->alias('cfi')
            ->select('cfi.id as entity_id, ranking.id as id, ranking.priority, cfi.name')
            ->join(
                'LEFT JOIN',
                'ranking',
                "ranking.entity_id = cfi.id AND entity_type = '". Ranking::ENTITY_TYPE_CASHFLOW_ITEM. "'"
            )
            ->asArray()
            ->all();

        $data = $this->sortRankings($data);

        $dataProvider = new ArrayDataProvider([
            'allModels' => $data,
            'pagination' => [
                'pageSize' => 100,
            ],
            'sort' => [
                //'attributes' => ['id', 'name'],
            ],
        ]);

        return $this->render('cashflow_items', [
            'dataProvider' => $dataProvider,
            'i_can_update' => \Yii::$app->user->can('ranking.update'),
            'i_can_create' => \Yii::$app->user->can('ranking.create'),
            'i_can_delete' => \Yii::$app->user->can('ranking.delete'),
        ]);
    }

    public function actionDepartments()
    {
        $data = Department::find()
            ->alias('d')
            ->select('d.id as entity_id, ranking.id as id, ranking.priority, d.name')
            ->join(
                'LEFT JOIN',
                'ranking',
                "ranking.entity_id = d.id AND entity_type = '". Ranking::ENTITY_TYPE_CUST_DEPARTMENT. "'"
            )
            ->asArray()
            ->all();

        $data = $this->sortRankings($data);

        $dataProvider = new ArrayDataProvider([
            'allModels' => $data,
            'pagination' => [
                'pageSize' => 100,
            ],
            'sort' => [
                //'attributes' => ['id', 'name'],
            ],
        ]);

        return $this->render('departments', [
            'dataProvider' => $dataProvider,
            'i_can_update' => \Yii::$app->user->can('ranking.update'),
            'i_can_create' => \Yii::$app->user->can('ranking.create'),
            'i_can_delete' => \Yii::$app->user->can('ranking.delete'),
        ]);
    }

    public function actionUrgency()
    {
        $urgency_list = Urgency::getList();
        $data = [];
        /** @var Ranking[] $rankings */
        $rankings = Ranking::find()
            ->where(['entity_type' => Ranking::ENTITY_TYPE_URGENCY])
            ->indexBy('entity_id')
            ->all();

        foreach ($urgency_list as $id => $name) {
            $data[] = [
                'entity_id' => $id,
                'name' => $name,
                'id' => isset($rankings[$id]) ? $rankings[$id]->id : null,
                'priority' => isset($rankings[$id]) ? $rankings[$id]->priority : null,
            ];
        }

        $data = $this->sortRankings($data);

        $dataProvider = new ArrayDataProvider([
            'allModels' => $data,
            'pagination' => [
                'pageSize' => 100,
            ],
            'sort' => [
                //'attributes' => ['id', 'name'],
            ],
        ]);

        return $this->render('urgency', [
            'dataProvider' => $dataProvider,
            'i_can_update' => \Yii::$app->user->can('ranking.update'),
            'i_can_create' => \Yii::$app->user->can('ranking.create'),
            'i_can_delete' => \Yii::$app->user->can('ranking.delete'),
        ]);
    }

    public function actionDueDate()
    {
        $due_date_list = DueDate::getList();
        $data = [];
        /** @var Ranking[] $rankings */
        $rankings = Ranking::find()
            ->where(['entity_type' => Ranking::ENTITY_TYPE_DUE_DATE])
            ->indexBy('entity_id')
            ->all();

        foreach ($due_date_list as $id => $name) {
            $data[] = [
                'entity_id' => $id,
                'name' => $name,
                'id' => isset($rankings[$id]) ? $rankings[$id]->id : null,
                'priority' => isset($rankings[$id]) ? $rankings[$id]->priority : null,
            ];
        }

        $data = $this->sortRankings($data, 'id');

        $dataProvider = new ArrayDataProvider([
            'allModels' => $data,
            'pagination' => [
                'pageSize' => 100,
            ],
            'sort' => [
                //'attributes' => ['id', 'name'],
            ],
        ]);

        return $this->render('due_date', [
            'dataProvider' => $dataProvider,
            'i_can_update' => \Yii::$app->user->can('ranking.update'),
            'i_can_create' => \Yii::$app->user->can('ranking.create'),
            'i_can_delete' => \Yii::$app->user->can('ranking.delete'),
        ]);
    }

    /**
     * Displays a single Ranking model.
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
     * Creates a new Ranking model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @param string $entity_type
     * @return mixed
     */
    public function actionCreate($entity_type = null, $entity_id = null)
    {
        $model = new Ranking();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirectToList($entity_type);
        }

        if (!is_null($entity_id)) {
            $model->entity_id = $entity_id;
        }
        if (!is_null($entity_id)) {
            $model->entity_type = $entity_type;
        }

        $list = $this->getEntitiesList($model, $entity_type);

        return $this->render('create', [
            'model' => $model,
            'entity_id' => $entity_id,
            'entity_type' => $entity_type,
            'entity_types' => ['' => null] + Ranking::getEntityTypes(),
            'list' => $list,
        ]);
    }

    /**
     * Updates an existing Ranking model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @param string $entity_type
     *
     * @return mixed
     */
    public function actionUpdate($id, $entity_type = null, $entity_id = null)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirectToList($entity_type);
        }

        $list = $this->getEntitiesList($model, $entity_type);

        return $this->render('update', [
            'model' => $model,
            'entity_id' => $entity_id,
            'entity_type' => $entity_type,
            'entity_types' => ['' => null] + Ranking::getEntityTypes(),
            'list' => $list,
        ]);
    }

    /**
     * Deletes an existing Ranking model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $model = $this->findModel($id);
        $model->delete();

        return $this->redirectToList($model->entity_type);
    }

    /**
     * Finds the Ranking model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Ranking the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Ranking::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
    }

    private function getEntitiesList(Ranking $model, string $entity_type = null)
    {
        if (!isset(Ranking::CLASS_MAP[$entity_type])) {
            $entity_type = null;
        }

        if ($entity_type === null) {
            $entity_type = $model->entity_type;
        }

        if ($entity_type !== null) {
            $class = Ranking::CLASS_MAP[$entity_type];
            /** @var User|CashflowItem|Department $class */
            $list = $class::getDropdownList();

            if ($model->entity_type != $entity_type) {
                $model->entity_type = $entity_type;
                $model->entity_id = null;
            }
        } else {
            $list = [];
        }

        return $list;
    }

    private function redirectToList($entity_type)
    {
        $pages = [
            Ranking::ENTITY_TYPE_CASHFLOW_ITEM  => 'cashflow-items',
            Ranking::ENTITY_TYPE_URGENCY        => 'urgency',
            Ranking::ENTITY_TYPE_USER           => 'users',
            Ranking::ENTITY_TYPE_DUE_DATE       => 'due-date',
            Ranking::ENTITY_TYPE_CUST_DEPARTMENT => 'departments',
            Ranking::ENTITY_TYPE_COUNTERAGENT   => 'counteragents',
        ];
        if (isset($pages[$entity_type])) {
            return $this->redirect('/ranking/' . $pages[$entity_type]);
        } else {
            return $this->redirect('/ranking/index');
        }
    }

    private function sortRankings($data, $sort_field = 'name')
    {
        usort($data, function ($a, $b) use ($sort_field) {
            if ($b['priority'] == $a['priority']) {
                return $a[$sort_field] <=> $b[$sort_field];
            }
            return (int)$b['priority'] <=> (int)$a['priority'];
        });
        return $data;
    }
}
