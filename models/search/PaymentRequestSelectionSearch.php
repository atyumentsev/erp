<?php

namespace app\models\search;

use app\models\BankAccount;
use app\models\PaymentRequest;
use app\models\PaymentRequestRanking;
use yii\db\ActiveQuery;

class PaymentRequestSelectionSearch extends PaymentRequestRanking
{
    public $ranking = null;

    public function rules()
    {
        // only fields in rules() are searchable
        return [
            [['name', 'code_1c', 'ranking'], 'safe'],
            [['affiliate_id', 'counteragent_id', 'author_id', 'payer_organization_id', 'bank_account_id'], 'integer'],
        ];
    }

    /**
     * @return ActiveQuery
     */
    public function search()
    {
        $query = PaymentRequestRanking::find()
            ->where(['status' => [PaymentRequest::STATUS_APPROVED, PaymentRequest::STATUS_SELECTED]])
            ->with('originalCurrency')
            ->with('contract')
            ->with('customerDepartment')
            ->with('executorDepartment')
            ->with('payerOrganization')
            ->with('product')
            ->with('cashflowItem')
            ->with('author')
            ->with('counterAgent');

        if (!empty($this->bank_account_id)) {
            $bankAccount = BankAccount::findOne($this->bank_account_id);
            if ($bankAccount) {
                $this->payer_organization_id = $bankAccount->affiliate_id;
            }
        }

        // adjust the query by adding the filters
        $query
            ->andFilterWhere(['like', 'code_1c', $this->code_1c])
            ->andFilterWhere(['author_id' => $this->author_id])
            ->andFilterWhere(['payer_organization_id' => $this->payer_organization_id])
            ->andFilterWhere(['counteragent_id' => $this->counteragent_id]);

        return $query;
    }
}