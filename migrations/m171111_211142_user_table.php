<?php

use app\models\User;
use yii\db\Migration;

/**
 * Class m171111_211142_users_table
 */
class m171111_211142_user_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        $this->createTable('user', [
            'id'                => $this->primaryKey()->unsigned(),
            'code_1c'           => $this->string(255)->unique(),
            'username'          => $this->string(255)->notNull()->unique(),
            'name'              => $this->string(255)->notNull(),
            'parent_name'       => $this->string(255),
            'password_hash'     => $this->string(255)->notNull()->defaultValue(''),
            'access_token'      => $this->string(255)->unique(),
            'auth_key'          => $this->string(255)->unique(),
            'locale'            => $this->string(5)->notNull()->defaultValue('ru-RU'),
            'status'            => $this->smallInteger()->notNull(),
            'created_at'        => $this->bigInteger(),
            'updated_at'        => $this->bigInteger(),
        ]);

        $this->createIndex('idx_username_passwordHash', 'user', ['username', 'password_hash']);
        $this->createIndex('idx_access_token', 'user', ['access_token']);

        $this->insert('user', [
            'username' => 'admin',
            'name' => 'Admin',
            'status' => User::STATUS_ACTIVE,
            'password_hash' => User::getSaltedPassword('admin'),
            'locale' => 'en-US',
        ]);

        $users = [
            ['Ражева Ольга Алексеевна', 'Ражева О.А.', 'Ражева Ольга Алексеевна', 'ГенБит'],
            ['Воропаев Д.Б.', 'Воропаев Д.Б.', 'Воропаев Д.Б.', 'Внешние участники внедрения 1С'],
            ['Чулюков Олег Геннадьевич', 'Чулюков О.Г. ', 'Чулюков Олег Геннадьевич', 'СО (ППО2+ППО5)'],
            ['Загрядский Игорь Леонидович', 'Загрядский И. Л.', 'Загрядский Игорь Леонидович', 'Люмэкс'],
            ['Васильев Николай Иванович', 'Васильев Н.И.', 'Васильев Николай Иванович', 'Люмэкс'],
            ['Лупанов Алексей Валентинович', 'Лупанов А. В.', 'Лупанов Алексей Валентинович', 'Люмэкс'],
            ['Тихомиров Алексей Валерьевич', 'Тихомиров А. В.', 'Тихомиров Алексей Валерьевич', 'Люмэкс'],
            ['Ефимова Мария Евгеньевна', 'Ефимова М.Е. ', 'Ефимова Мария Евгеньевна', 'Экономическая служба'],
            ['Ершова Ирина Александровна', 'Ершова И. А. ', 'Ершова Ирина Александровна', 'Продающая компания'],
            ['Французов Павел Александрович', 'Французов П.А.', 'Французов Павел Александрович', 'Удаленные офисы'],
            ['Хлудок Елена Юрьевна', 'Хлудок Е.Ю.', 'Хлудок Елена Юрьевна', 'ВЭД'],
            ['Антипова Лилия Викторовна', 'Антипова Л.В.', 'Антипова Лилия Викторовна', 'Экономическая служба'],
            ['Стурова Анна Николаевна', 'Стурова А. Н.', 'Стурова Анна Николаевна', 'Люмэкс'],
            ['Ашмарин Александр Сергеевич', 'Ашмарин А.С. ', 'Ашмарин Александр Сергеевич', 'ВЭД'],
            ['Чеглецова Елизавета Александровна', 'Чеглецова Е.А.', 'Чеглецова Елизавета Александровна', 'ВЭД'],
            ['Громова Галина Юрьевна', 'Громова Г.Ю. ', 'Громова Галина Юрьевна', 'ВЭД'],
            ['Клиндухова Тамара Кирилловна', 'Клиндухова Т.К.', 'Клиндухова Тамара Кирилловна', 'ВЭД'],
            ['Гордеева Елена Александровна', 'Гордеева Е.А.', 'Гордеева Елена Александровна', 'ВЭД'],
            ['Белов Сергей Николаевич', 'Белов С.Н. ', 'Белов Сергей Николаевич', 'ВЭД'],
            ['Машьянов Николай Романович', 'Машьянов Н.Р.', 'Машьянов Николай Романович', 'ВЭД'],
            ['Яшенкова Ирина Владимировна', 'Яшенкова И. В.  (Люмэкс)', 'Яшенкова Ирина Владимировна', 'Бухгалтерия'],
            ['Сбитнева Екатерина Михайловна', 'Сбитнева Е. М.', 'Сбитнева Екатерина Михайловна', 'ГенБит'],
            ['Воробьева Ирина Васильевна', 'Воробьева И. В.', 'Воробьева Ирина Васильевна', 'Люмэкс'],
            ['Гуровская Инна Олеговна', 'Гуровская И. О.', 'Гуровская Инна Олеговна', 'ССК'],
            ['Корроль Ольга Олеговна', 'Корроль О.О. ', 'Корроль Ольга Олеговна', 'ССК'],
            ['Ежова Наталья Алексеевна', 'Ежова Н.А. ', 'Ежова Наталья Алексеевна', 'Кадры'],
            ['Ильин Олег Андреевич', 'Ильин О.А. ', 'Ильин Олег Андреевич', 'СО (ППО2+ППО5)'],
            ['Оверковская Екатерина Николаевна', 'Оверковская Е.Н.', 'Оверковская Екатерина Николаевна', 'Атомприбор'],
            ['Карандеева Анна Михайловна', 'Карандеева А.М.', 'Карандеева Анна Михайловна', 'Продающая компания'],
            ['Логинова Наталья Сергеевна', ' Логинова Н. С.', 'Логинова Наталья Сергеевна', 'ССК'],
            ['Дудина Людмила Георгиевна', 'Дудина Л. Г. ', 'Дудина Людмила Георгиевна', 'Люмэкс'],
            ['Зайцева Ольга Анатольевна', 'Зайцева О.А. ', 'Зайцева Ольга Анатольевна', 'Экономическая служба'],
            ['Кисляк Олег Дмитриевич', 'Кисляк О. Д. ', 'Кисляк Олег Дмитриевич', 'Люмэкс'],
            ['Кайдак Ирина Александровна', 'Кайдак И. А.  (Люмэкс)', 'Кайдак Ирина Александровна', 'Бухгалтерия'],
            ['Смыкова Ирина Леонидовна', 'Смыкова И.Л. ', 'Смыкова Ирина Леонидовна', 'Продающая компания'],
            ['Побережник Наталья Николаевна', 'Побережник Н.Н.', 'Побережник Наталья Николаевна', 'Атомприбор'],
            ['Карнаухова Наталья Владимировна', 'Карнаухова Н.В.', 'Карнаухова Наталья Владимировна', 'Экономическая служба'],
            ['Морозова Ольга Олеговна', 'Морозова О.О.', 'Морозова Ольга Олеговна', 'Экономическая служба'],
            ['Бабкова Татьяна Борисовна', 'Бабкова Т.Б. ', 'Бабкова Татьяна Борисовна', 'Кадры'],
            ['Долбиева Евгения Гавриловна', 'Долбиева Е.Г ', 'Долбиева Евгения Гавриловна', ''],
            ['Андреева Любовь Сергеевна', 'Андреева Л. С.', 'Андреева Любовь Сергеевна', 'СО (ППО2+ППО5)'],
            ['Борцов Николай Николаевич', 'Борцов Н.Н.', 'Борцов Николай Николаевич', 'Служба главного инженера'],
            ['Кирсанов Илья Игоревич', 'Кирсанов И.И.', 'Кирсанов Илья Игоревич', 'Удаленные офисы'],
            ['Шванев Николай Геннадьевич', 'Шванев Н.Г.', 'Шванев Николай Геннадьевич', 'Атомприбор'],
            ['Кургузова Ольга Андреевна', 'Кургузова О.А.', 'Кургузова Ольга Андреевна', 'ОСМА (ППО-3)'],
            ['Александрова Ирина Геннадьевна', 'Александрова И.Г.', 'Александрова Ирина Геннадьевна', 'Продающая компания'],
            ['Степанова Лариса Евгеньевна', 'Степанова Л.Е.', 'Степанова Лариса Евгеньевна', 'СО (ППО2+ППО5)'],
            ['Кольцова Ольга Андреевна', 'Кольцова О.А.', 'Кольцова Ольга Андреевна', 'Продающая компания'],
            ['Серегин Алексей Юрьевич', 'Серегин А.Ю', 'Серегин Алексей Юрьевич', 'Удаленные офисы'],
            ['Приходько Александр Викторович', 'pav', 'Приходько Александр Викторович  ', 'Администраторы  '],
            ['Лейнвебер Наталья Ивановна', 'Лейнвебер Н. И.', 'Лейнвебер Наталья Ивановна', 'Удаленные офисы'],
            ['Крюкова Ирина Владимировна', 'Крюкова И В', 'Крюкова Ирина Владимировна', 'Люмэкс'],
            ['Падерова София Харисовна', 'Падерова С. Х.', 'Падерова София Харисовна', 'Люмэкс'],
            ['Делединская Галина Ивановна', 'Делединская Г. И.', 'Делединская Галина Ивановна', 'Люмэкс'],
            ['Гафиятуллина Диана Ильдаровна', 'Гафиятуллина Д. И.', 'Гафиятуллина Диана Ильдаровна', 'Экономическая служба'],
            ['Макеева Лидия Ивановна', 'Макеева Л. И. (главный бухгалтер)', 'Макеева Лидия Ивановна', 'Атомприбор'],
            ['Трофименко Кирилл Александрович', 'Трофименко К.А.', 'Трофименко Кирилл Александрович', 'ОСМА (ППО-3)'],
            ['Левин Леонид Борисович', 'Левин Л.Б. ', 'Левин Леонид Борисович', 'ОСМА (ППО-3)'],
            ['Шклярик Владимир Георгиевич', 'Шклярик В.Г. ', 'Шклярик Владимир Георгиевич', 'ОСМА (ППО-3)'],
            ['Батура Светлана Анатольевна', 'Батура С.А.', 'Батура Светлана Анатольевна', 'ОСМА (ППО-3)'],
            ['Лебедева Елена Степановна', 'Лебедева Е.С.', 'Лебедева Елена Степановна', 'ОСМА (ППО-3)'],
            ['Ильюхин Виктор Иванович', 'Ильюхин В.И. ', 'Ильюхин Виктор Иванович', 'ОСМА (ППО-3)'],
            ['Парамонова Елена Викторовна', 'Парамонова Е.В.', 'Парамонова Елена Викторовна', 'ОСМА (ППО-3)'],
            ['Ивановский Андрей Валерьевич', 'Ивановский А.В.', 'Ивановский Андрей Валерьевич', 'ОСМА (ППО-3)'],
            ['Иванов Николай Сергеевич', 'Иванов Н.C.', 'Иванов Николай Сергеевич', 'Удаленные офисы'],
            ['Аракчеева Татьяна Олеговна', 'Аракчеева Т.О.', 'Аракчеева Татьяна Олеговна', 'СО (ППО2+ППО5)'],
            ['Толстых Ольга Николаевна', 'Толстых О.Н. ', 'Толстых Ольга Николаевна', 'ППО-2'],
            ['Агапов Дмитрий Геннадьевич', 'Агапов Д.Г. (менеджер склада)', 'Агапов Дмитрий Геннадьевич', 'ОСМА (ППО-3)'],
            ['Маляренко Марина Львовна', 'Маляренко М.Л.', 'Маляренко Марина Львовна', 'Люмэкс'],
            ['Адамсон Вера Георгиевна', 'Адамсон В.Г. ', 'Адамсон Вера Георгиевна', 'Продающая компания'],
            ['Санталова Ольга Владимировна', 'Санталова О.В.', 'Санталова Ольга Владимировна', 'СО (ППО2+ППО5)'],
            ['Морозова Мария Борисовна', 'Морозова М.Б.', 'Морозова Мария Борисовна', 'Экономическая служба  '],
            ['Евсеев Олег Владимирович', 'Евсеев О.В. (директор)', 'Евсеев Олег Владимирович', 'Атомприбор'],
            ['Алисьвяк Евгения Владимировна', 'Алисьвяк Е.В. (экономист)', 'Алисьвяк Евгения Владимировна', 'Экономическая служба'],
            ['Артемьева Е.С. (Руководитель экономической службы)', 'Артемьева Е.С. (руководитель экономической службы)', 'Артемьева Е.С. (Руководитель экономической службы)', 'Экономическая служба'],
            ['Горяченков Алексей Анатольевич', 'Горяченков А.А. (программист)', 'Горяченков Алексей Анатольевич', 'Администраторы  '],
            ['Власенко Елена Николаевна', 'Власенко Е.Н.', 'Власенко Елена Николаевна', 'Бухгалтерия "Люмэкс-маркетинг"'],
            ['Бегункова Зинаида Андреевна', 'Бегункова З.А.', 'Бегункова Зинаида Андреевна', 'Бухгалтерия "Люмэкс-маркетинг"'],
            ['Ащеулова Надежда Анатольевна', 'Ащеулова Н.А. (руководитель ССК)', 'Ащеулова Надежда Анатольевна', 'ССК'],
            ['Громова Елена Анатольевна', 'Громова Е.А. (зам. руководителя по экономике)', 'Громова Елена Анатольевна', 'ОСМА (ППО-3)'],
            ['Сороцкая Светлана Викторовна', 'Сороцкая С.В. (менеджер по комплектации)', 'Сороцкая Светлана Викторовна', 'Атомприбор'],
            ['Ефимова О.В. (Экономист-Производственные затраты)', 'Ефимова О.В. (экономист-производственные затраты) ', 'Ефимова О.В. (Экономист-Производственные затраты)', 'Экономическая служба'],
            ['Боганова Ксения Александровна', 'Боганова К.А.', 'Боганова Ксения Александровна', 'Экономическая служба  '],
            ['Ганя Дмитрий Васильевич', 'Ганя Д.В.  ', 'Ганя Дмитрий Васильевич', 'СО (ППО2+ППО5)'],
            ['Афанасьев Вячеслав Николаевич', 'Афанасьев В.Н.', 'Афанасьев Вячеслав Николаевич', 'СО (ППО2+ППО5)'],
            ['Бандурченко Вероника Владиславовна', 'Бандурченко В.В.', 'Бандурченко Вероника Владиславовна', 'СО (ППО2+ППО5)'],
            ['Иванова Мария Михайловна', 'Иванова М.М. ', 'Иванова Мария Михайловна', 'СО (ППО2+ППО5)'],
            ['Климова Виктория Сергеевна', 'Климова В.С. ', 'Климова Виктория Сергеевна', 'Экономическая служба  '],
            ['Цветкова Елена Викторовна', 'Цветкова Е.В.', 'Цветкова Елена Викторовна', 'Экономическая служба  '],
            ['Иванова Людмила Николаевна', 'Иванова Л.Н. ', 'Иванова Людмила Николаевна', 'Экономическая служба  '],
            ['Васильева Любовь Сергеевна', 'Васильева Л.С.', 'Васильева Любовь Сергеевна', 'СО (ППО2+ППО5)'],
            ['Килина Мария Валентиновна', 'Килина М.В.', 'Килина Мария Валентиновна', 'Экономическая служба  '],
            ['Серова Елена Викторовна', 'Серова Е.В.', 'Серова Елена Викторовна', 'Продающая компания'],
            ['Бичулова Людмила Александровна', 'Бичулова Л.А.', 'Бичулова Людмила Александровна', 'Продающая компания'],
            ['Осетрова Юлия Николаевна', 'Осетрова Ю.Н.', 'Осетрова Юлия Николаевна', 'СО (ППО2+ППО5)'],
            ['Менделеева Маргарита Игоревна', 'Менделеева М.И.', 'Менделеева Маргарита Игоревна', 'Продающая компания'],
            ['Жогов Константин Владимирович', 'Жогов К.В. ', 'Жогов Константин Владимирович', 'Атомприбор'],
            ['Ерошков Сергей Николаевич', 'Ерошков С.Н. ', 'Ерошков Сергей Николаевич', 'СО (ППО2+ППО5)'],
            ['Шолупов Сергей Евгеньевич', 'Шолупов С.Е. ', 'Шолупов Сергей Евгеньевич', 'СО (ППО2+ППО5)'],
            ['Травина Надежда Владимировна', 'Травина Н.В. ', 'Травина Надежда Владимировна', 'СО (ППО2+ППО5)'],
            ['Лавренова Ольга Сергеевна', 'Лавренова О.С.', 'Лавренова Ольга Сергеевна', 'ОСМА (ППО-3)'],
            ['Ритынь Екатерина Николаевна', 'Ритынь Е.Н.', 'Ритынь Екатерина Николаевна', 'ОСМА (ППО-3)'],
            ['Недосвитная Людмила Сергеевна', 'Недосвитная Л.С.', 'Недосвитная Людмила Сергеевна', 'Продающая компания'],
            ['Горяченкова Ирина Борисовна', 'Горяченкова И.Б.', 'Горяченкова Ирина Борисовна', 'Информационная служба'],
            ['Карамова Эльмира Феруллаевна ', 'Карамова Э.Ф.', 'Карамова Эльмира Феруллаевна ', 'Продающая компания'],
            ['Соловьева Светлана Роальдовна', 'Соловьева С.Р.', 'Соловьева Светлана Роальдовна', 'Экономическая служба  '],
            ['Стеклянникова Ирина Сергеевна', 'Стеклянникова И.С. (экономист)', 'Стеклянникова Ирина Сергеевна', 'Экономическая служба  '],
            ['Климова Ирина Олеговна', 'Климова И.О. ', 'Климова Ирина Олеговна', 'Продающая компания'],
            ['Шишова Марина Владимировна', 'Шишова М.В.', 'Шишова Марина Владимировна', 'СО (ППО2+ППО5)'],
            ['Киселева Елена Алексеевна', 'Киселева Е. А.', 'Киселева Елена Алексеевна', 'Удаленные офисы'],
            ['Старовойтова Татьяна Андреевна', 'Старовойтова Т.А.', 'Старовойтова Татьяна Андреевна', 'Продающая компания'],
            ['Колесниченко Добрыня Викторович', 'Колесниченко Д.В.', 'Колесниченко Добрыня Викторович', 'ОСМА (ППО-3)'],
            ['Кузнецова Татьяна Константиновна', 'Кузнецова Т.К.', 'Кузнецова Татьяна Константиновна', 'Продающая компания'],
            ['Иванова Марина Александровна', 'Иванова М.А. ', 'Иванова Марина Александровна', 'Экономическая служба  '],
            ['Гладилович Дмитрий Борисович', 'Гладилович Д.Б. (главный метролог)', 'Гладилович Дмитрий Борисович', 'МС'],
            ['Ажоткина Нина Николаевна', 'Ажоткина Н.Н.', 'Ажоткина Нина Николаевна', 'Экономическая служба  '],
            ['Великанов Александр Владимирович', 'Великанов А.В.', 'Великанов Александр Владимирович', 'Продающая компания'],
            ['Сляднев Максим Николаевич', 'Сляднев М.Н. (Руководитель МБО)', 'Сляднев Максим Николаевич', 'ГенБит'],
            ['Глыбина Маргарита Игоревна', 'Глыбина М.И. (экономист - управление ДС)          ', 'Глыбина Маргарита Игоревна', 'Экономическая служба  '],
            ['Зинченко Мария Михайловна', 'Зинченко М.М. (администратор)', 'Зинченко Мария Михайловна', 'Администраторы  '],
            ['Кравченко Татьяна Юрьевна', 'Кравченко Т.Ю. (Экономист  по МСФО)', 'Кравченко Татьяна Юрьевна', 'Экономическая служба  '],
            ['Камышева Наталья Владимировна', 'Камышева Н.В.', 'Камышева Наталья Владимировна', 'СО (ППО2+ППО5)'],
            ['Тяжко Константин Васильевич', 'Тяжко К.В. ', 'Тяжко Константин Васильевич', 'СО (ППО2+ППО5)'],
            ['Трофименко Александр Михайлович', 'Трофименко А.М.', 'Трофименко Александр Михайлович', 'ОСМА (ППО-3)'],
            ['Лудищева Жанна Геннадьевна', 'Лудищева Ж.Г.', 'Лудищева Жанна Геннадьевна', 'ОСМА (ППО-3)'],
            ['Косорукова Маргарита Николаевна', 'Косорукова М.Н.', 'Косорукова Маргарита Николаевна', 'Продающая компания'],
            ['Татарева Анна Михайловна', 'Татарева А.М.', 'Татарева Анна Михайловна', 'Продающая компания'],
            ['Ивакина Юлия Леонидовна', 'Ивакина Ю.Л.', 'Ивакина Юлия Леонидовна', 'Продающая компания'],
            ['Пак Мария Владимировна', 'Пак М.В.', 'Пак Мария Владимировна', 'Продающая компания'],
            ['Магаршак Михаил Александрович', 'Магаршак М.А.', 'Магаршак Михаил Александрович', 'СО (ППО2+ППО5)'],
            ['Филонов Дмитрий Олегович', 'Филонов Д.О.', 'Филонов Дмитрий Олегович', 'МБО'],
        ];

        foreach ($users as $user) {
            $this->insert('user', [
                'code_1c' => trim($user[1]),
                'name' => trim($user[0]),
                'parent_name' => trim($user[3]),
                'username' => User::getUsername($user[1]),
                'status' => User::STATUS_ACTIVE,
                'password_hash' => User::getSaltedPassword('password'),
            ]);
        }
    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        $this->dropTable('user');
    }
}
