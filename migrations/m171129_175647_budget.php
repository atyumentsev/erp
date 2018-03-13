<?php

use yii\db\Migration;
use app\models\Department;

/**
 * Class m171129_175647_budget
 */
class m171129_175647_budget extends Migration
{
    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        $this->createTable('budget_period', [
            'id'            => $this->primaryKey(),
            'name'          => $this->string()->notNull()->unique(),
            'date_start'    => $this->date()->notNull(),
            'date_finish'   => $this->date()->notNull(),
            'created_at'    => $this->integer(),
            'updated_at'    => $this->integer(),
        ]);

        // Center of financial responsibility
        $this->createTable('budget_cfr', [
            'id'            => $this->primaryKey(),
            'name'          => $this->string()->notNull()->unique(),
            'department_id' => $this->integer(),
            'created_at'    => $this->integer(),
            'updated_at'    => $this->integer(),
        ]);

        $this->addForeignKey(
            'fk_budget_cfr_dpt_id',
            'budget_cfr',
            'department_id',
            'department',
            'id',
            'CASCADE',
            'CASCADE'
        );

        $this->createTable('budget', [
            'id'                => $this->primaryKey(),
            'budget_period_id'  => $this->integer()->notNull(),
            'cashflow_item_id'  => $this->integer()->notNull(),
            'budget_cfr_id'     => $this->integer()->notNull(),
            'amount'            => $this->bigInteger()->notNull(),
            'created_at'        => $this->integer()->notNull(),
            'updated_at'        => $this->integer()->notNull(),
        ]);

        $this->createIndex('budget_unique', 'budget', ['budget_period_id', 'cashflow_item_id', 'budget_cfr_id'], true);

        $this->addForeignKey(
            'fk_budget_bp',
            'budget',
            'budget_period_id',
            'budget_period',
            'id',
            'CASCADE',
            'CASCADE'
        );

        $this->addForeignKey(
            'fk_budget_cfi',
            'budget',
            'cashflow_item_id',
            'cashflow_item',
            'id',
            'CASCADE',
            'CASCADE'
        );

        $this->addForeignKey(
            'fk_budget_dpt',
            'budget',
            'budget_cfr_id',
            'budget_cfr',
            'id',
            'CASCADE',
            'CASCADE'
        );

        $this->batchInsert('budget_period', ['name', 'date_start', 'date_finish'], [
            ['1q 2017', '2017-01-01', '2017-03-31'],
            ['2q 2017', '2017-04-01', '2017-06-30'],
            ['3q 2017', '2017-07-01', '2017-09-30'],
            ['4q 2017', '2017-10-01', '2017-12-31'],
            ['1q 2018', '2018-01-01', '2018-03-31'],
            ['2q 2018', '2018-04-01', '2018-06-30'],
            ['3q 2018', '2018-07-01', '2018-09-30'],
            ['4q 2018', '2018-10-01', '2018-12-31'],
        ]);

        $departments = [
            ['БухЛ', 'Бухгалтерия Люмэкс', 'Бухгалтерия Люмэкс'],
            ['ПРО', 'Производственный отдел', 'Производственный отдел'],
            ['ЛЦ', 'Люмэкс Центрум', 'Люмэкс Центрум'],
            ['ГБ', 'Генбит (Центрум)', 'Генбит (Центрум)'],
            ['LA', 'Lumex Analytics', 'Lumex Analytics'],
            ['АТМ', 'Атомприбор', 'Атомприбор'],
            ['Винтел', 'Винтел', 'Винтел'],
            ['Lx', 'Litmex', 'Litmex'],
            ['LI', 'Lumex Instruments', 'Lumex Instruments'],
            ['КПП', 'Canada', 'Canada'],
            ['Beijing', 'Beijing', 'Beijing'],
            ['HKI', 'Hong Kong', 'Hong Kong'],
            ['LIF', 'LI Finance', 'LI Finance'],
            ['ЛМарин', 'Люмэкс Марин', 'Люмэкс Марин'],
            ['ЛММ', 'ЛММ', 'ЛММ'],
        ];
        foreach ($departments as $i => $row) {
            $departments[$i][] = time();
            $departments[$i][] = time();
        }

        $this->batchInsert('department', ['short_name', 'name', 'full_name', 'created_at', 'updated_at'], $departments);

        $departments = Department::find()->select('id, short_name')->all();
        $departments = array_column($departments, 'id', 'short_name');

