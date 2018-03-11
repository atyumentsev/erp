<?php

use yii\db\Migration;
use app\models\User;

/**
 * Class m171220_152305_payment_process
 */
class m171220_152305_payment_process extends Migration
{
    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        $auth = \Yii::$app->authManager;
        $trRole = $auth->getRole('TREASURER');
        $cfoRole = $auth->getRole('CFO');

        $permission = $auth->createPermission('pr.select');
        $permission->description = 'Select Payment Requests';
        $auth->add($permission);
        $auth->addChild($trRole, $permission);

        $permission = $auth->createPermission('pr.send_to_accountant');
        $permission->description = 'Send Payment Requests to Accounting';
        $auth->add($permission);
        $auth->addChild($cfoRole, $permission);

        $permission = $auth->createPermission('bank.balance.edit');
        $permission->description = 'Edit Bank Balance';
        $auth->add($permission);
        $auth->addChild($trRole, $permission);

        $permission = $auth->createPermission('bank.balance.view');
        $permission->description = 'View Bank Balance';
        $auth->add($permission);
        $auth->addChild($trRole, $permission);

        $auth->assign($trRole, User::find()->select('id')->where(['username' => 'murkinaev'])->scalar());
        $auth->assign($trRole, User::find()->select('id')->where(['username' => 'zaycevaoa'])->scalar());

        $this->addColumn('bank', 'flags', $this->integer()->notNull()->defaultValue(0));

        $this->update('bank', ['flags' => 1], ['id' => [1, 2, 3]]);

        $this->createTable('bank_account_balance', [
            'id' => $this->primaryKey(),
            'date' => $this->date(),
            'bank_account_id' => $this->integer()->notNull(),
            'balance' => $this->bigInteger(),
            'paid' => $this->bigInteger(),
        ]);
        $this->createIndex('bab_pk', 'bank_account_balance', ['date', 'bank_account_id'], true);

        $this->addForeignKey('bab_fk_ba_id', 'bank_account_balance', 'bank_account_id', 'bank_account', 'id');
    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        $auth = \Yii::$app->authManager;

        $auth->remove($auth->getPermission('pr.select'));
        $auth->remove($auth->getPermission('pr.send_to_accountant'));
        $auth->remove($auth->getPermission('bank.balance.edit'));
        $auth->remove($auth->getPermission('bank.balance.view'));

        $trRole = $auth->getRole('TREASURER');

        $auth->revoke($trRole, User::find()->select('id')->where(['username' => 'murkinaev'])->scalar());
        $auth->revoke($trRole, User::find()->select('id')->where(['username' => 'zaycevaoa'])->scalar());

        $this->dropColumn('bank', 'flags');
        $this->dropTable('bank_account_balance');
    }
}
