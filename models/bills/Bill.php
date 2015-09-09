<?php

namespace app\models\bills;

use app\components\MyModel;

/**
 * Законопроект. Таблица "bills".
 *
 * @property integer $id
 * @property integer $prototype_id
 * @property integer $creator
 * @property integer $created
 * @property integer $vote_ended
 * @property integer $accepted
 * @property integer $dicktator
 * @property integer $state_id
 * @property string $data
 * 
 * @property \app\models\BillVote[] $votes Голоса
 * @property proto\BillProto $proto Тип законопроекта
 * @property \app\models\State $state Государство
 * @property \app\models\User $user Автор законопроекта
 */
class Bill extends MyModel
{

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'bills';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['prototype_id', 'creator', 'created', 'vote_ended'], 'required'],
            [['prototype_id', 'creator', 'created', 'vote_ended', 'accepted', 'dicktator', 'state_id'], 'integer'],
            [['data'], 'string']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id'         => 'ID',
            'prototype_id'  => 'Bill Type',
            'creator'    => 'Creator',
            'created'    => 'Created',
            'vote_ended' => 'Vote Ended',
            'accepted'   => 'Accepted',
            'data'       => 'Данные',
            'dicktator'  => 'Издан диктатором',
            'state_id'   => 'State ID'
        ];
    }

    public function getVotes()
    {
        return $this->hasMany('app\models\bills\BillVote', array('bill_id' => 'id'));
    }

    public function getProto()
    {
        return proto\BillProto::findByPk($this->prototype_id);
    }

    public function getState()
    {
        return $this->hasOne('app\models\State', array('id' => 'state_id'));
    }

    public function getCreatorUser()
    {
        return $this->hasOne('app\models\User', array('id' => 'creator'));
    }

    /**
     * Принимает законопроект и выполняет его
     * @return boolean
     */
    public function accept()
    {
        return $this->proto->accept($this);
    }

    /**
     * Завершить голосование по законопроекту без принятия
     */
    public function end()
    {
        $this->accepted = -1;
        $this->save();
    }

}