        $raw_data = [
            ['ИТОГО все подразделения', null],
            ['СВОД подразделений ЛМ', null],
            ['Общефирменные расходы ЛМ', null],
            ['Бухгалтерия ЛМ', 'БухЛМ'],
            ['ВЭД (LM International)', 'ОВЭД'],
            ['Дирекция (в тч ЮС)', 'Дир'],
            ['ИС', 'ИС'],
            ['1С проект', 'ИС'],
            ['КС', 'КС'],
            ['ЛВС', 'ИС'],
            ['МБО', 'МБО'],
            ['МС', 'МС'],
            ['НЭО', 'НЭО'],
            ['ОО', 'ОО'],
            ['ОРОС', 'ОРОС'],
            ['ОП (LM Domestic)', 'ОП'],
            ['ОСМА', 'ОСМА'],
            ['ПЛБ', 'ПЛБ'],
            ['СГИ (АХО)', 'СГИ'],
            ['СО', 'СО'],
            ['ССК', 'ССК'],
            ['ЭО', 'ЭО'],
            ['Аналитический отдел', 'АО'],
            ['СВОД подразделений Л', null],
            ['Общефирменные расходы Л', null],
            ['Бухгалтерия Люмэкс', 'БухЛ'],
            ['ПРО', 'ПРО'],
            ['Люмэкс Центрум', 'ЛЦ'],
            ['Генбит (Центрум)', 'ГБ'],
            ['Lumex Analytics', 'LA'],
            ['Атомприбор', 'АТМ'],
            ['Винтел', 'Винтел'],
            ['Litmex', 'Lx'],
            ['Lumex Instruments', 'LI'],
            ['Canada', 'КПП'],
            ['Beijing', 'Beijing'],
            ['Hong Kong', 'HKI'],
            ['LI Finance', 'LIF'],
            ['Люмэкс Марин', 'ЛМарин'],
        ];

        $cfr_data = [];
        foreach ($raw_data as $row) {
            $cfr_data[] = [$row[0], $departments[$row[1]] ?? null, time(), time()];
        }

        $this->batchInsert('budget_cfr', ['name', 'department_id', 'created_at', 'updated_at'], $cfr_data);

        $cashflow_items = [
            ['Д6226', '1. Операционная деятельность', 'Закупки хоз.товаров'],
        ];

        foreach ($cashflow_items as $_item) {
            $Item = new \app\models\CashflowItem([
                'code_1c'      => $_item[0],
                'parent_name'  => $_item[1],
                'full_name'    => $_item[2],
                'name'         => $_item[2],
            ]);
            $Item->save();
        }

