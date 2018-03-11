<?php

use yii\db\Migration;

/**
 * Class m171218_135540_dicts_editor
 */
class m171218_135540_dicts_editor extends Migration
{
    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        $auth = \Yii::$app->authManager;

        // Roles
        $methRole = $auth->createRole('METHODOLOGIST');
        $auth->add($methRole);

        $userRole = $auth->getRole('USER');
        $cfoRole = $auth->getRole('CFO');
        $trRole = $auth->getRole('TREASURER');

        $userAdminRole = $auth->createRole('USER.ADMIN');
        $userAdminRole->description = 'Users Admin';
        $auth->add($userAdminRole);

        // dicts
        $permission = $auth->createPermission('dict.view');
        $permission->description = 'View Dictionaries';
        $auth->add($permission);
        $auth->addChild($methRole, $permission);
        $auth->addChild($userRole, $permission);

        $permission = $auth->createPermission('dict.create');
        $permission->description = 'Create new item in Dictionaries';
        $auth->add($permission);
        $auth->addChild($methRole, $permission);

        $permission = $auth->createPermission('dict.update');
        $permission->description = 'Update existing items in Dictionaries';
        $auth->add($permission);
        $auth->addChild($methRole, $permission);

        $permission = $auth->createPermission('dict.delete');
        $permission->description = 'Delete existing items in Dictionaries';
        $auth->add($permission);
        $auth->addChild($methRole, $permission);

        // Banks
        $permission = $auth->createPermission('bank.view');
        $permission->description = 'View Dictionaries';
        $auth->add($permission);
        $auth->addChild($trRole, $permission);
        $auth->addChild($userRole, $permission);

        $permission = $auth->createPermission('bank.create');
        $permission->description = 'Create new item in Dictionaries';
        $auth->add($permission);
        $auth->addChild($trRole, $permission);

        $permission = $auth->createPermission('bank.update');
        $permission->description = 'Update existing items in Dictionaries';
        $auth->add($permission);
        $auth->addChild($trRole, $permission);

        $permission = $auth->createPermission('bank.delete');
        $permission->description = 'Delete existing items in Dictionaries';
        $auth->add($permission);
        $auth->addChild($trRole, $permission);

        // add to CFO
        $auth->addChild($cfoRole, $methRole);

        // users
        $permission = $auth->createPermission('user.view');
        $permission->description = 'View Dictionaries';
        $auth->add($permission);
        $auth->addChild($userAdminRole, $permission);
        $auth->addChild($userRole, $permission);

        $permission = $auth->createPermission('user.create');
        $permission->description = 'Create new User';
        $auth->add($permission);
        $auth->addChild($userAdminRole, $permission);

        $permission = $auth->createPermission('user.update');
        $permission->description = 'Update existing User';
        $auth->add($permission);
        $auth->addChild($userAdminRole, $permission);

        $permission = $auth->createPermission('user.delete');
        $permission->description = 'Delete existing User';
        $auth->add($permission);
        $auth->addChild($userAdminRole, $permission);

        // add to admin
        $adminRole = $auth->getRole('ADMIN');
        $auth->addChild($adminRole, $userAdminRole);
    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        $auth = \Yii::$app->authManager;

        $auth->remove($auth->getPermission('dict.view'));
        $auth->remove($auth->getPermission('dict.create'));
        $auth->remove($auth->getPermission('dict.update'));
        $auth->remove($auth->getPermission('dict.delete'));

        $auth->remove($auth->getPermission('bank.view'));
        $auth->remove($auth->getPermission('bank.create'));
        $auth->remove($auth->getPermission('bank.update'));
        $auth->remove($auth->getPermission('bank.delete'));

        $auth->remove($auth->getPermission('user.view'));
        $auth->remove($auth->getPermission('user.create'));
        $auth->remove($auth->getPermission('user.update'));
        $auth->remove($auth->getPermission('user.delete'));

        $auth->remove($auth->getRole('METHODOLOGIST'));
        $auth->remove($auth->getRole('USER.ADMIN'));
    }
}
