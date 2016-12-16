<?php

namespace app\models\politics;

use Yii,
    app\models\User,
    app\components\LinkCreator,
    app\models\base\MyActiveRecord;

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
class Citizenship extends MyActiveRecord {
    
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
            [['userId', 'stateId'], 'unique', 'targetAttribute' => ['userId', 'stateId']],
        ];
    }
    
    public static function primaryKey() {
        return ['userId', 'stateId'];
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
    
    /**
     * Подтвердить гражданство
     * @param boolean $save
     */
    public function approve($save = true)
    {
        $this->dateApproved = time();
        if ($save) {
            $this->save();
        }
        
        $this->user->noticy(1, Yii::t('app', 'Now you are a citizenship of ').LinkCreator::stateLink($this->state));
    }
    
    public function fire() {
        $this->user->noticy(2, Yii::t('app', 'You have lost citizenship of ').LinkCreator::stateLink($this->state));        
        return $this->delete();
    }
    
    public function fireSelf() {
        $this->user->noticyReaded(2, Yii::t('app', 'You have lost citizenship of ').LinkCreator::stateLink($this->state));
        return $this->delete();
    }
    
}
