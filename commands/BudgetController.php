<?php
namespace app\commands;

use app\components\CommandsController;
use app\models\Budget;
use app\models\BudgetCFR;
use app\models\CashflowItem;
use yii\helpers\Console;

/**
 * Загрузка бюджета
 */
class BudgetController extends CommandsController
{
    public function actionUpload2017Text($file = '@app/commands/data/budget.txt')
    {
        $file = \Yii::getAlias($file);
        if (!file_exists($file)) {
            $this->stderr("Error: file does not exist: $file", Console::FG_RED);
        }

        $rows = file($file);
        foreach ($rows as $i => $row) {
            $row = trim($row);
            $rows[$i] = explode("\t", $row);
            $rows[$i] = array_map('trim', $rows[$i]);
        }

        $cashflowItems = CashflowItem::find()->select('id, name')->all();
        $cashflowItems = array_column($cashflowItems, 'id', 'name');

        $cfrItems = BudgetCFR::find()->select('id, name')->all();
        $cfrItems = array_column($cfrItems, 'id', 'name');

        $cfr_items = $rows[0];
        foreach ($cfr_items as $i => $item) {
            if (empty($item)) {
                unset($cfr_items[$i]);
            }
        }

        for ($i = 4; $i < count($rows); $i++) {
            $row = $rows[$i];
            if (!isset($cashflowItems[$row[0]])) {
                $this->stdout("Cashflow Item '{$row[0]}' wasn't found in corresponding table" . PHP_EOL, Console::FG_RED);
                continue;
            }
            $budget = [
                'cashflow_item_id' => $cashflowItems[$row[0]],
            ];
            for ($j = 1; $j < count($row); $j += 5) {
                $budget['budget_cfr_id'] = $cfrItems[$cfr_items[$j]];
                for ($k = 1; $k < 5; $k ++) {
                    $budget['budget_period_id'] = $k; // 2017q1 = 1, 2017q2 = 2,...
                    $budget['amount'] = $this->convert2number($row[$j + $k]) * 100;
                    if (!empty($budget['amount'])) {
                        (new Budget($budget))->save();
                    }
                }
            }
            $this->stdout("Cashflow Item '{$row[0]}' was successfully loaded" . PHP_EOL, Console::FG_GREEN);
        }

        $this->stdout(
            "Budget successfully uploaded." . PHP_EOL,
            Console::FG_GREEN
        );
    }

    private function convert2number(string $number)
    {
        if (strlen($number) < 2) {
            return null;
        }
        $ret_val = (int)preg_replace('/[^\d]/', '', $number);
        if ($number[0] == '(') {
            $ret_val *= -1;
        }
        return $ret_val;
    }
}
