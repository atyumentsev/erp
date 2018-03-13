<?php

use yii\db\Migration;

/**
 * Class m171220_152305_payment_process
 */
class m180311_183100_pr_upload extends Migration
{
    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        $this->addColumn('payment_request', 'uuid', $this->string(36));
        $this->createIndex('payment_request_uuid', 'payment_request', 'uuid', true);

        $this->addColumn('payment_request', 'expense_date', $this->date());
        $this->addColumn('payment_request', 'status_1c', $this->string(30));
        $this->addColumn('payment_request', 'last_approver_id', $this->integer());

        $this->addForeignKey(
            'fk_payment_request_last_approver_id',
            'payment_request',
            'last_approver_id',
            'user',
            'id'
        );
    }

    public function safeDown()
    {
        $this->dropColumn('payment_request', 'uuid');
        $this->dropColumn('payment_request', 'expense_date');
        $this->dropColumn('payment_request', 'status_1c');
//        $this->dropColumn('payment_request', 'last_approver_id');
    }
}
