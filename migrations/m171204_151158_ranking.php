<?php

use yii\db\Migration;

/**
 * Class m171204_151158_ranking
 */
class m171204_151158_ranking extends Migration
{
    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        $this->createTable('ranking', [
            'id'            => $this->primaryKey(),
            'entity_type'   => $this->string(60)->notNull(),
            'entity_id'     => $this->integer()->notNull(),
            'priority'      => $this->integer()->notNull(),
            'created_at'    => $this->integer()->notNull(),
            'updated_at'    => $this->integer()->notNull(),
        ]);

        $this->createIndex('ranking_unique', 'ranking', ['entity_type', 'entity_id'], true);

        $this->addColumn(
            'payment_request',
            'status',
            $this->smallInteger()->after('id')->notNull()->defaultValue(1)
        );
        $this->createIndex('pr_status', 'payment_request', 'status');
    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        $this->dropTable('ranking');
        $this->dropColumn('payment_request', 'status');
    }
}
