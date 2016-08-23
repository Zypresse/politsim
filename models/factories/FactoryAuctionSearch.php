<?php

namespace app\models\factories;

use yii\data\ActiveDataProvider,
    app\models\factories\Factory,
    app\models\factories\FactoryAuction,
    app\models\Holding,
    app\models\Region,
    app\components\MyModel;

/**
 * FactoryAuctionSearch represents the model behind the search form about `app\models\factories\FactoryAuction`.
 */
class FactoryAuctionSearch extends FactoryAuction
{
    
    public  $factoryName,
            $holdingName,
            $regionName;
    
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'factory_id', 'date_end', 'winner_unnp'], 'integer'],
            [['start_price', 'end_price', 'current_price'], 'number'],
            [['factoryName', 'holdingName', 'regionName'], 'safe']
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
    public function search($params = [], $query = null)
    {
        if (is_null($query)) {
            $query = FactoryAuction::find();
        }

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        /**
        * Setup your sorting attributes
        * Note: This is setup before the $this->load($params) 
        * statement below
        */
        $dataProvider->setSort([
           'attributes' => [
               'id',
               'factoryName' => [
                   'asc' => [Factory::tableName().'.name' => SORT_ASC],
                   'desc' => [Factory::tableName().'.name' => SORT_DESC],
                   'label' => 'Factory Name'
               ],
               'holdingName' => [
                   'asc' => [Holding::tableName().'.name' => SORT_ASC],
                   'desc' => [Holding::tableName().'.name' => SORT_DESC],
                   'label' => 'Holding Name'
               ],
               'regionName' => [
                   'asc' => [Region::tableName().'.name' => SORT_ASC],
                   'desc' => [Region::tableName().'.name' => SORT_DESC],
                   'label' => 'Region Name'
               ],
               'current_price',
               'end_price',
               'date_end'
           ]
       ]);
        
         if (!($this->load($params) && $this->validate())) {
            /**
             * The following line will allow eager loading with country data 
             * to enable sorting by country on initial loading of the grid.
             */ 
            $query->joinWith(['factory']);
            $query->joinWith(['factory.holding']);
            $query->joinWith(['factory.region']);
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
        
        $query->joinWith(['factory' => function (ActiveDataProvider $q) {
            $q->where(Factory::tableName().'.name LIKE "%' . $this->factoryName . '%"');
        }])->joinWith(['factory.holding' => function (ActiveDataProvider $q) {
            $q->where(Holding::tableName().'.name LIKE "%' . $this->holdingName . '%"');
        }])->joinWith(['factory.region' => function (ActiveDataProvider $q) {
            $q->where(Region::tableName().'.name LIKE "%' . $this->regionName. '%"');
        }]);

        return $dataProvider;
    }
}