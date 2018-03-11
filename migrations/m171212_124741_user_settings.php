<?php

use yii\db\Migration;

/**
 * Class m171212_124741_user_settings
 */
class m171212_124741_user_settings extends Migration
{
    public function safeUp()
    {
        $this->createTable('user_settings', [
            'id'            => $this->primaryKey(),
            'user_id'       => $this->integer()->notNull(),
            'name'          => $this->string(30)->notNull(),
            'value'         => $this->text()->notNull(),
            'created_at'    => $this->integer()->notNull(),
            'updated_at'    => $this->integer()->notNull(),
        ]);

        $this->createIndex('user_settings_unique', 'user_settings', ['user_id', 'name'], true);

        $this->addForeignKey(
            'user_settings_user',
            'user_settings',
            'user_id',
            'user',
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
        $this->dropTable('user_settings');
    }
}
