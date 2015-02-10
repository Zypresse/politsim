<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Population;

/**
 * PopulationSearch represents the model behind the search form about `\app\models\Population`.
 */
class PopulationSearch extends Population
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'class', 'nation', 'ideology', 'sex', 'count'], 'integer'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function search($params)
    {
        $query = Population::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }

        $query->andFilterWhere([
            'id' => $this->id,
            'class' => $this->class,
            'nation' => $this->nation,
            'ideology' => $this->ideology,
            'sex' => $this->sex,
            'count' => $this->count,
        ]);

        return $dataProvider;
    }
}
