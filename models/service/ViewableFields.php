<?php

namespace app\models\service;

use app\models\PaymentRequest;
use app\models\UserSettings;

class ViewableFields
{
    const AVAILABLE_FIELDS = [
        'customerDepartmentShortNameWithShortLabel',
        'executorDepartmentShortNameWithShortLabel',
        'isIn1CReadable',
        'approvedByReadable',
        'payerOrganizationName',
        'payment_part',
        'currencyWithConversion',
        'originalPriceFormatted',
        'requiredPaymentReadable',
        'priceRubReadable',
        'requiredPaymentRubReadable',
        'contract_number',
        'contractDateReadable',
        'invoice_number',
        'invoiceDateReadable',
        'paymentDate',
        'payment_order_number',
        'counterAgentName',
        'productName',
        'cashflowItemShortName',
        'description',
        'authorShortName',
        'dueDateReadable',
        'urgencyReadable',
        'expectedDelivery',
        'note',
        'dueDateWeek',
        'dueDateMonth',
        'statusReadable',
    ];

    const DEFAULT_FIELDS = [
        'customerDepartmentShortNameWithShortLabel',
        'executorDepartmentShortNameWithShortLabel',
        'payerOrganizationName',
        'currencyWithConversion',
        'originalPriceFormatted',
        'requiredPaymentFormatted',
        'requiredPaymentRubFormatted',
        'contractName',
        'counterAgentName',
        'productName',
        'cashflowItemShortName',
        'description',
        'authorShortName',
        'dueDateReadable',
        'urgencyReadable',
        'expectedDelivery',
        'statusReadable',
    ];

    /**
     * @param int $user_id
     * @return array
     */
    public static function getUserFields(int $user_id) : array
    {
        /** @var UserSettings $settings */
        $settings = UserSettings::findOne([
                'user_id' => $user_id,
                'name' => 'payment_requests_fields',
            ]);

        if (!$settings) {
            return self::DEFAULT_FIELDS;
        } else {
            return json_decode($settings->value);
        }
    }

    /**
     * @return array
     */
    public static function getAvailableFields() : array
    {
        $pr = new PaymentRequest();
        $attributes = $pr->attributeLabels();

        $ret = [];

        foreach (self::AVAILABLE_FIELDS as $field) {
            $ret[$field] = $attributes[$field];
        }

        return $ret;
    }

    public static function saveUserFields($user_id, $fields)
    {
        $arr = [];
        foreach ($fields as $field) {
            if (in_array($field, self::AVAILABLE_FIELDS)) {
                $arr[] = $field;
            }
        }

        $settings = UserSettings::findOne([
            'user_id' => $user_id,
            'name' => 'payment_requests_fields',
        ]);
        if (!isset($settings)) {
            $settings = new UserSettings([
                'user_id' => $user_id,
                'name' => 'payment_requests_fields',
            ]);
        }
        $settings->value = json_encode($arr);
        return $settings->save();
    }
}
