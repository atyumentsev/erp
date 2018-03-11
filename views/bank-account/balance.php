<?php

use app\models\BankAccount;
use app\models\BankAccountBalance;
use app\models\Bank;
use yii\bootstrap\Html;
use kartik\date\DatePicker;

/**
 * @var yii\web\View $this
 * @var yii\data\ActiveDataProvider $dataProvider
 * @var bool $i_can_edit
 * @var bool $i_can_pay
 * @var Bank[] $banks
 * @var array $accounts_by_bank
 * @var BankAccountBalance $balances
 * @var array $errors
 * @var string $date
 */

$this->title = Yii::t('app', 'Accounts Balance');
$this->params['breadcrumbs'][] = $this->title;
?>
<style>
    #balance-table td:nth-child(3)
    {
        text-align: center;
    }
    #balance-table td:nth-child(4),
    #balance-table td:nth-child(5),
    #balance-table td:nth-child(6),
    #balance-table td:nth-child(7)
    {
        text-align: right;
    }
    #balance-search-form label {
        margin-top:5px;
        margin-left: 20px;
    }
    .custom-button {
        border:1px solid dimgrey;
        border-radius: 3px;
        padding:2px;
    }
</style>
<form method="get" action="/bank-account/balance" id="balance-search-form">
<div class="row">
    <div class="col-sm-1">
        <label for="date"><?= \Yii::t('app', 'Date:')?></label>
    </div>
    <div class="col-sm-2">
        <?= DatePicker::widget([
            'name' => 'date',
            'type' => DatePicker::TYPE_INPUT,
            'value' => $date,
            'pluginOptions' => [
                'autoclose' => true,
                'format' => 'dd.mm.yyyy'
            ]
        ]);
        ?>
    </div>
    <?= Html::submitButton("Show") ?>
</div>
</form>
<br>
<div class="bank-account-balance">
    <table class="table table-bordered" id="balance-table">
        <thead>
        <tr>
            <th><?= \Yii::t('app', 'Name')?></th>
            <th><?= \Yii::t('app', 'Account Number')?></th>
            <th><?= \Yii::t('app', 'Currency')?></th>
            <th><?= \Yii::t('app', 'Balance')?></th>
            <th><?= \Yii::t('app', 'Paid')?></th>
            <th><?= \Yii::t('app', 'Reserved')?></th>
            <th><?= \Yii::t('app', 'Rest')?></th>
            <th></th>
        </tr>
        </thead>
        <tbody>
        <?php foreach ($banks as $bank) : ?>
        <tr>
            <td colspan="8"><b><?= $bank->name; ?></b></td>
        </tr>
            <?php
            /** @var BankAccount $account */
            foreach ($accounts_by_bank[$bank->id] as $account) :
                if (isset($balances[$account->id])) {
                    /** @var \app\models\Currency $currency */
                    $currency = $balances[$account->id]->bankAccount->currency;
                    $paid = $balances[$account->id]->paid;
                    $balance = $balances[$account->id]->balance;
                    $reserved = 0;
                    $rest = $balance - $paid - $reserved;
                    $balance_val = $currency->getFormattedAmount($balance);
                    $paid_val = $currency->getFormattedAmount($paid);
                    $reserved_val = $currency->getFormattedAmount($reserved);
                    $rest_val = $currency->getFormattedAmount($rest);
                } else {
                    $balance_val = '';
                    $paid_val = '';
                    $reserved_val = '';
                    $rest_val = '';
                }
            ?>
            <tr>
                <td><?= $account->name; ?></td>
                <td><?= $account->account_number; ?></td>
                <td><?= $account->currency->code; ?></td>
                <td><?= $balance_val ?></td>
                <td><?= $paid_val ?></td>
                <td><?= $reserved_val ?></td>
                <td><?= $rest_val ?></td>
                <td>
                    <?php
                    $buttons = [];
                    if ($i_can_pay) {
                        $buttons[] = Html::a(
                            \Yii::t('app', 'Pay'),
                            ['/payment-requests-selection', 'bank_account_id' => $account->id]
                        );
                    }
                    if ($i_can_edit) {
                        $buttons[] = Html::a(
                            \Yii::t('app', 'Set Balance'),
                            ['/bank-account/set-balance', 'bank_account_id' => $account->id, 'date' => $date]
                        );
                    }
                    echo join (' | ', $buttons);
                    ?>
                </td>
            </tr>
            <?php endforeach; ?>
        <?php endforeach; ?>
        </tbody>
    </table>
</div>
