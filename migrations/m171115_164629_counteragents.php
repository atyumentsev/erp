<?php

use yii\db\Migration;

/**
 * Class m171115_164629_counteragents
 */
class m171115_164629_counteragents extends Migration
{
    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        $this->createTable('counteragent', [
            'id' => $this->primaryKey(10)->unsigned(),
            'code_1c' => $this->string(100)->notNull()->unique(),
            'name' => $this->string(200),
            'full_name' => $this->string(500),
            'inn' => $this->string(20),
            'kpp' => $this->string(20),
            'ib_code' => $this->string(20),
            'type' => $this->string(10),
            'created_at' => $this->integer()->notNull(),
            'updated_at' => $this->integer()->notNull(),
        ]);
    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        $this->dropTable('counteragent');
    }
}
