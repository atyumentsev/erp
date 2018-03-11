<?php

use yii\db\Migration;

/**
 * Class m171116_094138_contracts_table
 */
class m171116_094138_contracts_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        $this->createTable('contract', [
            'id' => $this->primaryKey(10)->unsigned(),
            'code_1c' => $this->string(100)->notNull()->unique(),
            'name' => $this->string(200),
            'currency_id' => $this->smallInteger(),
            'counteragent_id' => $this->integer(),
            'affiliate_id' => $this->integer(),
            'type' => $this->string(30),
            'number' => $this->string(50),
            'signed_at' => $this->date(),
            'created_at' => $this->integer()->notNull(),
            'updated_at' => $this->integer()->notNull(),
        ]);

        $this->createTable('affiliate', [
            'id' => $this->primaryKey(10)->unsigned(),
            'code_1c' => $this->string(100)->unique(),
            'name' => $this->string(200),
            'prefix' => $this->string(30),
            'created_at' => $this->integer()->notNull(),
            'updated_at' => $this->integer()->notNull(),
        ]);
        
        
        $affiliates = [
            ['000000019', 'HKI', 'Hong Kong Instruments Ltd'],
            ['000000020', 'LIF', 'LI Finance Limited'],
            ['000000023', 'Lx', 'Litmex Ltd'],
            ['000000022', 'Ln', 'Litnin Ltd'],
            ['000000021', 'LI', 'Lumex Instruments Limited'],
            ['000000014', 'LA', 'LumexAnalytics'],
            ['000000001', 'АТМ', 'Атомприбор'],
            ['000000017', 'ВТ', 'Винтэл'],
            ['000000012', 'ГБ', 'ГенБит'],
            ['000000011', 'КПП', 'Канадская производственная площадка'],
            ['000000024', 'ЛММ', 'ЛММ'],
            ['000000016', 'ПрО', 'Люмэкс'],
            ['000000018', 'МАР', 'Люмэкс-Марин'],
            ['000000013', 'ЛЦ', 'Люмэкс-Центрум'],
            ['000000015', 'ПП', 'Пекинское представительство'],
            [null, 'ЛМ', 'Люмэкс-маркетинг'],
            ['000000002', 'ПЩК', 'Продающая компания'],
            ['000000008', 'СО', 'Спектрометрическое отделение'],
            ['000000006', 'УК', 'Управляющая компания'],
            ['000000009', 'МБО', 'Медико-биологическое отделение'],
            ['000000005', 'ПК1', 'Отделение сепарационных методов анализа'],
        ];

        foreach ($affiliates as $i => $row) {
            $affiliates[$i][] = time();
            $affiliates[$i][] = time();
        }

        $this->batchInsert('affiliate', ['code_1c', 'prefix', 'name', 'created_at', 'updated_at'], $affiliates);

        $currencies = [
            [124,  'CAD', "Canadian dollar",  2, "U+0024"],
            [156,  'CNY', "Chinese yuan",     2, "U+00a5"],
        ];

        foreach ($currencies as $i => $row) {
            $currencies[$i][] = time();
            $currencies[$i][] = time();
        }
        $this->batchInsert('currency', ['id', 'code', 'name', 'units', 'sign', 'created_at', 'updated_at'], $currencies);
    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        $this->dropTable('contract');
        $this->dropTable('affiliate');

        $this->delete('currency', 'id IN (124, 156)');
    }
}
