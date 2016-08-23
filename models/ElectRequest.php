<?php

namespace app\models;

use app\components\MyModel,
    app\models\Org,
    app\models\Party,
    app\models\User,
    app\models\ElectVote;

/**
 * Заявка на выборы. Таблица "elects_requests".
 *
 * @property integer $id
 * @property integer $org_id
 * @property integer $party_id
 * @property integer $candidat
 * @property integer $leader Выборы лидера - 1, выборы участников - 2
 * 
 * @property Org $org
 * @property Party $party
 * @property User $user
 * @property ElectVote[] $votes
 */
class ElectRequest extends MyModel
{

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'elects_requests';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['org_id'], 'required'],
            [['org_id', 'party_id', 'candidat', 'leader'], 'integer']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id'       => 'ID',
            'org_id'   => 'Org ID',
            'party_id' => 'Party ID',
            'candidat' => 'Candidat',
            'leader'   => '1 — заявка на выборы лидера, 0 — на выборы в организаицю',
        ];
    }

    public function getOrg()
    {
        return $this->hasOne(Org::className(), array('id' => 'org_id'));
    }

    public function getParty()
    {
        return $this->hasOne(Party::className(), array('id' => 'party_id'));
    }

    public function getUser()
    {
        return $this->hasOne(User::className(), array('id' => 'candidat'));
    }

    public function getVotes()
    {
        return $this->hasMany(ElectVote::className(), array('request_id' => 'id'));
    }

}
