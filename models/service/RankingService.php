<?php

namespace app\models\service;

use app\models\DueDate;
use app\models\PaymentRequest;
use app\models\PaymentRequestRanking;
use app\models\Ranking;

class RankingService
{
    const RANKING_PARAMS = [
        Ranking::ENTITY_TYPE_CASHFLOW_ITEM => 'cashflow_item_id',
        Ranking::ENTITY_TYPE_USER => 'author_id',
        Ranking::ENTITY_TYPE_CUST_DEPARTMENT => 'customer_department_id',
        Ranking::ENTITY_TYPE_COUNTERAGENT => 'counteragent_id',
        Ranking::ENTITY_TYPE_URGENCY => 'urgency',
    ];

    /** @var  PaymentRequest[] */
    private $payment_requests = [];
    /** @var array */
    private $ranking_hash = [];

    public static function getAvailableRankings() : array
    {
        $ret = Ranking::getEntityTypes();
        $ret['due_date'] = \Yii::t('app', 'Due Date');
        return $ret;
    }

    public function init(array $payment_requests)
    {
        $this->payment_requests = $payment_requests;
        $rankings = Ranking::find()->all();
        /** @var Ranking $ranking */
        foreach ($rankings as $ranking) {
            $this->ranking_hash[$ranking->entity_type][$ranking->entity_id] = $ranking->priority;
        }
        foreach (array_keys(DueDate::getList()) as $days_cnt) {
            if (!isset($this->ranking_hash[Ranking::ENTITY_TYPE_DUE_DATE][$days_cnt])) {
                $this->ranking_hash[Ranking::ENTITY_TYPE_DUE_DATE][$days_cnt] = 0;
            }
        }
        ksort($this->ranking_hash[Ranking::ENTITY_TYPE_DUE_DATE]);
    }

    public function getRanked($ranking = null) : array
    {
        //
        // set rank
        //
        /** @var PaymentRequestRanking $payment_request */
        foreach ($this->payment_requests as $payment_request) {
            $payment_request->rank = 0;

            // due_date
            if ($ranking === null || $ranking === 'due_date') {
                $days_left = floor((strtotime($payment_request->due_date) - strtotime(date('Y-m-d'))) / 86400);
                $rank = 0;
                foreach ($this->ranking_hash[Ranking::ENTITY_TYPE_DUE_DATE] as $days_cnt => $_rank) {
                    if ($days_left <= $days_cnt) {
                        $rank = $_rank;
                        $payment_request->rank_explain[Ranking::ENTITY_TYPE_DUE_DATE] = $rank;
                        break;
                    }
                }
                $payment_request->rank += $rank;
            }

            // add rank according to ranking table
            foreach (self::RANKING_PARAMS as $entity_type => $key) {
                if (($ranking === null || $ranking == $entity_type)
                    && isset($this->ranking_hash[$entity_type][$payment_request->$key])) {
                    $rank = $this->ranking_hash[$entity_type][$payment_request->$key];
                    $payment_request->rank += $rank;
                    $payment_request->rank_explain[$entity_type] = $rank;
                }
            }
        }

        //
        // sort by rank
        //

        usort($this->payment_requests, function(PaymentRequestRanking $a, PaymentRequestRanking $b) {
            return $b->rank <=> $a->rank;
        });

        return $this->payment_requests;
    }
}