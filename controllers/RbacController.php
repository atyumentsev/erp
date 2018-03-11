<?php
namespace app\controllers;

use app\components\Controller;
use app\models\rbac\GraphBuilder;
use app\models\rbac\PermissionChecker;
use app\models\rbac\PermissionCheckerForm;
use app\models\User;
use yii\filters\AccessControl;

class RbacController extends Controller
{
    public $layout = 'finance';
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'rules' => [
                    [
                        'allow'  => 'true',
                        'roles'  => ['ADMIN'],
                    ],
                ],
            ],
        ];
    }

    public function beforeAction($action)
    {
        $this->view->registerJsFile(
            '@web/js/vis.min.js',
            ['depends' => [\yii\web\JqueryAsset::className()]]
        );
        $this->view->registerCssFile(
            '@web/css/vis.min.css'
        );

        return parent::beforeAction($action);
    }

    /**
     * @return string
     */
    public function actionPermissionChecker()
    {
        $model = new PermissionCheckerForm();
        $nodes = [];
        $edges = [];
        $assignedRoles = [];
        $permissionCheckResults = [];
        $weights = [];

        if ($model->load(\Yii::$app->request->post()) && $model->validate()) {
            $params = [];
            if (isset($model->entityId)) {
                $params['entity_id'] = $model->entityId;
            }
            $permissionChecker = new PermissionChecker($model->userId, $params);

            $permissionChecker->createPermissionTree($model->permissionName);
            $nodes = $permissionChecker->nodes;
            $edges = $permissionChecker->edges;
            $assignedRoles = $permissionChecker->assignedRoles;
            $permissionCheckResults = $permissionChecker->permissionCheckResults;
            $weights = $permissionChecker->weights;
        }

        return $this->render('permission_checker', [
            'model' => $model,
            'nodes' => $nodes,
            'edges' => $edges,
            'weights' => $weights,
            'assignedRoles' => $assignedRoles,
            'permissionCheckResults' => $permissionCheckResults,
            'users' => User::getDropdownList(),
        ]);
    }

    /**
     * @return string
     */
    public function actionHierarchy()
    {
        $graphBuilder = new GraphBuilder();
        $graphBuilder->createRbacTree();

        return $this->render('roles_tree', [
            'weights'       => $graphBuilder->getCorrectedWeights(),
            'edges'         => $graphBuilder->getEdges(),
            'items'         => $graphBuilder->getItems(),
        ]);
    }
}
