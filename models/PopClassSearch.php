<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\PopClass;

/**
 * PopClassSearch represents the model behind the search form about `\app\models\PopClass`.
 */
class PopClassSearch extends PopClass
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'awareness', 'threshold', 'aggression'], 'integer'],
            [['name', 'work_type'], 'safe'],
            [['food_min_count', 'food_min_type', 'food_max_count', 'food_max_type', 'dress_min_count', 'dress_min_type', 'dress_max_count', 'dress_max_type', 'furniture_min_count', 'furniture_min_type', 'furniture_max_count', 'furniture_max_type', 'alcohol_min_type', 'alcohol_min_count', 'alcohol_max_type', 'alcohol_max_count', 'energy_min', 'energy_max', 'base_speed'], 'number'],
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
        $query = PopClass::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }

        $query->andFilterWhere([
            'id' => $this->id,
            'food_min_count' => $this->food_min_count,
            'food_min_type' => $this->food_min_type,
            'food_max_count' => $this->food_max_count,
            'food_max_type' => $this->food_max_type,
            'dress_min_count' => $this->dress_min_count,
            'dress_min_type' => $this->dress_min_type,
            'dress_max_count' => $this->dress_max_count,
            'dress_max_type' => $this->dress_max_type,
            'furniture_min_count' => $this->furniture_min_count,
            'furniture_min_type' => $this->furniture_min_type,
            'furniture_max_count' => $this->furniture_max_count,
            'furniture_max_type' => $this->furniture_max_type,
            'alcohol_min_type' => $this->alcohol_min_type,
            'alcohol_min_count' => $this->alcohol_min_count,
            'alcohol_max_type' => $this->alcohol_max_type,
            'alcohol_max_count' => $this->alcohol_max_count,
            'energy_min' => $this->energy_min,
            'energy_max' => $this->energy_max,
            'base_speed' => $this->base_speed,
            'awareness' => $this->awareness,
            'threshold' => $this->threshold,
            'aggression' => $this->aggression,
        ]);

        $query->andFilterWhere(['like', 'name', $this->name])
            ->andFilterWhere(['like', 'work_type', $this->work_type]);

        return $dataProvider;
    }
}
