<?php
/**
 * @var $this yii\web\View
 * @var \yii\data\ActiveDataProvider $dataProvider
 * @var \app\models\search\PaymentRequestSearch $searchModel
 * @var array $departments
 * @var array $cashflow_items
 * @var array $users
 * @var array $payers
 * @var array $available_rankings
 * @var array $fields
 * @var array $bank_accounts_hash
 * @var bool $i_can_view_ranking
 * @var \app\models\BankAccount $account
 */
use yii\helpers\Url;
use app\models\PaymentRequestRanking;
use yii\bootstrap\ActiveForm;
use kartik\select2\Select2;
use yii\bootstrap\Html;
use yii\web\JsExpression;
use app\models\service\RankingService;
use app\components\helpers\CurrencyHelper;

$this->title = \Yii::t('app', 'Payment Requests Selection');
$this->params['breadcrumbs'][] = $this->title;
$entity_description = RankingService::getAvailableRankings();

?>
<style>
    #invoices-list th {
        white-space: normal;
        vertical-align: middle;
    }
    #invoice-search-form div {
        margin-bottom:10px;
    }

    #invoice-search-form div.form-group {
        display: inline;
    }
    #invoice-search-form div label{
        margin-top:5px;
    }

    div.invoice-search {
        margin-bottom: 25px;
    }

</style>

<div class="invoice-search">
    <?php
    $form = ActiveForm::begin([
        'action' => ['index'],
        'layout' => 'default',
        'method' => 'get',
        'options' => ['id' => 'invoice-search-form'],
        'fieldConfig' => [
            'template' => "<div class=\"col-md-1\">{label}</div><div class=\"col-md-2\">{input}</div>",
        ],
    ]);
    ?>
    <div class="row">
        <?= $form->field($searchModel, 'ranking')->dropDownList($available_rankings); ?>
        <?= $form->field($searchModel, 'bank_account_id')->dropDownList($bank_accounts_hash); ?>
    </div>
    <div class="row">
        <?= $form->field($searchModel, 'executor_department_id')->dropDownList($departments); ?>
        <?= $form->field($searchModel, 'customer_department_id')->dropDownList($departments); ?>
        <?= $form->field($searchModel, 'payer_organization_id')->dropDownList($payers); ?>
    </div>
    <div class="row">
        <?= $form->field($searchModel, 'cashflow_item_id')->dropDownList($cashflow_items); ?>
        <?php
        $counteragentName = (empty($searchModel->counterAgent)) ? '' : $searchModel->counterAgent->name;
        echo $form->field($searchModel, 'counteragent_id')->label(\Yii::t('app', 'CounterAgent'))
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
        <?= $form->field($searchModel, 'author_id')->dropDownList($users); ?>
    </div>
    <div class="form-group" style="margin-left: 8.5%;">
        <?= Html::submitButton(\Yii::t('app', 'Search'), ['class' => 'btn btn-primary']) ?>
        <?= Html::a(\Yii::t('app', 'Reset'), ['index'], ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>
</div>

<?php
$columns = [
    [
        'label' => \Yii::t('app', 'Rank'),
        'format' => 'raw',
        'value' => function (PaymentRequestRanking $model) use($entity_description) {
            $ret = ' <span title="';
            foreach ($model->rank_explain as $type => $_rank) {
                $ret .= "{$entity_description[$type]} => {$_rank}\n";
            }
            $ret .= '">' . $model->rank . '</span>';
            return $ret;
        },
    ],
    [
        'label' => \Yii::t('app', '#'),
        'format' => 'raw',
        'value' => function (PaymentRequestRanking $model) {
            return Html::a($model->internal_number, ['/payment-requests/view', 'id' => $model->id]);
        },
    ],
    [
        'label' => \Yii::t('app', 'Amount in Account currency'),
        'format' => 'raw',
        'value' => function (PaymentRequestRanking $model) use ($account) {
            return CurrencyHelper::convertToBankAccountCurrencyUnits($model, $account);
        },
    ],
];

$columns += $fields;

echo \yii\grid\GridView::widget([
    'tableOptions' => ['class' => 'table table-striped table-bordered', 'id' => 'invoices-list'],
    'dataProvider' => $dataProvider,
    'columns' => $columns,
]);

?>

<?php if ($i_can_view_ranking) : ?>
<a style="margin-bottom: 20px;" class="btn" href="<?= Url::to('/ranking')?>"><?=\Yii::t('app', 'Edit Rankings'); ?></a>
<?php endif; ?>

<a style="margin-bottom: 20px;" class="btn" href="<?= Url::to('/user-settings/payment-requests')?>"><?=\Yii::t('app', 'Configure fields'); ?></a>


