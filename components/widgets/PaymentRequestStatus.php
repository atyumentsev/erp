<?php
namespace app\components\widgets;

use app\models\PaymentRequest;
use yii\base\Widget;

class PaymentRequestStatus extends Widget
{
    const COLOR_TABLE = [
        PaymentRequest::STATUS_NEW          => 'grey',
        PaymentRequest::STATUS_READY        => 'yellow',
        PaymentRequest::STATUS_APPROVED     => '#88ff88',
        PaymentRequest::STATUS_CANCELLED    => '#FF8080',
        PaymentRequest::STATUS_SELECTED     => 'orange',
        PaymentRequest::STATUS_TO_BE_PAID   => 'orange',
        PaymentRequest::STATUS_PAID         => 'orange',
        'default'                           => 'white',
    ];

    /** @var PaymentRequest */
    public $paymentRequest = null;

    /**
     * @inheritdoc
     */
    public function run()
    {
        if ($this->paymentRequest instanceof PaymentRequest) {
            $color = self::COLOR_TABLE[$this->paymentRequest->status] ?? self::COLOR_TABLE['default'];
            return "<span style='padding-left: 15px; padding-right:15px; padding-bottom:5px; padding-top:5px; border-radius:15px; background-color:{$color}'>{$this->paymentRequest->statusReadable}</span>";
        } else {
            return '';
        }
    }
}
