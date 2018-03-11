<?php

use yii\db\Migration;

/**
 * Class m171128_101408_payment_request_files
 */
class m171128_101408_payment_request_files extends Migration
{
    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        $this->createTable('file', [
            'id'         => $this->primaryKey(),
            'name'       => $this->string()->notNull(),
            'mime'       => $this->string(128)->notNull(),
            'content'    => $this->binary()->notNull(),
            'user_id'    => $this->integer(11)->unsigned()->notNull(),
            'created_at' => $this->integer(),
            'updated_at' => $this->integer(),
        ]);

        $this->createTable('payment_request_file', [
            'file_id'            => $this->integer()->notNull(),
            'payment_request_id' => $this->integer()->notNull(),
            'created_at'         => $this->integer()->notNull(),
        ]);

        $this->createIndex('prf_file_id', 'payment_request_file', 'file_id');
        $this->createIndex('prf_payment_request_id', 'payment_request_file', 'payment_request_id');

        $this->addForeignKey(
            'fk_prf_file',
            'payment_request_file',
            'file_id',
            'file',
            'id',
            'CASCADE',
            'CASCADE'
        );
    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        $this->dropTable('payment_request_file');
        $this->dropTable('file');
    }
}
