<?php

namespace app\models\search;

use app\models\CounterAgent;
use yii\base\Model;
use yii\data\ActiveDataProvider;

class CounterAgentSearch extends CounterAgent
{
    public function rules()
    {
        // only fields in rules() are searchable
        return [
            [['name', 'code_1c'], 'safe'],
        ];
    }

    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    public function search($params)
    {
        $query = CounterAgent::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        // load the search form data and validate
        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }

        // adjust the query by adding the filters
        $query->andFilterWhere(['like', 'name', $this->name])
            ->andFilterWhere(['like', 'code_1c', $this->code_1c]);

        return $dataProvider;
    }
}