        $cashflowItems = [
            'Д6290' => 'Внутригрупповые перемещения ДС',
            'Д6291' => 'Внутренне движение денежных средств',
            'Д6295' => 'Выдача подотчет',
            'Д6298' => 'Курсовые разницы',
            'Д6302' => 'Переводы в пути',
            'Д6303' => 'Представительские расходы',
            'Д6214' => 'Комиссионные и дилерские вознаграждения',
            'Д6215' => 'Денежные средства, направленные на командировку сотрудников',
            'Д6216' => 'Займы краткосрочные беспроцентные, предоставленные другим организациям',
            'Д6217' => 'Закупки канцелярии и расходных материалов к оргтехнике',
            'Д6218' => 'Закупки материалов и комплектующих по заказам клиентов (для перепродажи)',
            'Д6219' => 'Закупки материалов и услуг, направленные на научно-исследовательские работы',
            'Д6220' => 'Закупки мелкого производственного оборудования и инструмента',
            'Д6221' => 'Закупки оборудования и оргтехники по заказам клиентов (для перепродажи)',
            'Д6222' => 'Закупки общехозяйственные прочие',
            'Д6223' => 'Закупки основных материалов и комплектующих, направленные на производство',
            'Д6224' => 'Закупки расходных материалов, направленные на производство',
            'Д6225' => 'Закупки рекламной продукции и материалов',
            'Д6226' => 'Закупки хоз.товаров',
            'Д6227' => 'Аренда и содержание площадей',
            'Д6228' => 'Аудиторские услуги (администрация)',
            'Д6229' => 'Информационные услуги (администрация)',
            'Д6230' => 'Обеспечение конкурсных заявок, аукционов',
            'Д6231' => 'Консультационные услуги (администрация)',
            'Д6232' => 'Метрологические услуги и сертификация (администрация)',
            'Д6233' => 'Метрологические услуги и сертификация, направленные на маркетинг и продвижение',
            'Д6234' => 'Патентование и лицензирование НМА',
            'Д6235' => 'Обучение сотрудников (административный персонал)',
            'Д6236' => 'Обучение сотрудников (маркетинг и продвижение)',
            'Д6237' => 'Обучение сотрудников (производство)',
            'Д6238' => 'Патентование и лицензирование (администрация)',
            'Д6239' => 'Семинары, выставки, маркетинговые мероприятия',
            'Д6240' => 'Телефония и Интернет (администрация)',
            'Д6241' => 'Транспортные и экспедиторские услуги (маркетинг и продвижение)',
            'Д6242' => 'Оплата труда работников',
            'Д6243' => 'Услуги административного характера прочие',
            'Д6244' => 'Услуги банков, обслуживание платежных систем и расчетных счетов',
            'Д6245' => 'Услуги по заказам клиентов (для перепродажи)',
            'Д6246' => 'Услуги почтовой связи (администрация)',
            'Д6247' => 'Услуги производственного характера (субподрядные работы)',
            'Д6248' => 'Платежи по налогам и сборам (кроме налога на прибыль и налогов с заработной платы)',
            'Д6249' => 'Платежи по налогу на прибыль',
            'Д6250' => 'Поступления от погашения беспроцентных краткосрочных займов предоставленных',
            'Д6251' => 'Поступления от покупателей, заказчиков',
            'Д6252' => 'Прочие закупки и услуги, направленные на маркетинг и продвижение',
            'Д6253' => 'Ремонт, обслуживание и поддержание в рабочем состоянии оборудования, автотранспорта и оргтехники',
            'Д6254' => 'Страховые платежи и взносы (администрация)',
            'Д6255' => 'Таможенные платежи и сборы (импорт)',
            'Д6256' => 'Таможенные платежи и сборы (экспорт)',
            'Д6257' => 'Возврат денежных средств от поставщиков*',
            'Д6258' => 'Возврат денежных средств, направленных на командировку сотрудников*',
            'Д6259' => 'Возврат денежных средств покупателям, заказчикам*',
            'Д6260' => 'Возврат обеспечения конкурсных заявок, аукционов*',
            'Д6261' => 'Прочие платежи по текущей деятельности',
            'Д6262' => 'Прочие поступления от текущей деятельности',
            'Д6263' => 'Метрологические услуги и сертификация НМА',
            'Д6264' => 'Поступления от продажи нематериальных активов',
            'Д6265' => 'Поступления от продажи объектов основных средств',
            'Д6266' => 'Поступления от продажи ценных бумаг и иных финансовых вложений',
            'Д6267' => 'Займы, предоставленные другим организациям',
            'Д6269' => 'Полученные проценты по займам предоставленным',
            'Д6270' => 'Поступления от погашения займов предоставленных',
            'Д6271' => 'Закупки основных материалов и комплектующих, направленные на разработки (на создание НМА)',
            'Д6272' => 'Закупки расходных материалов, направленные на разработки (на создание НМА)',
            'Д6273' => 'Услуги (субподрядные работы), направленные на разработки (на создание НМА)',
            'Д6274' => 'Приобретение объектов основных средств',
            'Д6275' => 'Приобретение ценных бумаг и иных финансовых вложений',
            'Д6276' => 'Прочие поступления по инвестиционной деятельности',
            'Д6277' => 'Прочие платежи по инвестиционной деятельности',
            'Д6278' => 'Выплата дивидендов',
            'Д6279' => 'Выплата процентов по займам и кредитам полученным',
            'Д6280' => 'Погашение займов и кредитов полученных (без процентов)',
            'Д6281' => 'Поступления займов и кредитов полученных',
            'Д6282' => 'Поступления от эмиссии акций или иных долевых бумаг',
            'Д6283' => 'Полученные субсидии, гранты и иное целевое финансирование',
            'Д6284' => 'Прочие поступления по финансовой деятельности',
            'Д6285' => 'Прочие платежи по финансовой деятельности',
            'Д6268' => 'Полученные дивиденды',
            'Д6286' => 'Премии',
            'Д6287' => 'Платежи по налогам и сборам (взносы в фонды)',
            'Д6288' => 'Платежи по налогам и сборам (НДФЛ)',
            'Д6289' => 'Транспортные услуги (импорт, доставка от поставщика)',
        ];
        foreach ($cashflowItems as $code_1c => $name) {
            $this->update('cashflow_item', ['name' => $name], "code_1c = '{$code_1c}'");
        }
    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        $this->dropTable('budget');
        $this->dropTable('budget_period');
        $this->dropTable('budget_cfr');
    }
}
