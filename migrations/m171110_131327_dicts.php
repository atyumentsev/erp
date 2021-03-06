<?php

use yii\db\Migration;
use app\models\Product;
use app\models\CashflowItem;
use app\models\Currency;
use app\models\Department;

/**
 * Class m171110_131327_dicts
 */
class m171110_131327_dicts extends Migration
{
    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        $this->createTable('cashflow_item', [
            'id' => $this->primaryKey(10)->unsigned(),
            'code_1c' => $this->string(100)->notNull()->unique(),
            'parent_name' => $this->string(100),
            'short_name' => $this->string(50),
            'name' => $this->string(100)->unique(),
            'full_name' => $this->string(100)->unique(),
            'description' => $this->string(500),
            'flags' => $this->smallInteger()->notNull()->defaultValue(0),
            'created_at' => $this->integer()->notNull(),
            'updated_at' => $this->integer()->notNull(),
        ]);

        $this->createTable('department', [
            'id' => $this->primaryKey(10)->unsigned(),
            'code_1c' => $this->string(100)->unique(),
            'name' => $this->string(100)->notNull()->unique(),
            'full_name' => $this->string(100)->notNull(),
            'short_name' => $this->string(100),
            'description' => $this->string(500),
            'created_at' => $this->integer()->notNull(),
            'updated_at' => $this->integer()->notNull(),
        ]);

        $this->createTable('currency', [
            'id'    => $this->primaryKey(10)->unsigned(),
            'code'  => $this->string(5)->notNull(),
            'name'  => $this->string(100)->notNull(),
            'units' => $this->smallInteger(5)->notNull(),
            'sign'  => $this->string(10)->notNull()->defaultValue(''),
            'created_at' => $this->integer()->notNull(),
            'updated_at' => $this->integer()->notNull(),
        ]);

        $this->createTable('product', [
            'id' => $this->primaryKey(10)->unsigned(),
            'code_1c' => $this->string(100)->notNull()->unique(),
            'name' => $this->string(100)->notNull()->unique(),
            'full_name' => $this->string(100),
            'description' => $this->string(500),
            'created_at' => $this->integer()->notNull(),
            'updated_at' => $this->integer()->notNull(),
        ]);

