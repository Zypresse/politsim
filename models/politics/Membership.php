<?php

namespace app\models\politics;

use Yii,
    app\models\base\MyActiveRecord,
    app\models\User,
    app\components\LinkCreator;

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
class Membership extends MyActiveRecord
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
            if ($this->save()) {
                Yii::$app->notificator->membershipApprouved($this->userId, $this->party);
                return true;
            } else {
                return false;
            }
        }
    }
    
    /**
     * 
     * @param boolean $self
     * @return boolean
     */
    public function fire($self = false) {
        $userId = $this->userId;
        $party = $this->party;
        if ($this->delete()) {
            Yii::$app->notificator->membershipLost($userId, $party, $self);
            return true;
        } else {
            return false;
        }
    }
    
    /**
     * 
     * @return boolean
     */
    public function fireSelf() {
        return $this->fire(true);
    }
}
