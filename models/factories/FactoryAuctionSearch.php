<?php

namespace app\models\factories;

use yii\data\ActiveDataProvider,
    app\models\factories\FactoryAuction,
    app\components\MyModel;

/**
 * FactoryAuctionSearch represents the model behind the search form about `app\models\factories\FactoryAuction`.
 */
class FactoryAuctionSearch extends FactoryAuction
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'factory_id', 'date_end', 'winner_unnp'], 'integer'],
            [['start_price', 'end_price', 'current_price'], 'number'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return MyModel::scenarios();
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
        $query = FactoryAuction::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        if (!isset($params['sort'])) {
            $params['sort'] = '-date_end';
        }
        
        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        $query->andFilterWhere([
            'id' => $this->id,
            'factory_id' => $this->factory_id,
            'date_end' => $this->date_end,
            'start_price' => $this->start_price,
            'current_price' => $this->current_price,
            'end_price' => $this->end_price,
            'winner_unnp' => $this->winner_unnp,
        ]);

        return $dataProvider;
    }
}