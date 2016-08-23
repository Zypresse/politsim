<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\User;

/**
 * UserSearch represents the model behind the search form about `app\models\User`.
 */
class UserSearch extends User
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'last_vote', 'last_tweet', 'party_id', 'state_id', 'post_id', 'region_id', 'sex', 'invited', 'ideology_id', 'utr'], 'integer'],
            [['name', 'photo', 'photo_big', 'twitter_nickname'], 'safe'],
            [['money', 'star', 'heart', 'chart_pie'], 'number'],
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
        $query = User::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        $query->andFilterWhere([
            'id' => $this->id,
            'last_vote' => $this->last_vote,
            'last_tweet' => $this->last_tweet,
            'party_id' => $this->party_id,
            'state_id' => $this->state_id,
            'post_id' => $this->post_id,
            'region_id' => $this->region_id,
            'money' => $this->money,
            'sex' => $this->sex,
            'star' => $this->star,
            'heart' => $this->heart,
            'chart_pie' => $this->chart_pie,
            'invited' => $this->invited,
            'ideology_id' => $this->ideology_id,
            'utr' => $this->utr,
        ]);

        $query->andFilterWhere(['like', 'name', $this->name])
            ->andFilterWhere(['like', 'photo', $this->photo])
            ->andFilterWhere(['like', 'photo_big', $this->photo_big])
            ->andFilterWhere(['like', 'twitter_nickname', $this->twitter_nickname]);

        return $dataProvider;
    }
}
