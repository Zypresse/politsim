<?php

namespace app\models;

use Yii,
    app\components\MyModel;

/**
 * Гражданство
 *
 * @property integer $id
 * @property integer $userId
 * @property integer $stateId
 * @property integer $dateCreated
 * @property integer $dateApproved
 * 
 * @property User $user
 * @property State $state
 */
class Citizenship extends MyModel {
    
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'citizenships';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['userId', 'stateId'], 'required'],
            [['userId', 'stateId', 'dateCreated', 'dateApproved'], 'integer', 'min' => 0],
        ];
    }
    
    public function getUser()
    {
        return $this->hasOne(User::className(), array('id' => 'userId'));
    }
    
    public function getState()
    {
        return $this->hasOne(State::className(), array('id' => 'stateId'));
    }
    
    public function beforeSave($insert)
    {
        if ($insert) {
            $this->dateCreated = time();
        }
        return parent::beforeSave($insert);
    }
    
}
