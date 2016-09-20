<?php

namespace app\models;

use Yii,
    app\components\MyModel;

/**
 * Кусок конституции привязанный к посту
 * 
 * @property integer $postId
 * @property integer $assignmentRule способ назначения
 * @property integer $powers полномочия постов (bitmask)
 * @property integer $termOfOffice срок полномочий 
 * @property integer $termOfElections длительность выборов
 * @property integer $termOfElectionsRegistration длительность регистрации на выборы
 * @property integer #electionsRules доп настройки выборов (bitmask)
 * @property integer $electoralDistrict округ к которому привязан пост
 * 
 * @property Post $post
 * @property AgencyPostConstitutionLicense[] $licenses
 * 
 */
class AgencyPostConstitution extends MyModel
{
    
    /**
     * наличие второго тура
     */
    const ELECTIONS_RULE_SECOND_TOUR = 1;
    
    /**
     * право участия в выборах самовыдвиженцев
     */
    const ELECTIONS_RULE_ALLOW_SELFREQUESTS = 2;
    
        
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'constitutions-agencies-posts';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['postId', 'assignmentRule', 'powers', 'termOfOffice', 'termOfElections', 'termOfElectionsRegistration', 'electionsRules'], 'required'],
            [['postId', 'assignmentRule', 'powers', 'termOfOffice', 'termOfElections', 'termOfElectionsRegistration', 'electionsRules', 'electoralDistrict'], 'integer', 'min' => 0],
            [['postId'], 'unique'],
            [['postId'], 'exist', 'skipOnError' => true, 'targetClass' => AgencyPost::className(), 'targetAttribute' => ['postId' => 'id']],
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
            'assignmentRule' => AgencyConstitution::ASSIGNMENT_RULE_BY_LEADER,
            'powers' => 0,
            'termOfOffice' => 0,
            'termOfElections' => 0,
            'termOfElectionsRegistration' => 0,
            'electionsRules' => 0,            
        ]);
    }
    
    public static function primaryKey()
    {
        return ['postId'];
    }
        
    public function getPost()
    {
        return $this->hasOne(AgencyPost::className(), ['id' => 'postId']);
    }
    
    public function getLicenses()
    {
        return $this->hasMany(AgencyPostConstitutionLicense::className(), ['postId' => 'postId']);
    }
        
}
