<?php

use app\models\forms\PaymentRequestForm;
use kartik\select2\Select2;
use yii\bootstrap\ActiveForm;
use yii\web\JsExpression;
use yii\bootstrap\Html;
use kartik\date\DatePicker;

/**
 * @var yii\web\View $this
 * @var array $currencies
 * @var array $products
 * @var array $cashflow_items
 * @var array $departments
 * @var array $affiliates
 * @var array $users
 * @var array $payers
 * @var array $urgency
 * @var array $bank_accounts_hash
 * @var PaymentRequestForm $model
 * @var string $action
 */

$bank_accounts = [null => ''] + ($bank_accounts_hash[$model->payer_organization_id] ?? []);
?>

    <style>
        span.file-remove-button {
            font-weight: normal;
            color: red;
        }
        h3 {
            padding-top: 10px;
        }
    </style>


    <div class="contract-search">
<?php
$form = ActiveForm::begin([
    'action' => [$action],
    'layout' => 'horizontal',
    'method' => 'post',
    'options' => ['id' => 'invoice-create-form'],
    'fieldConfig' => [
        //'template' => "<div class=\"col-sm-6\">{label}</div><div class=\"col-sm-6\">{input}{error}</div>",
    ],
    'enableClientValidation'=>false,
]);
?>
    <div class="row">
        <!--        <div class="col-md-6">-->
        <h3 class="col-md-offset-1"><?= \Yii::t('app', 'Payment Destination') ?></h3>
        <?= $form->field($model, 'customer_department_id')->dropDownList($departments) ?>
        <?= $form->field($model, 'executor_department_id')->dropDownList($departments) ?>
        <?= $form->field($model, 'author_id')->dropDownList($users) ?>
        <?= $form->field($model, 'product_id')->dropDownList($products) ?>
        <?= $form->field($model, 'cashflow_item_id')->dropDownList($cashflow_items) ?>
        <?= $form->field($model, 'description') ?>
        <?= $form->field($model, 'note') ?>
        <div class="form-group field-paymentrequestform-note">
            <label class="control-label col-sm-3"
                   for="paymentrequestform-expectedDeliveryReadable"><?= \Yii::t('app', 'Expected Delivery Date'); ?></label>
            <div class="col-sm-6">
                <?= DatePicker::widget([
                    'name' => 'PaymentRequestForm[expectedDeliveryReadable]',
                    'type' => DatePicker::TYPE_INPUT,
                    'value' => $model->getExpectedDelivery(),
                    'pluginOptions' => [
                        'autoclose' => true,
                        'format' => 'dd.mm.yyyy'
                    ]
                ]);
                ?>
            </div>
        </div>

        <h3 class="col-md-offset-1"><?= \Yii::t('app', 'Invoice Details') ?></h3>
        <?= '';//$form->field($model, 'internal_number') ?>

        <div class="form-group field-paymentrequestform-has_documents">
        <label class="control-label col-sm-3"
               for="paymentrequestform-has_documents"><?= \Yii::t('app', 'Has Documents'); ?></label>
        <div class="col-sm-6">
        <?= $form->field($model, 'has_documents')->checkbox([
            'template' => "<div class=\"col-sm-6\">{input}</div>"
        ])->label(false) ?>
        </div>
        </div>

        <?php
        $counteragentName = (empty($model->counterAgent)) ? '' : $model->counterAgent->name;
        echo $form->field($model, 'counteragent_id')
            ->widget(Select2::classname(), [
                'options' => ['placeholder' => \Yii::t('app', 'Search for a counteragent ...')],
                'language' => 'ru',
                'initValueText' => $counteragentName,
                'pluginOptions' => [
                    'allowClear' => true,
                    'minimumInputLength' => 3,
                    'ajax' => [
                        'url' => '/counter-agents/find',
                        'dataType' => 'json',
                        'data' => new JsExpression('function(params) { return {q:params.term}; }')
                    ],
                    'escapeMarkup' => new JsExpression('function (markup) { return markup; }'),
                    'templateResult' => new JsExpression('function(counteragent) { return counteragent.text; }'),
                    'templateSelection' => new JsExpression('function(counteragent) { return counteragent.text; }'),
                ],
            ]);
        ?>
        <?= $form->field($model, 'invoice_recepient_affiliate_id')->dropDownList($affiliates) ?>
        <?= $form->field($model, 'payer_organization_id')->dropDownList($affiliates) ?>
        <?= $form->field($model, 'contract_number') ?>
        <div class="form-group field-paymentrequestform-contractDateReadable">
            <label class="control-label col-sm-3"
                   for="paymentrequestform-contractDateReadable"><?= \Yii::t('app', 'Contract Date'); ?></label>
            <div class="col-sm-6">
                <?= DatePicker::widget([
                    'name' => 'PaymentRequestForm[contractDateReadable]',
                    'type' => DatePicker::TYPE_INPUT,
                    'value' => $model->getContractDateReadable() ?: null,
                    'pluginOptions' => [
                        'autoclose' => true,
                        'format' => 'dd.mm.yyyy'
                    ]
                ]);
                ?>
            </div>
        </div>

        <?= $form->field($model, 'invoice_number') ?>
        <div class="form-group field-paymentrequestform-invoiceDateReadable">
            <label class="control-label col-sm-3"
                   for="paymentrequestform-invoiceDateReadable"><?= \Yii::t('app', 'Invoice Date'); ?></label>
            <div class="col-sm-6">
                <?= DatePicker::widget([
                    'name' => 'PaymentRequestForm[invoiceDateReadable]',
                    'type' => DatePicker::TYPE_INPUT,
                    'value' => $model->getInvoiceDateReadable() ?: null,
                    'pluginOptions' => [
                        'autoclose' => true,
                        'format' => 'dd.mm.yyyy'
                    ]
                ]);
                ?>
            </div>
        </div>

        <?= $form->field($model, 'originalPriceReadable') ?>
        <?= $form->field($model, 'payment_part') ?>
        <?= $form->field($model, 'requiredPaymentReadable') ?>
        <?= $form->field($model, 'original_currency_id')->dropDownList($currencies) ?>
        <?= $form->field($model, 'conversion_percent') ?>
        <!--        </div>-->

        <h3 class="col-md-offset-1"><?= \Yii::t('app', 'Payment Details') ?></h3>
        <div class="form-group field-paymentrequestform-desiredPaymentDateReadable">
            <label class="control-label col-sm-3"
                   for="paymentrequestform-desiredPaymentDateReadable"><?= \Yii::t('app', 'Desired Payment Date'); ?></label>
            <div class="col-sm-6">
                <?= DatePicker::widget([
                    'name' => 'PaymentRequestForm[desiredPaymentDateReadable]',
                    'type' => DatePicker::TYPE_INPUT,
                    'value' => $model->getDesiredPaymentDate(),
                    'pluginOptions' => [
                        'autoclose' => true,
                        'format' => 'dd.mm.yyyy'
                    ]
                ]);
                ?>
            </div>
        </div>

        <div class="form-group field-paymentrequestform-dueDateReadable">
            <label class="control-label col-sm-3"
                   for="paymentrequestform-dueDateReadable"><?= \Yii::t('app', 'Due Date'); ?></label>
            <div class="col-sm-6">
                <?= DatePicker::widget([
                    'name' => 'PaymentRequestForm[dueDateReadable]',
                    'type' => DatePicker::TYPE_INPUT,
                    'value' => $model->getDueDateReadable(),
                    'pluginOptions' => [
                        'autoclose' => true,
                        'format' => 'dd.mm.yyyy'
                    ]
                ]);
                ?>
            </div>
        </div>

        <?= $form->field($model, 'bank_account_id')->dropDownList($bank_accounts) ?>

        <?= $form->field($model, 'urgency')->dropDownList($urgency) ?>
    </div>


