<?php

namespace app\models;

use app\components\MyModel;

/**
 * Заявка на выборы. Таблица "elect_requests".
 *
 * @property integer $id
 * @property integer $org_id
 * @property integer $party_id
 * @property integer $candidat
 * @property integer $leader Выборы лидера - 1, выборы участников - 2
 */
class ElectRequest extends MyModel
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'elect_requests';
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
            'id' => 'ID',
            'org_id' => 'Org ID',
            'party_id' => 'Party ID',
            'candidat' => 'Candidat',
            'leader' => '1 — заявка на выборы лидера, 0 — на выборы в организаицю',
        ];
    }

    public function getOrg()
    {
        return $this->hasOne('app\models\Org', array('id' => 'org_id'));
    }
    public function getParty()
    {
        return $this->hasOne('app\models\Party', array('id' => 'party_id'));
    }
    public function getUser()
    {
        return $this->hasOne('app\models\User', array('id' => 'candidat'));
    }
    public function getVotes()
    {
        return $this->hasMany('app\models\ElectVote', array('request_id' => 'id'));
    }
}
