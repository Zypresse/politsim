<?php

namespace app\models;

use Yii,
    app\components\MyModel;

/**
 * Кусок конституции привязанный к агенству
 * 
 * @property integer $agencyId
 * @property integer $leaderPostId
 * @property integer $assignmentRule способ назначения членов организации
 * @property integer $powers полномочия постов (bitmask)
 * @property integer $termOfOffice срок полномочий 
 * @property integer $termOfElections длительность выборов
 * @property integer $termOfElectionsRegistration длительность регистрации на выборы
 * @property integer $tempPostsCount число одинаковых постов типа парламентариев
 * @property integer $tempPostId базовый пост для шаблонных
 * 
 * @property Agency $agency
 * @property Post $leaderPost
 * @property AgencyConstitutionLicense[] $licenses
 * 
 */
class AgencyConstitution extends MyModel
{
    
    /**
     * способ назначения членов организации
     * не назначаются
     */
    const ASSIGNMENT_RULE_NOT_SET = 0;
    
    /**
     * назначаются лидером организации
     */
    const ASSIGNMENT_RULE_BY_LEADER = 1;
    
    /**
     * назначаются лидером государства
     */
    const ASSIGNMENT_RULE_BY_STATE_LEADER = 2;
    
    /**
     * назначаются предшественником
     */
    const ASSIGNMENT_RULE_INHERITANCE = 3;
    
    /**
     * выбираются на выборах по партийным спискам
     */
    const ASSIGNMENT_RULE_ELECTIONS_PROPORTIONAL = 4;
    
    /**
     * выбираются на выборах по индивидуальным спискам
     */
    const ASSIGNMENT_RULE_ELECTIONS_PLURARITY = 5;
    
    
    /**
     * полномочия постов (bitmask)
     * право быть "диктатором", единолично принимать законы
     */
    const POWER_BILLS_ACCEPT = 1;
    
    /**
     * право вето по законопроектам
     */
    const POWER_BILLS_VETO = 2;
    
    /**
     * право предлагать законопроекты
     */
    const POWER_BILLS_MAKE = 4;
    
    /**
     * право голосовать по законопроектам
     */
    const POWER_BILLS_VOTE = 8;
    
    /**
     * управление военными юнитами
     */
    const POWER_UNIT_CONTROL = 16;
    
    /**
     * управление бизнесом
     */
    const POWER_BUISNESS_CONTROL = 32;
    
        
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'constitutions-agencies';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['agencyId', 'assignmentRule', 'powers', 'termOfOffice', 'termOfElections', 'termOfElectionsRegistration'], 'required'],
            [['agencyId', 'leaderPostId', 'assignmentRule', 'powers', 'termOfOffice', 'termOfElections', 'termOfElectionsRegistration'], 'integer', 'min' => 0],
            [['agencyId'], 'unique'],
            [['agencyId'], 'exist', 'skipOnError' => true, 'targetClass' => Agency::className(), 'targetAttribute' => ['agencyId' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            
        ];
    }
    
    public static function generate()
    {
        return new static([
            'assignmentRule' => static::ASSIGNMENT_RULE_BY_LEADER,
            'powers' => 0,
            'termOfOffice' => 30,
            'termOfElections' => 1,
            'termOfElectionsRegistration' => 7
        ]);
    }
    
    public static function primaryKey()
    {
        return ['agencyId'];
    }
    
    public function getAgency()
    {
        return $this->hasOne(Agency::className(), ['id' => 'agencyId']);
    }
    
    public function getLeaderPost()
    {
        return $this->hasOne(AgencyPost::className(), ['id' => 'leaderPostId']);
    }
    
    public function getLicenses()
    {
        return $this->hasOne(AgencyConstitutionLicense::className(), ['agencyId' => 'agencyId']);
    }
        
}
