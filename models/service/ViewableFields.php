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
        'originalPriceReadable',
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
        'originalPriceReadable',
        'requiredPaymentReadable',
        'priceRubReadable',
        'requiredPaymentRubReadable',
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

    const RIGHT_ALIGN_FIELDS = [
        'originalPriceReadable',
        'requiredPaymentReadable',
        'priceRubReadable',
        'requiredPaymentRubReadable',
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
            $fields =  self::DEFAULT_FIELDS;
        } else {
            $fields = json_decode($settings->value);
        }

        $ret = [];
        foreach ($fields as $field) {
            if (in_array($field, self::RIGHT_ALIGN_FIELDS)) {
                $ret[] = [
                    'attribute' => $field,
                    'contentOptions' => ['class' => 'text-right'],
                    //'headerOptions' => ['class' => 'text-center']
                ];
            } else {
                $ret[] = $field;
            }
        }

        return $ret;
    }

    /**
     * @param int $user_id
     * @return array
     */
    public static function getUserFieldsList(int $user_id) : array
    {
        /** @var UserSettings $settings */
        $settings = UserSettings::findOne([
            'user_id' => $user_id,
            'name' => 'payment_requests_fields',
        ]);

        if (!$settings) {
            $fields =  self::DEFAULT_FIELDS;
        } else {
            $fields = json_decode($settings->value);
        }

        $ret = [];
        foreach ($fields as $field) {
            $ret[] = $field;
        }

        return $ret;
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
