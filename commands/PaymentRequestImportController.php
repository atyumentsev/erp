<?php
namespace app\commands;

use app\components\CommandsController;
use app\models\Affiliate;
use app\models\CashflowItem;
use app\models\CounterAgent;
use app\models\Currency;
use app\models\Department;
use app\models\PaymentRequest;
use app\models\Product;
use app\models\Urgency;
use app\models\User;
use yii\helpers\Console;

/**
 * Загрузка заявок на расходование средств из 1С
 */
class PaymentRequestImportController extends CommandsController
{
    public function actionUploadXml($file = '@app/commands/data/payment_requests.xml')
    {
        $file = \Yii::getAlias($file);
        if (!file_exists($file)) {
            $this->stderr("Error: file does not exist: $file", Console::FG_RED);
        }

        $xml = simplexml_load_file($file);

        $pr_list = PaymentRequest::find()
            ->where('uuid IS NOT NULL')
            ->indexBy('uuid')
            ->all();

        $ca_hash = array_column(
            CounterAgent::find()->select('id, name')->asArray()->all(),
            'id',
            'name'
        );

        $affiliate_hash = array_column(
            Affiliate::find()->select('id, name')->asArray()->all(),
            'id',
            'name'
        );

        $user_hash = array_column(
            User::find()->select('id, name')->asArray()->all(),
            'id',
            'name'
        );

        $cfi_hash = array_column(
            CashflowItem::find()->select('id, name')->asArray()->all(),
            'id',
            'name'
        );
        $cfi_hash['Телефония и Интернет'] = $cfi_hash['Телефония и Интернет (администрация)'];
        $cfi_hash['Аттестация, сертификация и метрологические услуги (продажи)'] = null;

        $product_hash = array_column(
            Product::find()->select('id, name')->asArray()->all(),
            'id',
            'name'
        );

        $urgency_hash = [
            'Очень срочно' => Urgency::VERY_URGENT,
            'Срочно' => Urgency::URGENT,
            'Средняя' => Urgency::MEDIUM,
            'Низкая' => Urgency::LOW,
        ];

        $department_list = Department::find()
            ->select('id, name')
            ->asArray()
            ->all();

        $depts_hash = array_column($department_list, 'id', 'name');
        $depts_hash['Производство (ОСМА (ППО-3))'] = $depts_hash['Отделение сепарационных методов анализа'];
        $depts_hash['Производственный отдел (Л)'] = $depts_hash['Производственный отдел'];

        $currency_hash = [
            'руб.' => Currency::CODE_RUB,
            'USD' => Currency::CODE_USD,
        ];

        foreach ($xml->children() as $row) {
            $uuid = (string)$row->УИД;

            if (isset ($pr_list[$uuid])) {
                $pr = $pr_list[$uuid];
            } else {
                $pr = new PaymentRequest([
                    'uuid' => $uuid,
                ]);
            }

            $original_currency_id = $currency_hash[(string)$row->Валюта] ?: null;
            if ($original_currency_id === null) {
                $this->stderr("Currency {$row->Валюта} is not found" . PHP_EOL, Console::FG_RED);
            }

            $ca_name = (string)$row->Получатель;
            $ca_id = $ca_hash[$ca_name] ?? null;
            if ($ca_id == null) {
                $this->stdout("CounterAgent {$ca_name} does not exist" . PHP_EOL);
                $counterAgent = new CounterAgent(['name' => $ca_name]);
                $counterAgent->save();
                $ca_id = $counterAgent->id;
                $ca_hash[$ca_name] = $ca_id;
            }

            $user_name = trim((string)$row->Ответственный);
            $user_id = $user_hash[$user_name] ?? null;
            if ($user_id == null) {
                $this->stdout("User {$user_name} does not exist" . PHP_EOL);
                $short_name = User::getShortName($user_name);
                $user = new User([
                    'code_1c' => $user_name,
                    'name' => $user_name,
                    'parent_name' => (string)$row->Подразделение,
                    'short_name' => $short_name,
                    'username' => User::getUsername($short_name),
                    'status' => User::STATUS_ACTIVE,
                    'password_hash' => User::getSaltedPassword('password'),
                ]);
                $user->save();
                $user_hash[$user->name] = $user->id;
            }

            $approver_name = trim((string)$row->ПоследнийСогласующий);
            if (empty($user_hash[$approver_name])) {
                $this->stdout("User {$approver_name} does not exist" . PHP_EOL);
                $short_name = User::getShortName($approver_name);
                $user = new User([
                    'code_1c' => $approver_name,
                    'name' => $approver_name,
                    'parent_name' => (string)$row->Этап,
                    'short_name' => $short_name,
                    'username' => User::getUsername($short_name),
                    'status' => User::STATUS_ACTIVE,
                    'password_hash' => User::getSaltedPassword('password'),
                ]);
                $user->save();
                $user_hash[$user->name] = $user->id;
            }

            $product_name = trim((string)$row->Номенклатура);
            if (!empty($product_name)) {
                if (!isset($product_hash[$product_name])) {
                    $product = new Product([
                        'code_1c' => $product_name,
                        'name' => $product_name,
                    ]);
                    $product->save();
                    $product_hash[$product_name] = $product->id;
                }
            }

            $pr->status = PaymentRequest::STATUS_APPROVED;

            // <Дата>27.11.2017 19:53:17</Дата>
            $pr->due_date = date('Y-m-d', strtotime((string)$row->Дата));
            // <Номер>ПЩК00000117</Номер>
            $pr->internal_number = (string)$row->Номер;
            // <ДатаРасхода>07.11.2017 0:00:00</ДатаРасхода>
            $pr->expense_date = date('Y-m-d', strtotime((string)$row->ДатаРасхода));
            // <СуммаДокумента>16 590</СуммаДокумента>
            $pr->original_price = $this->convert2number($row->СуммаДокумента) * 100;
            // <Валюта>руб.</Валюта>
            $pr->original_currency_id = $original_currency_id;
            // <Получатель>ИнетКомп СПб ООО</Получатель>
            $pr->counteragent_id = $ca_id;
            // <Заявка>Заявка на расход денежных средств ПЩК00000117 от 27.11.2017 19:53:17</Заявка>
            //<Состояние>Подготовлен</Состояние>
            $pr->status_1c = (string)$row->Состояние;
            // <Организация>Продающая компания</Организация>
            $pr->invoice_recepient_affiliate_id = $affiliate_hash[(string)$row->Организация];
            //  <Подразделение>Отдел маркетинговых коммуникаций</Подразделение>
            $pr->customer_department_id = $depts_hash[(string)$row->Подразделение];
            $pr->executor_department_id = $depts_hash[(string)$row->Подразделение];
            // <Ответственный>Романова Ольга Геннадьевна</Ответственный>
            $pr->author_id = $user_hash[$user_name];
            //@todo <Этап>ЭО</Этап>
            // <ПоследнийСогласующий>Романова Ольга Геннадьевна</ПоследнийСогласующий>
            $pr->last_approver_id = $user_hash[$approver_name];
            //@todo <ФормаОплаты>Безналичные</ФормаОплаты>
            //<Описание>69 Барабан Xerox 013R00662</Описание>
            $pr->description = (string)$row->Описание;
            //@todo <БанковскийСчетКасса/>
            //@todo <СостояниеОплаты>Не оплачено</СостояниеОплаты>
            // <СтатьяДвиженияДенежныхСредств>Закупки канцелярии и расходных материалов к оргтехнике</СтатьяДвиженияДенежныхСредств>
            $pr->cashflow_item_id = $cfi_hash[(string)$row->СтатьяДвиженияДенежныхСредств];
            // <Срочность>Средняя</Срочность>
            $pr->urgency = $urgency_hash[(string)$row->Срочность];
            // <КДоплате>16 590</КДоплате>
            $pr->required_payment = $this->convert2number($row->КДоплате) * 100;
            // <Комментарий/>
            $pr->note = (string)$row->Комментарий;
            // <Номенклатура/>
            if (!empty($product_name)) {
                $pr->product_id = $product_hash[$product_name];
            }
            //@todo <Сделка/>
            // <КДоплатеУпр>16 590</КДоплатеУпр>
            $pr->required_payment_rub = $this->convert2number($row->КДоплатеУпр) * 100;
            $pr->price_rub = ($pr->required_payment_rub / $pr->required_payment) * $pr->original_price;
            $pr->payment_part = $pr->required_payment / $pr->original_price * 100;
            //<СчетПоставщика>669 от 07.09.2017</СчетПоставщика>
            $invoice_name = (string)$row->СчетПоставщика;
            $invoice_name_arr = explode(' от ', $invoice_name);
            if (isset($invoice_name_arr[0])) {
                $pr->invoice_number = $invoice_name_arr[0];
            }
            if (isset($invoice_name_arr[1])) {
                $pr->invoice_date = date('Y-m-d', strtotime($invoice_name_arr[1]));
            }
            //<Договор>669 от 07.09.17</Договор>
            $contract_name = (string)$row->Договор;
            $contract_name_arr = explode(' от ', $contract_name);
            if (isset($contract_name_arr[0])) {
                $pr->contract_number = $contract_name_arr[0];
            }
            if (isset($contract_name_arr[1])) {
                $pr->contract_date = date('Y-m-d', strtotime($contract_name_arr[1]));
            }
            // <УИД>7704aad7-d393-11e7-80dd-00155d78c209</УИД>
            $pr->uuid = $uuid;

            $pr->code_1c = $uuid;

            try {
                $res = $pr->save();
                if (!$res) {
                    print_r($pr->errors);
                }
            } catch(\Exception $e) {
                echo $e->getMessage();
            }
        }
    }

    private function convert2number(string $number)
    {
        if (strlen($number) < 2) {
            return null;
        }
        $number = str_replace(',', '.', $number);
        $ret_val = (float)preg_replace('/[^\d\.]/', '', $number);
        if ($number[0] == '(') {
            $ret_val *= -1;
        }
        return $ret_val;
    }
}
