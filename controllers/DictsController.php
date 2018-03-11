<?php

namespace app\controllers;

use app\components\Controller;
use app\models\CashflowItem;
use app\models\Department;
use app\models\Product;
use app\models\User;
use yii\filters\AccessControl;

class DictsController extends Controller
{
    public $layout = 'finance.php';

    public function beforeAction($action)
    {
        $this->view->params['breadcrumbs'][] = [
            'label' => \Yii::t('app', 'Dictionaries'),
            'url' => ['/dicts'],
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
    public function actionUsers() : string
    {
        $users = User::find()->orderBy('id ASC')->all();
        return $this->render('users', [
            'users' => $users,
        ]);
    }

    /**
     * @return string
     */
    public function actionCashflowItems() : string
    {
        $items = CashflowItem::find()->orderBy('id ASC')->all();
        return $this->render('cashflow_items', [
            'items' => $items,
        ]);
    }

    /**
     * @return string
     */
    public function actionProducts() : string
    {
        $products = Product::find()->orderBy('id ASC')->all();
        return $this->render('products', [
            'products' => $products,
        ]);
    }

    /**
     * @return string
     */
    public function actionDepartments() : string
    {
        $departments = Department::find()->orderBy('id ASC')->all();
        return $this->render('departments', [
            'departments' => $departments,
        ]);
    }
}
