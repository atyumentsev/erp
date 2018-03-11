<?php
namespace app\commands;

use app\components\CommandsController;
use app\models\CounterAgent;
use yii\helpers\Console;

/**
 * Инициализация справочников
 */
class CounterAgentsController extends CommandsController
{
    public function actionUploadText($file = '@app/commands/data/counteragents.txt')
    {
        $file = \Yii::getAlias($file);
        if (!file_exists($file)) {
            $this->stderr("Error: file does not exist: $file", Console::FG_RED);
        }
        $rows = file($file);
        $map = [
            0 => 'name',
            1 => 'inn',
            2 => 'kpp',
            3 => 'code_1c',
            5 => 'ib_code',
            8 => 'type',
            9 => 'full_name',
        ];

        $created_counteragents_counter = 0;
        $updated_counteragents_counter = 0;


        foreach ($rows as $row) {
            $raw_data = explode("\t", $row);
            $data = [];
            foreach ($raw_data as $key => $value) {
                if (isset($map[$key])) {
                    $data[$map[$key]] = $value;
                }
            }
            if (empty($data['code_1c'])) {
                print_r($row);
                return;
            }
            $counteragent = CounterAgent::findOne(['code_1c' => $data['code_1c']]);
            if ($counteragent === null) {
                $created_counteragents_counter++;
                $counteragent = new CounterAgent($data);
            } else {
                $updated_counteragents_counter++;
                $counteragent->setAttributes($data);
            }
            $counteragent->save();
        }

        $this->stdout(
            "Counteragents successfully uploaded. Created: {$created_counteragents_counter}, updated: {$updated_counteragents_counter}" . PHP_EOL,
            Console::FG_GREEN
        );
    }
}
