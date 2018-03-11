<?php

namespace app\models;

use app\components\helpers\CurrencyHelper;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;

/**
 * Class Payment
 * @package app\models
 *
 * @property integer $id
 * @property string $code_1c
 * @property integer $customer_department_id
 * @property integer $executor_department_id
 * @property string $internal_number
 * @property integer $payer_organization_id
 * @property string $payment_part
 * @property integer $original_currency_id
 * @property integer $original_price
 * @property float $conversion_percent
 * @property integer $required_payment
 * @property integer $price_rub
 * @property integer $required_payment_rub
 * @property integer $contract_id
 * @property string $contract_date
 * @property string $contract_number
 * @property string $number
 * @property string $invoice_date
 * @property string $payment_date
 * @property string $payment_order_number
 * @property integer $counteragent_id
 * @property integer $product_id
 * @property integer $cashflow_item_id
 * @property string $description
 * @property integer $author_id
 * @property string $due_date
 * @property integer $urgency
 * @property string $expected_delivery
 * @property string $note
 * @property string $desired_payment_date
 * @property integer $invoice_recepient_affiliate_id
 * @property integer $bank_account_id
 * @property integer $has_documents
 * @property integer $status
 * @property integer $updated_at
 * @property integer $created_at
 *
 * @property Currency       $originalCurrency
 * @property Currency       $rubCurrency
 * @property CounterAgent   $counterAgent
 * @property Contract       $contract
 * @property Department     $customerDepartment
 * @property Department     $executorDepartment
 * @property Affiliate      $payerOrganization
 * @property Product        $product
 * @property CashflowItem   $cashflowItem
 * @property User           $author
 * @property Affiliate      $invoiceRecepient
 * @property BankAccount    $bankAccount
 * @property File[]         $attachments
 *
 * @property string $statusLabel
 */
class PaymentRequest extends ActiveRecord
{
    public static $rubCurrency;
    public $originalCurrency;

    const CURRENCY_CODE_RUB = 643;

    const STATUS_NEW        = 1;
    const STATUS_READY      = 2;

    const STATUS_APPROVED   = 9;

