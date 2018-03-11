<?php
/**
 * @var $this yii\web\View
 * @var PaymentRequest $invoice
 * @var array $budget_data
 * @var \app\models\Currency $rubCurrency
 * @var \app\models\Currency $originalCurrency
 * @var bool $i_can_update
 * @var bool $i_can_cancel
 * @var bool $i_can_approve
 * @var bool $i_can_create
 */
use app\components\widgets\PaymentRequestStatus;
use app\models\PaymentRequest;
use yii\widgets\DetailView;
use yii\helpers\Url;
use yii\bootstrap\Html;

$this->params['breadcrumbs'][] = [
    'label' => \Yii::t('app', 'Payment Requests'),
    'url' => ['/payment-requests/index'],
];

$this->title = \Yii::t('app', 'Payment Request #{internal_number}', ['internal_number' => $invoice->internal_number]);
$this->params['breadcrumbs'][] = $this->title;

$this->title .= ' ' .PaymentRequestStatus::widget(['paymentRequest' => $invoice]);
?>

<style>
    #invoice div {
        margin-bottom:10px;
    }
    #invoice-search-form div label{
        margin-top:5px;
    }

    div.invoice {
        margin-bottom: 25px;
    }

    #budget-table th,
    #budget-table td:nth-child(1),
    #budget-table td:nth-child(2) {
        text-align: center;
    }

    #budget-table td:nth-child(3),
    #budget-table td:nth-child(4),
    #budget-table td:nth-child(5)
    {
        text-align: right;
    }
</style>

<div class="invoice row">
    <div class="col-md-6">
        <h3><?= \Yii::t('app', 'Payment Destination') ?></h3>
    <?php
    echo DetailView::widget([
        'model' => $invoice,
        'options' => [
            'class' => 'table table-striped table-bordered detail-view',
        ],
        'attributes' => [
            'customerDepartmentShortName',
            'executorDepartmentShortName',
            'authorShortName',
            'productName',
            'cashflowItemName',
            'description',
            'note',
            'expectedDelivery',
            [
                'label' => 'Accepted',
                'value' => 'Not Implemented',
            ],
            'code_1c',
        ],
    ]);
    ?>
    </div>

    <div class="col-md-6">
        <h3><?= \Yii::t('app', 'Invoice Details') ?></h3>
        <?php
        echo DetailView::widget([
            'model' => $invoice,
            'options' => [
                'class' => 'table table-striped table-bordered detail-view',
            ],
            'attributes' => [
                'hasDocuments',
                'counterAgentName',
                'invoiceRecepientName',
                'payerOrganizationName',
                'contractName',
                'invoiceName',
                'originalPriceFormatted',
                'payment_part',
                'requiredPaymentFormatted',
                'currencyWithConversion',
                'priceRubFormatted',
                'requiredPaymentRubFormatted',
            ],
        ]);
        ?>
    </div>
</div>

<div class="row">

    <div class="col-md-6">
        <h3><?= \Yii::t('app', 'Agreement')?></h3>
        Under Construction
<?php if (!empty($budget_data)) : ?>
        <h3><?= \Yii::t('app', 'Budget')?></h3>
        <table id="budget-table" class="table table-striped table-bordered">
            <thead>
            <tr>
                <th><?= \Yii::t('app', 'Period') ?></th>
                <th><?= \Yii::t('app', 'CFR') ?></th>
                <th><?= \Yii::t('app', 'Budget') ?></th>
                <th><?= \Yii::t('app', 'Performance') ?></th>
                <th><?= \Yii::t('app', 'Rest') ?></th>
            </tr>
            </thead>
            <tbody>
            <tr>
                <td><?= $budget_data['period']->name ?></td>
                <td><?php
                    $cfr_names = [];
                    foreach ($budget_data['cfr_list'] as $cfr) {
                        $cfr_names[] = $cfr->name;
                    }
                    echo join(',', $cfr_names);
                    ?></td>
                <td><?= $rubCurrency->getFormattedAmount($budget_data['budget']) ?></td>
                <td><?= $rubCurrency->getFormattedAmount($budget_data['performance']) ?></td>
                <td style="color: <?= $budget_data['rest'] >= 0 ? 'green' : 'orangered' ?>"><?= $rubCurrency->getFormattedAmount($budget_data['rest']) ?></td>
            </tr>
            </tbody>
        </table>
<?php endif; ?>
    </div>

    <div class="col-md-6">
        <h3><?= \Yii::t('app', 'Payment Details') ?></h3>
        <?php
        echo DetailView::widget([
            'model' => $invoice,
            'options' => [
                'class' => 'table table-striped table-bordered detail-view',
            ],
            'attributes' => [
                'urgencyReadable',
                'desiredPaymentDate',
                'bankAccountReadable',
                'dueDateReadable',
                'paymentDate',
                [
                    'label' => \Yii::t('app', 'Payment Order No.'),
                    'value' => 'Not Implemented',
                ],
            ],
        ]);
        ?>
    </div>
</div>

<?php if (count($invoice->attachments) > 0) : ?>
    <h3><?=\Yii::t('app', 'Attachments')?></h3>
    <ul>
    <?php foreach ($invoice->attachments as $file) : ?>
        <li><?= Html::a($file->name, $file->getUrl())?></li>
    <?php endforeach; ?>
    </ul>
<?php endif; ?>

<?php if ($i_can_update) : ?>
<a style="margin-bottom: 20px;" class="btn btn-primary" href="<?= Url::to(['/payment-requests/update', 'id' => $invoice->id])?>"><?=\Yii::t('app', 'Edit'); ?></a>
<?php endif;?>

<?php if ($i_can_approve) : ?>
    <a style="margin-bottom: 20px;" class="btn btn-success" href="<?= Url::to(['/payment-requests/approve', 'id' => $invoice->id])?>"><?=\Yii::t('app', 'Approve'); ?></a>
<?php endif;?>

<?php if ($i_can_cancel) : ?>
    <a style="margin-bottom: 20px;" class="btn btn-danger" href="<?= Url::to(['/payment-requests/cancel', 'id' => $invoice->id])?>"><?=\Yii::t('app', 'Cancel'); ?></a>
<?php endif;?>

<?php if ($i_can_create) : ?>
    <a style="margin-bottom: 20px;" class="btn btn-default" href="<?= Url::to(['/payment-requests/copy', 'id' => $invoice->id])?>"><?=\Yii::t('app', 'Copy'); ?></a>
<?php endif;?>
