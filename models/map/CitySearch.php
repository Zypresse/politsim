<?php

namespace app\models\map;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\map\City;

/**
 * CitySearch represents the model behind the search form of `app\models\map\City`.
 */
class CitySearch extends City
{
    
    public $tilesCount;
    
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'utr'], 'integer'],
            [['name', 'nameShort'], 'safe'],
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
        $query = self::find()
                ->select(['cities.*', 'COUNT(tiles.id) AS "tilesCount"'])
                ->leftJoin('tiles', 'tiles."cityId" = cities.id')
                ->groupBy("cities.id");
        
        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);
        $dataProvider->setSort([
            'attributes' => [
                'id',
                'name',
                'nameShort',
                'utr',
                'population',
                'tilesCount' => [
                    'asc' => ['tilesCount' => SORT_ASC ],
                    'desc' => ['tilesCount' => SORT_DESC],
                    'default' => SORT_ASC
                ],          
            ]
        ]);
        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'regionId' => $this->regionId,
            'population' => $this->population,
            'usersCount' => $this->usersCount,
            'usersFame' => $this->usersFame,
            'utr' => $this->utr,
        ]);

        $query->andFilterWhere(['ilike', 'name', $this->name])
            ->andFilterWhere(['ilike', 'nameShort', $this->nameShort])
            ->andFilterWhere(['ilike', 'flag', $this->flag])
            ->andFilterWhere(['ilike', 'anthem', $this->anthem]);

        return $dataProvider;
    }
    
    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return array_merge(parent::attributeLabels(), [
            'tilesCount' => 'Число тайлов',
        ]);
    }
}
