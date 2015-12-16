<?php

namespace app\models\bills;

use app\components\MyModel,
    app\models\bills\BillVote,
    app\models\bills\proto\BillProto,
    app\models\State,
    app\models\User;

/**
 * Законопроект. Таблица "bills".
 *
 * @property integer $id
 * @property integer $proto_id
 * @property integer $creator
 * @property integer $created
 * @property integer $vote_ended
 * @property integer $accepted
 * @property integer $dicktator
 * @property integer $state_id
 * @property string $data
 * 
 * @property BillVote[] $votes Голоса
 * @property BillProto $proto Тип законопроекта
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
            [['proto_id', 'creator', 'created', 'vote_ended'], 'required'],
            [['proto_id', 'creator', 'created', 'vote_ended', 'accepted', 'dicktator', 'state_id'], 'integer'],
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
            'proto_id'   => 'Bill Type',
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
        return $this->hasMany(BillVote::className(), array('bill_id' => 'id'));
    }

    public function getProto()
    {
        return BillProto::findByPk($this->proto_id);
    }

    public function getState()
    {
        return $this->hasOne(State::className(), array('id' => 'state_id'));
    }

    public function getCreatorUser()
    {
        return $this->hasOne(User::className(), array('id' => 'creator'));
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
