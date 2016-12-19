<?php

namespace app\models\politics;

use Yii,
    app\models\User,
    app\models\base\MyActiveRecord;

/**
 * 
 * @property integer $partyId
 * @property integer $userId
 * @property string $name
 * @property string $nameShort
 * @property integer $powers
 * @property integer $appointmentType
 * @property integer $successorId
 * 
 * @property Party $party
 * @property User $user
 * @property User $successor
 * 
 */
class PartyPost extends MyActiveRecord
{
    
    /**
     * изменение основных полей 
     */
    const POWER_CHANGE_FIELDS = 1;
    
    /**
     * создание и редактирование постов
     */
    const POWER_EDIT_POSTS = 2;
    
    /**
     * принятие заявок в партию
     */
    const POWER_APPROVE_REQUESTS = 4;
    
    
    /**
     * назнач лидером
     */
    const APPOINTMENT_TYPE_LEADER = 1;
    
    /**
     * назначается предыдущим владельцем
     */
    const APPOINTMENT_TYPE_INHERITANCE = 2;
    
    /**
     * выбирается на праймериз
     */
    const APPOINTMENT_TYPE_PRIMARIES = 3;
    
    
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'partiesPosts';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['partyId', 'name', 'nameShort', 'powers', 'appointmentType'], 'required'],
            [['userId', 'partyId', 'powers', 'appointmentType', 'successorId'], 'integer', 'min' => 0],
            [['name'], 'string', 'max' => 255],
            [['nameShort'], 'string', 'max' => 6],
        ];
    }
    
    public function attributeLabels() {
        return [
            'name' => Yii::t('app', 'Post name'),
            'nameShort' => Yii::t('app', 'Post short name'),
            'powers' => Yii::t('app', 'Powers'),
            'appointmentType' => Yii::t('app', 'Appointment type'),
        ];
    }
    
    public function getUser()
    {
	return $this->hasOne(User::classname(), ['id' => 'userId']);
    }
    
    public function getParty()
    {
	return $this->hasOne(Party::classname(), ['id' => 'partyId']);
    }
    
    public function getSuccessor()
    {
	return $this->hasOne(User::classname(), ['id' => 'successorId']);
    }
    
    public function isPartyLeader()
    {
        return $this->party->leaderPostId == $this->id;
    }
    
    public function beforeValidate() {
        if (is_array($this->powers)) {
            $powers = 0;
            foreach ($this->powers as $power) {
                $powers += intval($power);
            }
            $this->powers = $powers;
        }
        return parent::beforeValidate();
    }
    
}
