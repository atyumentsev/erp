<?php

namespace app\models\search;

use app\models\Budget;

class BudgetSearch extends Budget
{
    public function rules()
    {
        // only fields in rules() are searchable
        return [
            [['budget_cfr_id'], 'integer'],
        ];
    }

    public function formName()
    {
        return '';
    }
    /*
    public function search($params)
    {
        $query = Contract::find()
            ->with('currency')
            ->with('affiliate')
            ->with('counterAgent');

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        // load the search form data and validate
        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }

        // adjust the query by adding the filters
        $query->andFilterWhere(['like', 'name', $this->name])
            ->andFilterWhere(['like', 'code_1c', $this->code_1c])
            ->andFilterWhere(['affiliate_id' => $this->affiliate_id])
            ->andFilterWhere(['counteragent_id' => $this->counteragent_id]);

        return $dataProvider;
    }
    */
}