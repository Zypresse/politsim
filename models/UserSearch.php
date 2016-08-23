<?php

namespace app\models;

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
            [['genderId', 'locationId', 'ideologyId', 'religionId', 'dateCreated', 'dateLastLogin', 'utr', 'fame', 'trust', 'success', 'fameBase', 'trustBase', 'successBase'], 'integer'],
            [['name', 'avatar', 'avatarBig'], 'safe'],
            [['isInvited'], 'boolean'],
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
            return $dataProvider;
        }

        $query->andFilterWhere([
            'id' => $this->id, 
            'genderId' => $this->genderId,
            'locationId' => $this->locationId,
            'ideologyId' => $this->ideologyId,
            'religionId' => $this->religionId,
            'fame' => $this->fame,
            'trust' => $this->trust,
            'success' => $this->success,
            'fameBase' => $this->fameBase,
            'trustBase' => $this->trustBase,
            'successBase' => $this->successBase,
            'dateCreated' => $this->dateCreated,
            'dateLastLogin' => $this->dateLastLogin,
            'isInvited' => $this->isInvited,
            'utr' => $this->utr,
        ]);

        $query->andFilterWhere(['like', 'name', $this->name])
            ->andFilterWhere(['like', 'avatar', $this->avatar])
            ->andFilterWhere(['like', 'avatarBig', $this->avatarBig]);

        return $dataProvider;
    }
}
