<?php

use yii\db\Migration;

/**
 * Class m171120_111746_invoice
 */
class m171120_111746_invoice extends Migration
{
    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        $this->createTable('payment_request', [
            'id'                        => $this->primaryKey(10)->unsigned(),
            'code_1c'                   => $this->string(100)->unique(),
            'customer_department_id'    => $this->integer(),
            'executor_department_id'    => $this->integer(),
            'internal_number'           => $this->string(30),
            'payer_organization_id'     => $this->integer(),
            'payment_part'              => $this->float(),
            'original_currency_id'      => $this->integer(),
            'original_price'            => $this->bigInteger(),
            'conversion_percent'        => $this->float(),
            'required_payment'          => $this->bigInteger(),
            'price_rub'                 => $this->bigInteger(),
            'required_payment_rub'      => $this->bigInteger(),
            'contract_id'               => $this->integer(),
            'contract_date'             => $this->date(),
            'contract_number'           => $this->string(30),
            'invoice_number'            => $this->string(30),
            'invoice_date'              => $this->date(),
            'payment_date'              => $this->date(),
            'counteragent_id'           => $this->integer(),
            'product_id'                => $this->integer(),
            'cashflow_item_id'          => $this->integer(),
            'description'               => $this->string(300),
            'author_id'                 => $this->integer(),
            'due_date'                  => $this->date(),
            'urgency'                   => $this->smallInteger(),
            'expected_delivery'         => $this->string(100),
            'note'                      => $this->string(500),
            'created_at'                => $this->integer()->notNull(),
            'updated_at'                => $this->integer()->notNull(),
        ]);

        $data = [
            ['Произв-во.Осн. материалы и компл.', 'Закупки основных материалов и комплектующих, направленные на производство', 'Закупка (вт.ч. импорт) материалов и комплектующих для использования их в процессе изготовления полуфабрикатов и готовой продукции, для поддержания складских запасов; закупки реактивов для лаборатории (ОП) с целью формирования наборов для реализации, комплектующих (ЗИП) для ремонта и сервисного обслуживания приборов.'],
            ['Произв-во. Расходные материалы', 'Закупки расходных материалов, направленные на производство', 'Закупка разного рода жидкостей (ацетон, кислоты, масло), газов (гексан, азот), пробирок, колб, кювет, припоя, кабелей, скотча и прочих расходных материалов для производственных целей; упаковочная тара и материалы; закупка аргона и хим.посуды для лаборатории.'],
            ['Произв-во. Услуги', 'Услуги производственного характера (субподрядные работы)', 'Оплата услуг производственного характера - поверка приборов (первичная и периодическая), поверка дозаторов, субподрядные работы по оказанию услуг производственного характера (в т.ч. изготовление механики, гальваника, шелкография).'],
            ['Проект НМА. Осн. материалы', 'Закупки основных материалов и комплектующих, направленные на разработки (на создание НМА)', 'Закупка материалов и комплектующих для использования их в процессе опытно-конструкторских разработок, проектирования с целью создания нематериальных активов: нового оборудования, приборов, ПО, методик (см. список проектов, направленных на создание НМА). Обязательно с указанием проекта!'],
            ['Проект НМА. Расходные материалы', 'Закупки расходных материалов, направленные на разработки (на создание НМА)', 'Закупка расходных материалов (хим.реактивов, салфеток, жидкойстей, газов, кабелей, разъемов, пленок, лаков) для использования их в процессе опытно-конструкторских разработок, проектирования с целью создания нематериальных активов: нового оборудования, приборов, ПО, методик (см. список проектов). Обязательно с указанием проекта!'],
            ['Проект НМА. Услуги', 'Услуги (субподрядные работы), направленные на разработки (на создание НМА)', 'Оплата услуг (субподрядные работы) в рамках опытно-конструкторских разработок, проектирования с целью создания нематериальных активов: нового оборудования, приборов, ПО, методик (см. список проектов). Обязательно с указанием проекта!'],
            ['(не проект НМА). НИР. Материалы и услуги', 'Закупки материалов и услуг, направленные на научно-исследовательские работы', 'Платежи за закупку комплектующих, оказание услуг, закупку материалов для использования их в процессе научно-исследовательских работ, не имеющих целью создание конкретных НМА: приборов, ПО, методик (см. список проектов), а с целью модернизации или стандартизации существующих, создания соответствующей конструкторской и технологической документации, разработки требований, поиска альтернатив материалам, процессам, услугам и т.п. Обязательно с указанием проекта!'],
            ['ОС', 'Приобретение объектов основных средств', 'Платежи для приобретения объектов основных средств, в т.ч. оборудования, дорогостоящей оргтехники (и ПО к ним), а также прочих долгосрочных активов, стоимостью  ≥20 000 руб., в т.ч. отсроченные платежи по приобретению ОС в рассрочку.'],
            ['содержание оборудования', 'Ремонт, обслуживание и поддержание в рабочем состоянии оборудования, автотранспорта и оргтехники', 'Платежи с целью текущего ремонта, сервисного обслуживания, дооборудования основных средств, оборудования, оргтехники (в т.ч. необходимые комплектующие и запчасти), а также обслуживание общефирменных систем (вентиляции, контроля доступа, пожаротушения, видеонаблюдения и т.п.), их диагностика, техническое обслуживание'],
            ['МБП', 'Закупки мелкого производственного оборудования и инструмента', 'Платежи для приобретения объектов МБП, в т.ч. оргтехники небольшой стоимости (< 20 000 рублей), ПО к оргтехнике, комплектующих к оборудованию и оргтехнике, мебели, инструмента, оборудование рабочих мест и т.п.'],
            ['Произв-во. Обучение', 'Обучение сотрудников (производство)', 'Обучение сотрудников производственных подразделений, закупки периодической и обучающей литературы'],
            ['Офисные товары', 'Закупки канцелярии и расходных материалов к оргтехнике', 'Закупка канцелярских товаров, бумаги, офисных принадлежностей и расходных материалов к оргтехнике (пленок, картриджей, дисков, фотобарабанов, тонеров)'],
            ['Хоз.товары', 'Закупки общехозяйственные прочие', 'Закупки хозяйственных и санитарно-гигиенических товаров'],
            ['Прочее', 'Прочие платежи по текущей деятельности', 'Платежи, направленные на прочие операционные цели (статья используется в случае невозможности отнесения платежа к имеющимся статьям движения по операционной деятельности или в случае невозможности четкой идентификации платежа)'],
        ];

        foreach ($data as $row) {
            $item = \app\models\CashflowItem::findOne(['full_name' => $row[1]]);
            if (!$item) {
                echo "{$row[1]} does not exist";
                return false;
            }
            $item->short_name = $row[0];
            $item->description = $row[2];
            $item->flags = \app\models\CashflowItem::FLAG_FOR_INVOICES;
            $item->save();
        }

        $this->addColumn('affiliate', 'flags', $this->integer()->notNull()->defaultValue(0)->after('description'));
        $this->addColumn('affiliate', 'short_name', $this->string(30)->after('prefix'));

        $this->update('affiliate', ['short_name' => new \yii\db\Expression('prefix')]);
        $this->update('affiliate', ['short_name' => 'Л'], ['code_1c' => '000000016']);
        $this->update('affiliate', ['flags' => 1], ['name' => ['Люмэкс-маркетинг', 'Люмэкс']]);

        return true;
    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        $this->dropTable('payment_request');
        $this->dropColumn('affiliate', 'flags');
        $this->dropColumn('affiliate', 'short_name');
    }
}