        $this->createDicts();
    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        $this->dropTable('cashflow_item');
        $this->dropTable('department');
        $this->dropTable('currency');
        $this->dropTable('product');
    }

    private function createDicts()
    {
        //
        // Статьи ДДС
        //
        $cashflow_items = [
            ['Д6227', '1. Операционная деятельность', 'Аренда и содержание площадей'],
            ['Д6228', '1. Операционная деятельность', 'Аудиторские услуги (администрация)'],
            ['Д6291', '4. ВГО', 'Внутреннее движение денежных средств'],
            ['Д6290', '4. ВГО', 'Внутригрупповые перемещения ДС'],
            ['Д6257', '1. Операционная деятельность', 'Возврат денежных средств от поставщиков*'],
            ['Д6259', '1. Операционная деятельность', 'Возврат денежных средств покупателям, заказчикам*'],
            ['Д6258', '1. Операционная деятельность', 'Возврат денежных средств, направленных на командировку сотрудников*'],
            ['Д6260', '1. Операционная деятельность', 'Возврат обеспечения конкурсных заявок, аукционов*'],
            ['Д6295', '1. Операционная деятельность', 'Выдача подотчет'],
            ['Д6278', '3. Финансовая деятельность', 'Выплата дивидендов'],
            ['Д6279', '3. Финансовая деятельность', 'Выплата процентов по займам и кредитам полученным'],
            ['Д6215', '1. Операционная деятельность', 'Денежные средства, направленные на командировку сотрудников'],
            ['Д6216', '2. Инвестиционная деятельность', 'Займы  беспроцентные, предоставленные другим организациям'],
            ['Д6267', '2. Инвестиционная деятельность', 'Займы, предоставленные другим организациям'],
            ['Д6217', '1. Операционная деятельность', 'Закупки канцелярии и расходных материалов к оргтехнике'],
            ['Д6218', '1. Операционная деятельность', 'Закупки материалов и комплектующих по заказам клиентов (для перепродажи)'],
            ['Д6219', '1. Операционная деятельность', 'Закупки материалов и услуг, направленные на научно-исследовательские работы'],
            ['Д6220', '1. Операционная деятельность', 'Закупки мелкого производственного оборудования и инструмента'],
            ['Д6221', '1. Операционная деятельность', 'Закупки оборудования и оргтехники по заказам клиентов (для перепродажи)'],
            ['Д6222', '1. Операционная деятельность', 'Закупки общехозяйственные прочие'],
            ['Д6223', '1. Операционная деятельность', 'Закупки основных материалов и комплектующих, направленные на производство'],
            ['Д6271', '2. Инвестиционная деятельность', 'Закупки основных материалов и комплектующих, направленные на разработки (на создание НМА)'],
            ['Д6224', '1. Операционная деятельность', 'Закупки расходных материалов, направленные на производство'],
            ['Д6272', '2. Инвестиционная деятельность', 'Закупки расходных материалов, направленные на разработки (на создание НМА)'],
            ['Д6225', '1. Операционная деятельность', 'Реклама'],
            ['Д6229', '1. Операционная деятельность', 'Информационные услуги (администрация)'],
            ['Д6214', '1. Операционная деятельность', 'Дилерские вознаграждения'],
            ['Д6304', '1. Операционная деятельность', 'Комиссионное вознаграждение'],
            ['Д6231', '1. Операционная деятельность', 'Консультационные услуги (администрация)'],
            ['Д6298', '5. Курсовые разницы', 'Курсовые разницы'],
            ['Д6232', '1. Операционная деятельность', 'Аттестация, сертификация и метрологические услуги (производство)'],
            ['Д6263', '1. Операционная деятельность', 'Аттестация, сертификация и метрологические услуги (НМА)'],
            ['Д6233', '1. Операционная деятельность', 'Аттестация, сертификация и метрологические услуги (продажи)'],
            ['Д6230', '1. Операционная деятельность', 'Обеспечение конкурсных заявок, аукционов'],
            ['Д6235', '1. Операционная деятельность', 'Обучение сотрудников (административный персонал)'],
            ['Д6236', '1. Операционная деятельность', 'Обучение сотрудников (маркетинг и продвижение)'],
            ['Д6237', '1. Операционная деятельность', 'Обучение сотрудников (производство)'],
            ['Д6242', '1. Операционная деятельность', 'Оплата труда работников'],
            ['Д6238', '1. Операционная деятельность', 'Патентование и лицензирование (администрация)'],
            ['Д6234', '2. Инвестиционная деятельность', 'Патентование и лицензирование НМА'],
            ['Д6302', '6. Переводы в пути',	'Переводы в пути'],
            ['Д6287', '1. Операционная деятельность', 'Платежи по налогам и сборам (взносы в фонды)'],
            ['Д6248', '1. Операционная деятельность', 'Платежи по налогам и сборам (кроме налога на прибыль и налогов с заработной платы)'],
            ['Д6288', '1. Операционная деятельность', 'Платежи по налогам и сборам (НДФЛ)'],
            ['Д6249', '1. Операционная деятельность', 'Платежи по налогу на прибыль'],
            ['Д6280', '3. Финансовая деятельность', 'Погашение займов и кредитов полученных (без процентов)'],
            ['Д6268', '2. Инвестиционная деятельность', 'Полученные дивиденды'],
            ['Д6269', '2. Инвестиционная деятельность', 'Полученные проценты по займам предоставленным'],
            ['Д6283', '3. Финансовая деятельность', 'Полученные субсидии, гранты и иное целевое финансирование'],
            ['Д6281', '3. Финансовая деятельность', 'Поступления займов и кредитов полученных'],
            ['Д6250', '3. Финансовая деятельность', 'Поступления от погашения беспроцентных  займов предоставленных'],
            ['Д6270', '3. Финансовая деятельность', 'Поступления от погашения займов предоставленных'],
            ['Д6251', '1. Операционная деятельность', 'Поступления от покупателей, заказчиков'],
            ['Д6264', '2. Инвестиционная деятельность', 'Поступления от продажи нематериальных активов'],
            ['Д6265', '2. Инвестиционная деятельность', 'Поступления от продажи объектов основных средств'],
            ['Д6266', '2. Инвестиционная деятельность', 'Поступления от продажи ценных бумаг и иных финансовых вложений'],
            ['Д6282', '3. Финансовая деятельность', 'Поступления от эмиссии акций или иных долевых бумаг'],
            ['Д6303', '1. Операционная деятельность', 'Представительские расходы'],
            ['Д6286', '1. Операционная деятельность', 'Премии'],
            ['Д6274', '2. Инвестиционная деятельность', 'Приобретение объектов основных средств'],
            ['Д6275', '2. Инвестиционная деятельность', 'Приобретение ценных бумаг и иных финансовых вложений'],
            ['Д6252', '1. Операционная деятельность', 'Прочие закупки и услуги, направленные на маркетинг и продвижение'],
            ['Д6277', '2. Инвестиционная деятельность', 'Прочие платежи по инвестиционной деятельности'],
            ['Д6261', '1. Операционная деятельность', 'Прочие платежи по текущей деятельности'],
            ['Д6285', '3. Финансовая деятельность', 'Прочие платежи по финансовой деятельности'],
            ['Д6262', '1. Операционная деятельность', 'Прочие поступления от текущей деятельности'],
            ['Д6276', '2. Инвестиционная деятельность', 'Прочие поступления по инвестиционной деятельности'],
            ['Д6284', '3. Финансовая деятельность', 'Прочие поступления по финансовой деятельности'],
            ['Д6253', '1. Операционная деятельность', 'Ремонт, обслуживание и поддержание в рабочем состоянии оборудования, автотранспорта и оргтехники'],
            ['Д6239', '1. Операционная деятельность', 'Семинары, выставки, маркетинговые мероприятия'],
            ['Д6254', '1. Операционная деятельность', 'Страховые платежи и взносы'],
            ['Д6255', '1. Операционная деятельность', 'Таможенные платежи и сборы (импорт)'],
            ['Д6256', '1. Операционная деятельность', 'Таможенные платежи и сборы (экспорт)'],
            ['Д6240', '1. Операционная деятельность', 'Телефония и Интернет'],
            ['Д6241', '1. Операционная деятельность', 'Транспортные и экспедиторские услуги (маркетинг и продвижение)'],
            ['Д6289', '1. Операционная деятельность', 'Транспортные услуги (импорт, доставка от поставщика)'],
            ['Д6273', '2. Инвестиционная деятельность', 'Услуги (субподрядные работы), направленные на разработки (на создание НМА)'],
            ['Д6243', '1. Операционная деятельность', 'Услуги административного характера прочие'],
            ['Д6244', '1. Операционная деятельность', 'Услуги банков, обслуживание платежных систем и расчетных счетов'],
            ['Д6245', '1. Операционная деятельность', 'Услуги по заказам клиентов (для перепродажи)'],
            ['Д6246', '1. Операционная деятельность', 'Услуги почтовой связи'],
            ['Д6247', '1. Операционная деятельность', 'Услуги производственного характера (субподрядные работы)'],
        ];

        foreach ($cashflow_items as $_item) {
            $Item = new CashflowItem([
                'code_1c'      => $_item[0],
                'parent_name'  => $_item[1],
                'full_name'    => $_item[2],
                'name'         => $_item[2],
            ]);
            $Item->save();
        }

        //
        // Подразделения
        //
        \Yii::$app->db->createCommand()->truncateTable(Department::tableName());
        $departments = [
            ['Медико-биологическое отделение'],
            ['Отделение сепарационных методов анализа'],
            ['Организационный отдел'],
            ['Отдел разработок обучения и сервиса'],
            ['Отдел продаж'],
            ['Научно-экспертный отдел'],
            ['Отдел внешнеэкономической деятельности'],
            ['Отдел международной логистики и координации'],
            ['Аналитический отдел'],
            ['Отдел маркетинговых коммуникаций'],
            ['Спектрометрическое отделение'],
            ['Бухгалтерия Люмэкс-Маркетинг'],
            ['Дирекция (УК)'],
            ['Информационная служба до 07.11.17'],
            ['Кадровая Служба'],
            ['Метрологическая служба (УК)'],
            ['Служба главного инженера'],
            ['Служба Системы качества'],
            ['Экономический отдел'],
            ['Патентно-Лицензионное Бюро'],
        ];

        foreach ($departments as $_item) {
            $Item = new Department([
                'name'          => $_item[0],
                'full_name'     => $_item[0],
            ]);
            $Item->save();
        }

        //
        // Валюты
        //
        $currencies = [
            [840,  'USD', "US Dollar",      2, "U+0024"],
            [978,  'EUR', "Euro",           2, "U+20AC"],
            [643,  'RUB', "Russian Ruble",  2, "U+20BD"],
            [392,  'JPY', "Japanese yen",   0, "U+00A5"],
            [826,  'GBP', "Pound sterling", 2, "U+00A3"],
        ];

        foreach ($currencies as $_item) {
            $item = new Currency([
                'id'    => $_item[0],
                'code'  => $_item[1],
                'name'  => $_item[2],
                'units' => $_item[3],
                'sign'  => $_item[4],
            ]);
            $item->save();
        }

        $products = [
            ['000000087', 'Анализатор ртути РА-915+'],
            ['000000088', 'Анализатор ртути РА-915М'],
            ['000000117', 'БИК-анализатор ИнфраЛюм ФТ-100'],
            ['000000116', 'БИК-анализатор ИнфраЛюм ФТ-12'],
            ['000000026', 'БИК-анализаторы'],
            ['000000074', 'Виалы'],
            ['000000004', 'ВЧ-лампы'],
            ['000000036', 'Градуировка БИК -анализаторов'],
            ['000000077', 'ГСО'],
            ['000000029', 'Доп. аксессуары для БИК-анализаторов'],
            ['000000042', 'Доп. аксессуары для Фурье спектрометров ИнфраЛЮМ ФТ-02'],
            ['000000034', 'ИК Фурье спектрометры'],
            ['000000019', 'ИнфраЛЮМ ФТ - 02,10'],
            ['000000020', 'ИнфраЛЮМ ФТ - 40'],
            ['000000033', 'ИнфраЛЮМ ФТ 02,10,40'],
            ['000000095', 'Капель'],
            ['000000100', 'Капель, Люмахром'],
            ['000000104', 'Капель, Люмахром, Панорама'],
            ['000000109', 'Капель, Панорама'],
            ['000000099', 'Колонки'],
            ['000000101', 'КОФ'],
            ['000000105', 'Крепеж'],
            ['000000070', 'Кюветы'],
            ['000000098', 'Люмахром'],
            ['000000107', 'Люмахром, Панорама'],
            ['000000060', 'Люмахром-Флюорат'],
            ['000000015', 'МГА'],
            ['000000073', 'Микродозаторы'],
            ['000000016', 'Минотавр'],
            ['000000083', 'Монохроматор'],
            ['000000039', 'НИ и ОКР по нестандартным задачам для БИК-анализатора'],
            ['000000045', 'НИ и ОКР по нестандартным задачам для Фурье спектрометров ИнфраЛЮМ ФТ-02'],
            ['000000097', 'Панорама'],
            ['000000040', 'ПНР  Фурье спектрометров ИнфраЛЮМ ФТ-02'],
            ['000000048', 'ПНР БИК-анализаторов'],
            ['000000047', 'Поверка БИК-анализаторов'],
            ['000000038', 'Поверка Фурье спектрометров ИнфраЛЮМ ФТ-02'],
            ['000000075', 'Посуда'],
            ['000000041', 'Приставки для ИК-анализа'],
            ['000000089', 'Приставки к РА'],
            ['000000079', 'Программное обеспечение'],
            ['000000035', 'Программное обеспечение для БИК- анализаторов'],
            ['000000043', 'Программное обеспечение для Фурье спектрометров ИнфраЛЮМ ФТ-02'],
            ['000000032', 'Прочее'],
            ['000000090', 'Прочее ППО-5'],
            ['000000120', 'ПЦР'],
            ['000000082', 'РА-915'],
            ['000000081', 'Разное'],
            ['000000049', 'Разработки'],
            ['000000118', 'Разработки Экспериментального отдела'],
            ['000000080', 'Расходный материал'],
            ['000000091', 'Ртутный анализатор РА-915 МВ'],
            ['000000102', 'Светофильтры'],
            ['000000037', 'Сервисное обслуживание БИК- анализаторов'],
            ['000000044', 'Сервисное обслуживание Фурье спектрометров ИнфраЛЮМ ФТ-02'],
            ['000000046', 'Сложное оборудование для инфракрасного анализа'],
            ['000000078', 'СОП'],
            ['000000114', 'Термалюм'],
            ['000000096', 'Термион'],
            ['000000108', 'Товары для перепродажи'],
            ['000000076', 'Упаковочный материал'],
            ['000000072', 'Фильтры'],
            ['000000094', 'Флюорат'],
            ['000000084', 'Хим. реактивы и наборы'],
            ['000000106', 'Чужие'],
            ['000000071', 'Шприцы'],
        ];

        foreach ($products as $_item) {
            $item = new Product([
                'code_1c'   => $_item[0],
                'name'      => $_item[1],
            ]);
            $item->save();
        }
    }
}
