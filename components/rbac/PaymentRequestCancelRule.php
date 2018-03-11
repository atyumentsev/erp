<?php

namespace app\components\rbac;

use app\models\PaymentRequest;

class PaymentRequestCancelRule extends \yii\rbac\Rule
{
    public $name = 'pr.cancel.my.rule';

    public function execute($user, $item, $params)
    {
        if (isset($params['entity_id']) && is_numeric($params['entity_id'])) {
            $pr = PaymentRequest::findOne(['id' => $params['entity_id']]);
            return $user == $pr->author_id;
        }
        return false;
    }
}
