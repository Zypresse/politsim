<?php

namespace app\models;

use Yii,
    app\components\MyModel;

/**
 * @property integer $userId
 * @property integer $partyId
 * @property integer $dateCreated
 * @property integer $dateApproved
 * 
 * @property User $user
 * @property Party $party
 * 
 */
class Membership extends MyModel
{
    
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'memberships';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['userId', 'partyId'], 'required'],
            [['userId', 'partyId', 'dateCreated', 'dateApproved'], 'integer', 'min' => 0],
        ];
    }
    
    public static function primaryKey() {
        return ['userId', 'partyId'];
    }
    
    public function beforeSave($insert) {
        
        if ($insert) {
            $this->dateCreated = time();
        }
        
        return parent::beforeSave($insert);
    }
    
    public function getUser()
    {
	return $this->hasOne(User::classname(), ['id' => 'userId']);
    }
    
    public function getParty()
    {
	return $this->hasOne(Party::classname(), ['id' => 'partyId']);
    }
    
    /**
     * Подтвердить 
     * @param boolean $save
     */
    public function approve($save = true)
    {
        $this->dateApproved = time();
        if ($save) {
            $this->save();
        }
        
        $this->user->noticy(3, Yii::t('app', 'Now you are a membership of '.\app\components\LinkCreator::partyLink($this->party)));
    }
    
    public function fire() {
        $this->user->noticy(4, Yii::t('app', 'You have lost membership of '.\app\components\LinkCreator::partyLink($this->party)));        
        return $this->delete();
    }
    
    public function fireSelf() {
        $this->user->noticyReaded(4, Yii::t('app', 'You have lost membership of '.\app\components\LinkCreator::partyLink($this->party)));
        return $this->delete();
    }
}
