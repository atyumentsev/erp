<?php

namespace app\models\PaymentRequest;

use app\components\helpers\CurrencyHelper;
use app\models\PaymentRequest;
use app\models\Urgency;

class PaymentRequestView extends PaymentRequest
{
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
            return '';
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

    public function getOriginalPriceReadable()
    {
        if (empty($this->original_price)) {
            return $this->original_price;
        }
        if (!empty($this->originalCurrency)) {
            return $this->originalCurrency->getFormattedAmount($this->original_price);
        }
        return $this->original_price / 100;
    }

    public function getRequiredPaymentReadable()
    {
        if (empty($this->required_payment)) {
            return $this->required_payment;
        }
        if (!empty($this->originalCurrency)) {
            return $this->originalCurrency->getFormattedAmount($this->required_payment);
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
        return $this->rubCurrency->getFormattedAmount($ret_val);
    }

    public function getRequiredPaymentRubReadable()
    {
        if (!empty($this->required_payment_rub)) {
            $ret_val = $this->required_payment_rub;
        } else {
            $ret_val = CurrencyHelper::convertToRubUnits($this->original_currency_id, $this->conversion_percent, $this->required_payment);
        }
        return $this->rubCurrency->getFormattedAmount($ret_val);

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
        return Urgency::getList()[$this->urgency] ?? null;
    }

    public function getIsIn1CReadable()
    {
        return !empty($this->code_1c) ? \Yii::t('app', 'Yes') : \Yii::t('app', 'No');
    }

    public function getApprovedByReadable()
    {
        return $this->lastApprover->name;
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
}
