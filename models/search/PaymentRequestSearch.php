<?php

namespace app\models\search;

use app\models\PaymentRequest;

class PaymentRequestSearch extends PaymentRequest
{
    public function rules()
    {
        // only fields in rules() are searchable
        return [
            [['name', 'code_1c'], 'safe'],
            [['affiliate_id', 'counteragent_id', 'author_id'], 'integer'],
        ];
    }

    public function search()
    {
        $query = PaymentRequest::find()
            ->with('originalCurrency')
            ->with('contract')
            ->with('customerDepartment')
            ->with('executorDepartment')
            ->with('payerOrganization')
            ->with('product')
            ->with('cashflowItem')
            ->with('author')
            ->with('counterAgent');

        // adjust the query by adding the filters
        $query
            ->andFilterWhere(['like', 'code_1c', $this->code_1c])
            ->andFilterWhere(['author_id' => $this->author_id])
            ->andFilterWhere(['counteragent_id' => $this->counteragent_id])
            ->orderBy('payment_request.id DESC');

        return $query;
    }
}