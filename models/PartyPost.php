<?php

namespace app\models;

use Yii,
    app\components\MyModel;

/**
 * 
 * 'partyId' => 'UNSIGNED INTEGER REFERENCES parties(id) NOT NULL',
    'userId' => 'UNSIGNED INTEGER REFERENCES users(id) DEFAULT NULL',
    'name' => 'VARCHAR(255) NOT NULL',
    'nameShort' => 'VARCHAR(6) NOT NULL',
    // bitmask
    // 1 изменение основных полей 
    // 2 создание и редактирование постов
    // 4 принятие заявок в партию
    'powers' => 'UNSIGNED INTEGER(3) NOT NULL',
    // 1 назнач лидером
    // 2 назначается предыдущим владельцем
    // 3 выбирается на праймериз
    'appointmentType' => 'UNSIGNED INTEGER(1) NOT NULL',
    'successorId' => 'UNSIGNED INTEGER REFERENCES users(id) DEFAULT NULL'
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
class PartyPost extends MyModel
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
        return 'parties-posts';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['partyId', 'name', 'nameShort', 'powers', 'appointmentType'], 'required'],
            [['userId', 'partyId', 'powers', 'appointmentType', 'successorId'], 'integer', 'min' => 0],
            [['name', 'nameShort'], 'string', 'max' => 255],
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
    
}