    const STATUS_SELECTED   = 10;
    const STATUS_TO_BE_PAID = 11;
    const STATUS_PAID       = 12;
    const STATUS_CANCELLED  = 13;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'payment_request';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            ['internal_number', 'unique'],
            [['code_1c'], 'string'],
            [['has_documents'], 'boolean'],
            [['status'], 'integer'],
            [[
                'original_currency_id',
                'original_price',
                'required_payment',
                'description',
            ], 'required'],
            // links
            [[
                'original_currency_id',
                'counteragent_id',
                'customer_department_id',
                'executor_department_id',
                'payer_organization_id',
                'contract_id',
                'product_id',
                'cashflow_item_id',
                'author_id',
                'invoice_recepient_affiliate_id',
                'bank_account_id',
            ], 'integer'],
            // strings
            [[
                'internal_number',
                'invoice_number',
                'description',
                'expected_delivery',
                'note',
                'contract_number',
                'payment_order_number',
            ], 'string'],
            // prices
            [[
                'original_price',
                'required_payment',
                'price_rub',
                'required_payment_rub',
            ], 'integer'],
            [[
                'conversion_percent',
                'payment_part',
            ], 'number', 'min' => 0, 'max' => 100],
            // compare required payments and prices
            ['required_payment', 'compare', 'compareAttribute' => 'original_price', 'operator' => '<=', 'type' => 'number'],
            ['required_payment_rub', 'compare', 'compareAttribute' => 'price_rub', 'operator' => '<=', 'type' => 'number'],
            // dates
            [['invoice_date', 'payment_date', 'due_date', 'contract_date', 'desired_payment_date'], 'string'],
            [['invoice_date', 'payment_date', 'due_date', 'contract_date', 'desired_payment_date'], 'date', 'format' => 'php:Y-m-d'],
            // 1C
            ['code_1c', 'string'],
            ['code_1c', 'unique'],
            // urgency
            ['urgency', 'integer'],
            ['urgency', 'in', 'range' => array_keys(Urgency::getList())],
            // check consistency
            ['original_currency_id',    'exist', 'targetClass' => Currency::class, 'targetAttribute' => 'id'],
            ['counteragent_id',         'exist', 'targetClass' => CounterAgent::class, 'targetAttribute' => 'id'],
            ['original_currency_id',    'exist', 'targetClass' => Currency::class, 'targetAttribute' => 'id'],
            ['contract_id',             'exist', 'targetClass' => Contract::class, 'targetAttribute' => 'id'],
            ['product_id',              'exist', 'targetClass' => Product::class, 'targetAttribute' => 'id'],
            ['cashflow_item_id',        'exist', 'targetClass' => CashflowItem::class, 'targetAttribute' => 'id'],
            ['author_id',               'exist', 'targetClass' => User::class, 'targetAttribute' => 'id'],
            ['payer_organization_id',   'exist', 'targetClass' => Affiliate::class, 'targetAttribute' => 'id'],
            [[
                'customer_department_id',
                'executor_department_id'
            ], 'exist', 'targetClass' => Department::class, 'targetAttribute' => 'id'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'code_1c' => \Yii::t('app', '1C Code'),
            'customer_department_id' => \Yii::t('app', 'Customer Department'),
            'executor_department_id' => \Yii::t('app', 'Executor Department'),
            'internal_number' => \Yii::t('app', 'Internal Number'),
            'payer_organization_id' => \Yii::t('app', 'Payer'),
            'payment_part' => \Yii::t('app', 'Payment Part, %'),
            'original_currency_id' => \Yii::t('app', 'Original Currency'),
            'original_price' => \Yii::t('app', 'Original Price'),
            'conversion_percent' => \Yii::t('app', 'Conversion Rate, %'),
            'required_payment' => \Yii::t('app', 'Required Payment'),
            'price_rub' => \Yii::t('app', 'Price, RUB'),
            'required_payment_rub' => \Yii::t('app', 'Required Payment, RUB'),
            'contract_id' => \Yii::t('app', 'Contract'),
            'contract_number' => \Yii::t('app', 'Contract #'),
            'contractDateReadable' => \Yii::t('app', 'Contract Date'),
            'invoice_number' => \Yii::t('app', 'Invoice Number'),
            'invoice_date' => \Yii::t('app', 'Invoice Date'),
            'payment_date' => \Yii::t('app', 'Payment Date'),
            'payment_order_number' => \Yii::t('app', 'Payment Order No.'),
            'counteragent_id' => \Yii::t('app', 'CounterAgent'),
            'product_id' => \Yii::t('app', 'Product'),
            'cashflow_item_id' => \Yii::t('app', 'Cashflow Item'),
            'description' => \Yii::t('app', 'Description'),
            'author_id' => \Yii::t('app', 'Responsible Person'),
            'bank_account_id' => \Yii::t('app', 'Bank Account'),
            'due_date' => \Yii::t('app', 'Due Date'),
            'urgency' => \Yii::t('app', 'Urgency'),
            'expected_delivery' => \Yii::t('app', 'Expected Delivery Date'),
            'invoice_recepient_affiliate_id' => \Yii::t('app', 'Invoice Recepient'),
            'note' => \Yii::t('app', 'Note'),
            'status' => \Yii::t('app', 'Status'),
            'updated_at' => \Yii::t('app', 'Updated At'),
            'created_at' => \Yii::t('app', 'Created At'),

            //
            'originalPriceReadable' => \Yii::t('app', 'Original Price'),
            'currencyWithConversion' => \Yii::t('app', 'Currency'),
            'requiredPaymentReadable' => \Yii::t('app', 'Required Payment'),
            'requiredPaymentRubReadable' => \Yii::t('app', 'Required Payment, RUB'),
            'priceRubReadable' => \Yii::t('app', 'Price, RUB'),

            'originalPriceFormatted' => \Yii::t('app', 'Original Price'),
            'requiredPaymentFormatted' => \Yii::t('app', 'Required Payment'),
            'requiredPaymentRubFormatted' => \Yii::t('app', 'Required Payment, RUB'),
            'priceRubFormatted' => \Yii::t('app', 'Price, RUB'),

            'counterAgentName' => \Yii::t('app', 'Counteragent'),
            'paymentDate' => \Yii::t('app', 'Payment Date'),
            'payerOrganizationName' => \Yii::t('app', 'Payer'),
            'invoiceRecepientName' => \Yii::t('app', 'Invoice Recepient'),
            'invoiceRecepientShortName' => \Yii::t('app', 'Invoice Recepient'),
            'payerOrganizationShortName' => \Yii::t('app', 'Payer'),
            'cashflowItemName' => \Yii::t('app', 'Cashflow Item'),
            'cashflowItemShortName' => \Yii::t('app', 'Cashflow Item'),
            'productName' => \Yii::t('app', 'Product'),
            'customerDepartmentName' => \Yii::t('app', 'Customer Department'),
            'customerDepartmentShortName' => \Yii::t('app', 'Customer Department'),
            'customerDepartmentShortNameWithShortLabel' => \Yii::t('app', 'Cust. Dpt'),
            'executorDepartmentName' => \Yii::t('app', 'Executor Department'),
            'executorDepartmentShortName' => \Yii::t('app', 'Executor Department'),
            'executorDepartmentShortNameWithShortLabel' => \Yii::t('app', 'Exec. Dpt'),
            'contractName' => \Yii::t('app', 'Contract'),
            'contractDate' => \Yii::t('app', 'Contract Date'),
            'contractNumber' => \Yii::t('app', 'Contract Number'),
            'dueDateReadable' => \Yii::t('app', 'Due Date'),
            'authorShortName' => \Yii::t('app', 'Author'),
            'invoiceName' => \Yii::t('app', 'Invoice'),
            'invoiceDateReadable' => \Yii::t('app', 'Invoice Date'),
            'expectedDelivery' => \Yii::t('app', 'Expected Delivery Date'),
            'desiredPaymentDateReadable' => \Yii::t('app', 'Desired Payment Date'),
            'urgencyReadable' => \Yii::t('app', 'Urgency'),
            'bankAccountReadable' => \Yii::t('app', 'Bank Account'),
            'approvedByReadable' => \Yii::t('app', 'Approved By'),
            'paymentOrderShortLabel' => \Yii::t('app', 'PO #'),

            'isIn1CReadable' => \Yii::t('app', 'Is in 1C'),
            'dueDateWeek' => \Yii::t('app', 'Planned Payment Week'),
            'dueDateMonth' => \Yii::t('app', 'Planned Payment Month'),
            'statusReadable' => \Yii::t('app', 'Status'),
        ];
    }

    public static function getStatusLabels() : array
    {
        return [
            self::STATUS_NEW => \Yii::t('app', 'New'),
            self::STATUS_READY => \Yii::t('app', 'Ready'),
            self::STATUS_APPROVED => \Yii::t('app', 'Approved'),
            self::STATUS_SELECTED => \Yii::t('app', 'Selected'),
            self::STATUS_TO_BE_PAID => \Yii::t('app', 'To be paid'),
            self::STATUS_PAID => \Yii::t('app', 'Paid'),
            self::STATUS_CANCELLED => \Yii::t('app', 'Cancelled'),
        ];
    }

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'timestamp' => [
                'class' => TimestampBehavior::class,
            ],
        ];
    }


    /* --- Human-readable dates ------------------------------------------------------------------------------------- */
    public function getInvoiceDateReadable()
    {
        if (empty($this->invoice_date)) {
            return $this->invoice_date;
        }
        return date('d.m.Y', strtotime($this->invoice_date));
    }

    public function getContractDateReadable()
    {
        if (empty($this->contract_date)) {
            return $this->contract_date;
        }
        return date('d.m.Y', strtotime($this->contract_date));
    }

    public function getPaymentDate()
    {
        if (empty($this->payment_date)) {
            return null;
        }
        return date('d.m.Y', strtotime($this->payment_date));
    }

    public function getDesiredPaymentDate()
    {
        if (empty($this->desired_payment_date)) {
            return null;
        }
        return date('d.m.Y', strtotime($this->desired_payment_date));
    }

    public function getDueDateReadable()
    {
        if (empty($this->due_date)) {
            return null;
        }
        return date('d.m.Y', strtotime($this->due_date));
    }

    public function getExpectedDelivery()
    {
        if (empty($this->expected_delivery)) {
            return null;
        }
        return date('d.m.Y', strtotime($this->expected_delivery));
    }

    public function getHasDocuments()
    {
        if ($this->has_documents === null) {
            return null;
        }
        return $this->has_documents !== 0 ? \Yii::t('app', 'Yes') : \Yii::t('app', 'No');
    }

    public function init()
    {
        $this->rubCurrency = $this->getRubCurrency();
        $this->originalCurrency = Currency::findOne(['id' => $this->original_currency_id]);
    }

    /* --- Human-readable sums -------------------------------------------------------------------------------------- */
    //FIXME
    public function getOriginalPriceReadable()
    {
        if (empty($this->original_price)) {
            return $this->original_price;
        }
        if (!empty($this->originalCurrency)) {
            return $this->originalCurrency->getMoneyAmountFromUnits($this->original_price);
        }
        return $this->original_price / 100;
    }

    public function getRequiredPaymentReadable()
    {
        if (empty($this->required_payment)) {
            return $this->required_payment;
        }
        if (!empty($this->originalCurrency)) {
            return $this->originalCurrency->getMoneyAmountFromUnits($this->required_payment);
        }
        return $this->required_payment / 100;
    }

    public function getPriceRubReadable()
    {
        if (!empty($this->price_rub)) {
            $ret_val = $this->price_rub;
        } else {
            $ret_val = CurrencyHelper::convertToRubUnits($this->original_currency_id, $this->conversion_percent, $this->original_price);
        }
        return $this->rubCurrency->getMoneyAmountFromUnits($ret_val);
    }

    public function getRequiredPaymentRubReadable()
    {
        if (!empty($this->required_payment_rub)) {
            $ret_val = $this->required_payment_rub;
        } else {
            $ret_val = CurrencyHelper::convertToRubUnits($this->original_currency_id, $this->conversion_percent, $this->required_payment);
        }
        return $this->rubCurrency->getMoneyAmountFromUnits($ret_val);

    }

    public function getOriginalPriceFormatted()
    {
        if (empty($this->original_price)) {
            return $this->original_price;
        }
        if (!empty($this->originalCurrency)) {
            return $this->originalCurrency->getFormattedAmount($this->original_price);
        }
        return $this->original_price / 100;
    }

    public function getRequiredPaymentFormatted()
    {
        if (empty($this->required_payment)) {
            return $this->required_payment;
        }
        if (!empty($this->originalCurrency)) {
            return $this->originalCurrency->getFormattedAmount($this->required_payment);
        }
        return $this->required_payment / 100;
    }

    public function getPriceRubFormatted()
    {
        if (!empty($this->price_rub)) {
            $ret_val = $this->price_rub;
        } else {
            $ret_val = CurrencyHelper::convertToRubUnits($this->original_currency_id, $this->conversion_percent, $this->original_price);
        }
        return $this->rubCurrency->getFormattedAmount($ret_val);
    }

    public function getRequiredPaymentRubFormatted()
    {
        if (!empty($this->required_payment_rub)) {
            $ret_val = $this->required_payment_rub;
        } else {
            $ret_val = CurrencyHelper::convertToRubUnits($this->original_currency_id, $this->conversion_percent, $this->required_payment);
        }
        return $this->rubCurrency->getFormattedAmount($ret_val);

    }

    public function getCurrencyWithConversion()
    {
        if (empty ($this->original_currency_id)) {
            return null;
        }
        return CurrencyHelper::getConversionRate($this->original_currency_id, $this->conversion_percent);
    }

    public function getCounterAgentName()
    {
        if (!empty($this->counteragent_id)) {
            return $this->counterAgent->name;
        }
        return null;
    }

    public function getAuthorShortName()
    {
        if (!empty($this->author_id)) {
            return $this->author->short_name;
        }
        return null;
    }

    public function getPayerOrganizationName()
    {
        if (isset($this->payer_organization_id)) {
            return $this->payerOrganization->name;
        }
        return null;
    }

    public function getPayerOrganizationShortName()
    {
        if (isset($this->payer_organization_id)) {
            return $this->payerOrganization->short_name;
        }
        return null;
    }

    public function getInvoiceRecepientName()
    {
        if (isset($this->invoice_recepient_affiliate_id)) {
            return $this->invoiceRecepient->name;
        }
        return null;
    }

    public function getInvoiceRecepientShortName()
    {
        if (isset($this->invoice_recepient_affiliate_id)) {
            return $this->invoiceRecepient->short_name;
        }
        return null;
    }

    public function getBankAccountReadable()
    {
        if (isset($this->bank_account_id)) {
            return $this->bankAccount->name;
        }
        return null;
    }

    public function getCashflowItemName()
    {
        if (isset($this->cashflow_item_id)) {
            return $this->cashflowItem->name;
        }
        return null;
    }

    public function getCashflowItemShortName()
    {
        if (isset($this->cashflow_item_id)) {
            return isset($this->cashflowItem->short_name) ? $this->cashflowItem->short_name : $this->cashflowItem->name;
        }
        return null;
    }

    public function getProductName()
    {
        if (isset($this->product_id)) {
            return $this->product->name;
        }
        return null;
    }

    public function getCustomerDepartmentName()
    {
        if (isset($this->customer_department_id)) {
            return $this->customerDepartment->name;
        }
        return null;
    }

    public function getCustomerDepartmentShortName()
    {
        if (isset($this->customer_department_id)) {
            return $this->customerDepartment->short_name;
        }
        return null;
    }

    public function getExecutorDepartmentName()
    {
        if (isset($this->executor_department_id)) {
            return $this->executorDepartment->name;
        }
        return null;
    }

    public function getCustomerDepartmentShortNameWithShortLabel()
    {
        return $this->getCustomerDepartmentShortName();
    }

    public function getExecutorDepartmentShortName()
    {
        if (isset($this->executor_department_id)) {
            return $this->executorDepartment->short_name;
        }
        return null;
    }

    public function getExecutorDepartmentShortNameWithShortLabel()
    {
        return $this->getExecutorDepartmentShortName();
    }

    public function getContractName()
    {
        if (empty($this->contract_date)) {
            return $this->contract_number;
        }
        if (empty($this->contract_number)) {
            return $this->getContractDateReadable();
        }
        return \Yii::t('app', '{contract_number} from {contract_date}', [
            'contract_number' => $this->contract_number,
            'contract_date' => $this->getContractDateReadable(),
        ]);
    }

    public function getInvoiceName()
    {
        return \Yii::t('app', '{invoice_number} from {invoice_date}', [
            'invoice_number' => $this->invoice_number,
            'invoice_date' => $this->getInvoiceDateReadable(),
        ]);
    }

    public function getUrgencyReadable()
    {
        return isset(Urgency::getList()[$this->urgency]) ?? null;
    }

    public function getIsIn1CReadable()
    {
        return !empty($this->code_1c) ? \Yii::t('app', 'Yes') : \Yii::t('app', 'No');
    }

    public function getApprovedByReadable()
    {
        // @TODO
        return '!UC!';
    }

    public function getPaymentOrderShortLabel()
    {
        return $this->payment_order_number;
    }

    public function getDueDateMonth()
    {
        if (isset($this->due_date)) {
            return date('m/Y', strtotime($this->due_date));
        }
        return null;
    }

    public function getDueDateWeek()
    {
        if (isset($this->due_date)) {
            return date('W', strtotime($this->due_date));
        }
        return null;
    }

    public function getStatusReadable()
    {
        $labels = $this->getStatusLabels();
        return $labels[$this->status] ?? $this->status;
    }

    /* --- Relations ------------------------------------------------------------------------------------------------ */

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCounterAgent()
    {
        return $this->hasOne(CounterAgent::class, ['id' => 'counteragent_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getContract()
    {
        return $this->hasOne(Contract::class, ['id' => 'contract_id']);
    }


    /**
     * @return \yii\db\ActiveQuery
     */
    public function getOriginalCurrency()
    {
        return $this->hasOne(Currency::class, ['id' => 'original_currency_id']);
    }

    /**
     * @param Currency $currency
     */
    public function setOriginalCurrency(Currency $currency)
    {
        $this->originalCurrency = $currency;
    }

    /**
     * @return null|Currency
     */
    public function getRubCurrency()
    {
        return Currency::findOne(['id' => self::CURRENCY_CODE_RUB]);
    }

    /**
     * @param Currency $currency
     */
    public function setRubCurrency(Currency $currency)
    {
        $this->rubCurrency = $currency;
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCustomerDepartment()
    {
        return $this->hasOne(Department::class, ['id' => 'customer_department_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getExecutorDepartment()
    {
        return $this->hasOne(Department::class, ['id' => 'executor_department_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPayerOrganization()
    {
        return $this->hasOne(Affiliate::class, ['id' => 'payer_organization_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getInvoiceRecepient()
    {
        return $this->hasOne(Affiliate::class, ['id' => 'invoice_recepient_affiliate_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getBankAccount()
    {
        return $this->hasOne(BankAccount::class, ['id' => 'bank_account_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProduct()
    {
        return $this->hasOne(Product::class, ['id' => 'product_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCashflowItem()
    {
        return $this->hasOne(CashflowItem::class, ['id' => 'cashflow_item_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAuthor()
    {
        return $this->hasOne(User::class, ['id' => 'author_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAttachments()
    {
        return $this->hasMany(File::class, ['id' => 'file_id'])
            ->viaTable(PaymentRequestFile::tableName(), ['payment_request_id' => 'id']);
    }
}
