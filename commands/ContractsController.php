<?php
namespace app\commands;

use app\components\CommandsController;
use app\models\Affiliate;
use app\models\Contract;
use app\models\CounterAgent;
use yii\helpers\Console;

/**
 * Инициализация справочников
 */
class ContractsController extends CommandsController
{
    public function actionUploadText($file = '@app/commands/data/contracts.txt')
    {
        $file = \Yii::getAlias($file);
        if (!file_exists($file)) {
            $this->stderr("Error: file does not exist: $file", Console::FG_RED);
        }
        $rows = file($file);
        $map = [
            0 => 'code_1c',
            1 => 'name',
            2 => 'currency',
            3 => 'counteragent',
            4 => 'affiliate',
            5 => 'type',
            6 => 'number',
            7 => 'signed_at',
        ];

        $currency_map = [
            'руб.' => 643,
            'USD' => 840,
            'EUR' => 978,
            'CAD' => 124,
            'CNY' => 156,
        ];
        $affiliate_map = array_column(Affiliate::find()->select('id, name')->asArray()->all(),  'id', 'name');
        $counteragents_map = array_column(CounterAgent::find()->select('id, name')->asArray()->all(),  'id', 'name');

        $created_contracts_counter = 0;
        $updated_contracts_counter = 0;
        $not_loaded_contracts_counter = 0;

        foreach ($rows as $i => $row) {
            $raw_data = explode("\t", $row);
            $data = [];
            foreach ($raw_data as $key => $value) {
                if (isset($map[$key])) {
                    $data[$map[$key]] = $value;
                }
            }
            if (empty($data['code_1c'])) {
                $this->stderr("Empty code_1c for line #{$i}");
                $not_loaded_contracts_counter++;
                continue;
            }
            if (empty($currency_map[$data['currency']])) {
                $this->stderr("Unknown currency '{$data['currency']}' for line #{$i}" . PHP_EOL, Console::FG_RED);
                $not_loaded_contracts_counter++;
                continue;
            }
            if (empty($affiliate_map[$data['affiliate']])) {
                $this->stderr("Unknown affiliate '{$data['affiliate']}' for line #{$i}" . PHP_EOL, Console::FG_RED);
                $not_loaded_contracts_counter++;
                continue;
            }
            if (empty($counteragents_map[$data['counteragent']])) {
                $this->stderr("Unknown counteragent '{$data['counteragent']}' for line #{$i}" . PHP_EOL, Console::FG_RED);
                $not_loaded_contracts_counter++;
                continue;
            }

            $data['currency_id'] = $currency_map[$data['currency']];
            $data['counteragent_id'] = $counteragents_map[$data['counteragent']];
            $data['affiliate_id'] = $affiliate_map[$data['affiliate']];
            if (isset($data['signed_at'])) {
                $data['signed_at'] = date('Y-m-d', strtotime($data['signed_at']));
            }

            unset($data['currency'], $data['counteragent'], $data['affiliate']);

            $contract = Contract::findOne(['code_1c' => $data['code_1c']]);
            if ($contract === null) {
                $created_contracts_counter++;
                $contract = new Contract($data);
            } else {
                $updated_contracts_counter++;
                $contract->setAttributes($data);
            }
            if (!$contract->save()) {
                print_r($contract->getErrors());
                return;
            }
        }

        $this->stdout(
            "Contracts successfully uploaded." . PHP_EOL .
            "Created: {$created_contracts_counter}, updated: {$updated_contracts_counter}, errors: {$not_loaded_contracts_counter}" . PHP_EOL,
            Console::FG_GREEN
        );
    }
}
