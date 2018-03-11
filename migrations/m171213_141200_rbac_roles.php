<?php

use yii\db\Migration;
use app\components\rbac\PaymentRequestUpdateRule;
use app\components\rbac\PaymentRequestCancelRule;
use app\models\User;

/**
 * Class m171213_141200_rbac
 */
class m171213_141200_rbac_roles extends Migration
{
    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        $auth = \Yii::$app->authManager;

        // Roles
        $userRole = $auth->createRole('USER');
        $auth->add($userRole);

        $prRole = $auth->createRole('PR_RESPONSIBLE');
        $prRole->description = 'Responsible for Payment Request registry';
        $auth->add($prRole);

        $trRole = $auth->createRole('TREASURER');
        $auth->add($trRole);

        $cfoRole = $auth->createRole('CFO');
        $auth->add($cfoRole);

        $ceoRole = $auth->createRole('CEO');
        $auth->add($ceoRole);

        $adminRole = $auth->createRole('ADMIN');
        $auth->add($adminRole);

        // Payment Requests
        $permission = $auth->createPermission('pr.view');
        $permission->description = 'View Payment Request';
        $auth->add($permission);
        $auth->addChild($userRole, $permission);

        $permission = $auth->createPermission('pr.create');
        $permission->description = 'Create new Payment Request';
        $auth->add($permission);
        $auth->addChild($userRole, $permission);

        $prUpdatePermission = $auth->createPermission('pr.update');
        $prUpdatePermission->description = 'Update existing Payment Request';
        $auth->add($prUpdatePermission);
        $auth->addChild($prRole, $prUpdatePermission);

        $rule = new PaymentRequestUpdateRule();
        $auth->add($rule);
        $permission = $auth->createPermission('pr.update.my');
        $permission->description = 'Update belonging to current user Payment Request';
        $permission->ruleName = $rule->name;
        $auth->add($permission);
        $auth->addChild($userRole, $permission);
        $auth->addChild($permission, $prUpdatePermission);

        $prCancelPermission = $auth->createPermission('pr.cancel');
        $prCancelPermission->description = 'Cancel Payment Request';
        $auth->add($prCancelPermission);
        $auth->addChild($prRole, $prCancelPermission);

        $rule = new PaymentRequestCancelRule();
        $auth->add($rule);
        $permission = $auth->createPermission('pr.cancel.my');
        $permission->description = 'Cancel belonging to current user Payment Request';
        $permission->ruleName = $rule->name;
        $auth->add($permission);
        $auth->addChild($userRole, $permission);
        $auth->addChild($permission, $prCancelPermission);

        $prApprovePermission = $auth->createPermission('pr.approve');
        $prApprovePermission->description = 'Approve Payment Request';
        $auth->add($prApprovePermission);
        $auth->addChild($prRole, $prApprovePermission);

        // Ranking
        $permission = $auth->createPermission('ranking.view');
        $permission->description = 'View Ranking';
        $auth->add($permission);
        $auth->addChild($trRole, $permission);

        $permission = $auth->createPermission('ranking.create');
        $permission->description = 'Create new Ranking';
        $auth->add($permission);
        $auth->addChild($trRole, $permission);

        $permission = $auth->createPermission('ranking.update');
        $permission->description = 'Update existing Ranking';
        $auth->add($permission);
        $auth->addChild($trRole, $permission);

        $permission = $auth->createPermission('ranking.delete');
        $permission->description = 'Delete existing Ranking';
        $auth->add($permission);
        $auth->addChild($trRole, $permission);

        // Roles Hierarchy
        $auth->addChild($cfoRole, $trRole);
        $auth->addChild($cfoRole, $prRole);
        $auth->addChild($adminRole, $cfoRole);
        $auth->addChild($adminRole, $ceoRole);

        $users = [
            ['Алисьвяк Евгения Владимировна', null, 'Экономическая служба', 'murkinaev'],
        ];

        foreach ($users as $user) {
            $this->insert('user', [
                'code_1c' => trim($user[1]),
                'name' => trim($user[0]),
                'parent_name' => trim($user[2]),
                'username' => $user[3],
                'status' => User::STATUS_ACTIVE,
                'password_hash' => User::getSaltedPassword('password'),
            ]);
        }

        $auth->assign($adminRole, User::find()->select('id')->where(['username' => 'admin'])->scalar());
        $auth->assign($prRole,    User::find()->select('id')->where(['username' => 'murkinaev'])->scalar());
        $auth->assign($cfoRole,   User::find()->select('id')->where(['username' => 'glybinami'])->scalar());
    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        $auth = \Yii::$app->authManager;

        $auth->remove($auth->getRule((new PaymentRequestCancelRule())->name));
        $auth->remove($auth->getRule((new PaymentRequestUpdateRule())->name));

        $auth->remove($auth->getPermission('pr.view'));
        $auth->remove($auth->getPermission('pr.create'));
        $auth->remove($auth->getPermission('pr.update'));
        $auth->remove($auth->getPermission('pr.update.my'));
        $auth->remove($auth->getPermission('pr.cancel'));
        $auth->remove($auth->getPermission('pr.cancel.my'));
        $auth->remove($auth->getPermission('pr.approve'));

        $auth->remove($auth->getPermission('ranking.view'));
        $auth->remove($auth->getPermission('ranking.create'));
        $auth->remove($auth->getPermission('ranking.update'));
        $auth->remove($auth->getPermission('ranking.delete'));

        $auth->remove($auth->getRole('ADMIN'));
        $auth->remove($auth->getRole('USER'));
        $auth->remove($auth->getRole('CEO'));
        $auth->remove($auth->getRole('CFO'));
        $auth->remove($auth->getRole('TREASURER'));
        $auth->remove($auth->getRole('PR_RESPONSIBLE'));

        User::deleteAll(['username' => 'murkinaev']);
        User::deleteAll(['username' => 'glybinami']);
    }
}
