<?php

namespace app\models;

use app\components\MyModel;

/**
 * Законопроект. Таблица "bills".
 *
 * @property integer $id
 * @property integer $bill_type
 * @property integer $creator
 * @property integer $created
 * @property integer $vote_ended
 * @property integer $accepted
 * @property integer $dicktator
 * @property integer $state_id
 * 
 * @property BillVote[] $votes Голоса
 * @property BillType $type Тип законопроекта
 * @property State $state Государство
 * @property User $user Автор законопроекта
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
            [['bill_type', 'creator', 'created', 'vote_ended'], 'required'],
            [['bill_type', 'creator', 'created', 'vote_ended', 'accepted', 'dicktator', 'state_id'], 'integer'],
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
            'bill_type'  => 'Bill Type',
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
        return $this->hasMany('app\models\BillVote', array('bill_id' => 'id'));
    }

    public function getType()
    {
        return $this->hasOne('app\models\BillType', array('id' => 'bill_type'));
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
        return $this->type->accept($this);
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
