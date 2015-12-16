<?php

namespace app\models;

use app\components\MyModel,
    app\models\User,
    app\models\ElectRequest;

/**
 * Голос на выборах. Таблица "elects_votes".
 *
 * @property integer $id
 * @property integer $uid
 * @property integer $request_id
 * 
 * @property User $user
 * @property ElectRequest $request
 */
class ElectVote extends MyModel
{

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'elects_votes';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['uid', 'request_id'], 'required'],
            [['uid', 'request_id'], 'integer'],
            [['uid', 'request_id'], 'unique', 'targetAttribute' => ['uid', 'request_id'], 'message' => 'The combination of Uid and Request ID has already been taken.']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id'         => 'ID',
            'uid'        => 'Uid',
            'request_id' => 'Request ID',
        ];
    }

    public function getUser()
    {
        return $this->hasOne(User::className(), array('id' => 'uid'));
    }

    public function getRequest()
    {
        return $this->hasOne(ElectRequest::className(), array('id' => 'request_id'));
    }

}