<?php if (!$model->isNewRecord) : ?>
    <h3 class="col-md-offset-1"><?= \Yii::t('app', 'Attachments') ?></h3>

    <div class="row">
        <?= $form->field($model, 'attachment')->widget(\kartik\file\FileInput::classname(), [
            'options' => [
                'accept' => ['image/*', 'application/pdf'],
            ],
            'pluginOptions' => [
                'showUpload' => false,
            ],
        ]); ?>
    </div>

    <?php if (count($model->attachments) > 0) : ?>
        <ul>
        <?php foreach ($model->attachments as $file) : ?>
            <li>
                <?= Html::a($file->name, $file->getUrl()) ?>
                <span class="glyphicon glyphicon-remove file-remove-button" data-id="<?= $file->id ?>"></span>
            </li>
        <?php endforeach; ?>
        </ul>

        </div>

        <?php
        $this->registerJs('$(".file-remove-button").click(function () {
    if (confirm("' . \Yii::t('app', 'Are you sure you want to delete this file?') . '")) {
        $.get("/file/delete?id=" + $(this).data("id"), function () {
            window.location = window.location;
        });
    }
})');
        ?>
    <?php endif; ?>
<?php endif; ?>

    <div class="form-group" style="margin-left: 16.95%;">
        <?= Html::submitButton(\Yii::t('app', 'Save'), ['class' => 'btn btn-primary']) ?>
        <?= Html::a(\Yii::t('app', 'Reset'), ['create'], ['class' => 'btn btn-default']) ?>
    </div>

<?php ActiveForm::end(); ?>

<?php

$this->registerJs("$(\"#paymentrequestform-customer_department_id\").change(function () {
    $(\"#paymentrequestform-executor_department_id\").val(this.value);
})");

$this->registerJs("$(\"#paymentrequestform-invoice_recepient_affiliate_id\").change(function () {
    $(\"#paymentrequestform-payer_organization_id\").val(this.value);
})");

$this->registerJs("$(\"#paymentrequestform-payment_part\").change(function () {
    $(\"#paymentrequestform-requiredpaymentreadable\").val(this.value / 100 * $(\"#paymentrequestform-originalpricereadable\").val());
})");

$this->registerJs("$(\"#paymentrequestform-requiredpaymentreadable\").change(function () {
    $(\"#paymentrequestform-payment_part\").val(this.value * 100 / $(\"#paymentrequestform-originalpricereadable\").val());
})");

$this->registerJs('
    $("#paymentrequestform-payer_organization_id").change(function () {
        var affiliate_id = $("#paymentrequestform-payer_organization_id").val();
        var bank_accounts_hash = ' . json_encode($bank_accounts_hash) . ';
        var newOptions = (bank_accounts_hash[affiliate_id] === undefined) ? {} : bank_accounts_hash[affiliate_id];

        var $el = $("#paymentrequestform-bank_account_id");
        $el.empty(); // remove old options
        $el.append($("<option></option>"));
        $.each(newOptions, function(value, key) {
            $el.append($("<option></option>").attr("value", value).text(key));
        });        
    })
');
