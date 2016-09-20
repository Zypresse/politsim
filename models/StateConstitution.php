<?php

namespace app\models;

use Yii,
    app\components\MyModel;

/**
 * Конституция государства
 *
 * @property integer $stateId
 * @property integer $partyPolicy
 * @property integer $rulingPartyId
 * @property double $partyRegistrationTax
 * @property boolean $isAllowMultipost
 * @property integer $leaderPostId
 * @property integer $regionsLeadersAssignmentRule
 * @property integer $centralBankId
 * @property integer $currencyId
 * @property boolean $isAllowSetExchangeRateManually
 * @property double $taxBase
 * @property integer $businessPolicy
 * @property double $minWage
 * @property integer $retirementAge
 * @property integer $religionId
 * 
 * @property State $state
 * @property Party $rulingParty
 * @property AgencyPost $leaderPost
 * @property Company $centralBank
 * @property Currency $currency
 * @property Religion $religion
 * @property StateConstitutionLicense[] $licenses
 * 
 */
class StateConstitution extends MyModel
{
    
    /**
     * запрещена деятельность партий
     */
    const PARTY_POLICY_FORBIDDEN = 0;
    
    /**
     * разрешена деятельность правящей партии, остальные запрещены
     */
    const PARTY_POLICY_ALLOW_ONLY_RULING = 1;
    
    /**
     * разрешена деятельность уже зарегистрированных партий, регистрация новых запрещена
     */
    const PARTY_POLICY_ALLOW_REGISTERED = 2;
    
    /**
     * регистрация новых партий требует подтверждения соотв. министра
     */
    const PARTY_POLICY_NEED_CONFIRM = 3;
    
    /**
     * регистрация партий свободная
     */
    const PARTY_POLICY_FREE = 4;
    
    /**
     * запрещено владение бизнесом всем
     */
    const BUISNESS_FORBIDDEN_ALL = 0;
    
    /**
     * разрешено владение бизнесом, запрещена регистрация компаний всем
     */
    const BUISNESS_ALLOW_REGISTERED_ALL = 1;
    
    /**
     * разрешено владение и регистрация компаний всем
     */
    const BUISNESS_FREE_ALL = 2;
    
    /**
     * запрещено иностранцам, своим разрешено владение, запрещена регистрация 
     */
    const BUISNESS_FORBIDDEN_FOREIGN_ALLOW_REGISTERED_LOCAL = 3;
            
    /**
     * запрещено иностранцам, своим разрешено владение и регистрация
     */
    const BUISNESS_FORBIDDEN_FOREIGN_FREE_LOCAL = 4;
    
    /**
     * запрещена регистрация иностранцам, но разрешено владение, своим разрешено владение и регистрация
     */
    const BUISNESS_ALLOW_REGISTERED_FOREIGN_FREE_LOCAL = 5;
    
    
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'constitutions';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['stateId', 'partyPolicy', 'isAllowMultipost', 'businessPolicy'], 'required'],
            [['stateId', 'partyPolicy', 'rulingPartyId', 'leaderPostId', 'centralBankId', 'currencyId', 'businessPolicy', 'retirementAge', 'religionId', 'regionsLeadersAssignmentRule'], 'integer', 'min' => 0],
            [['partyRegistrationTax', 'taxBase', 'minWage'], 'number', 'min' => 0],
            [['isAllowMultipost', 'isAllowSetExchangeRateManually'], 'boolean'],
            [['stateId'], 'unique'],
//            [['currencyId'], 'exist', 'skipOnError' => true, 'targetClass' => Currency::className(), 'targetAttribute' => ['currencyId' => 'id']],
//            [['centralBankId'], 'exist', 'skipOnError' => true, 'targetClass' => Company::className(), 'targetAttribute' => ['centralBankId' => 'id']],
            [['leaderPostId'], 'exist', 'skipOnError' => true, 'targetClass' => AgencyPost::className(), 'targetAttribute' => ['leaderPostId' => 'id']],
            [['rulingPartyId'], 'exist', 'skipOnError' => true, 'targetClass' => Party::className(), 'targetAttribute' => ['rulingPartyId' => 'id']],
            [['stateId'], 'exist', 'skipOnError' => true, 'targetClass' => State::className(), 'targetAttribute' => ['stateId' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'partyPolicy' => Yii::t('app', 'Party policy'),
            'rulingPartyId' => Yii::t('app', 'Ruling rarty'),
            'partyRegistrationTax' => Yii::t('app', 'Party registration tax'),
            'isAllowMultipost' => Yii::t('app', 'Allow one user joining many posts'),
            'leaderPostId' => Yii::t('app', 'Leader post'),
            'centralBankId' => Yii::t('app', 'Central bank'),
            'currencyId' => Yii::t('app', 'Currency'),
            'isAllowSetExchangeRateManually' => Yii::t('app', 'Allow set exchange rate manually'),
            'taxBase' => Yii::t('app', 'Tax base'),
            'businessPolicy' => Yii::t('app', 'Business policy'),
            'minWage' => Yii::t('app', 'Minimum wage'),
            'retirementAge' => Yii::t('app', 'Retirement age'),
            'stateReligionId' => Yii::t('app', 'State religion'),
            'regionsLeadersAssignmentRule' => Yii::t('app', 'Regions leaders assignment type'),
        ];
    }
    
    public static function generate()
    {
        return new static([
            'partyPolicy' => static::PARTY_POLICY_FREE,
            'isAllowMultipost' => false,
            'businessPolicy' => static::BUISNESS_FREE_ALL,
            'regionsLeadersAssignmentRule' => AgencyConstitution::ASSIGNMENT_RULE_ELECTIONS_PLURARITY,
        ]);
    }
    
    public static function primaryKey()
    {
        return ['stateId'];
    }
    
    public function getState()
    {
        return $this->hasOne(State::className(), ['id' => 'stateId']);
    }
    
    public function getRulingParty()
    {
        return $this->hasOne(Party::className(), ['id' => 'rulingPartyId']);
    }
    
    public function getLeaderPost()
    {
        return $this->hasOne(AgencyPost::className(), ['id' => 'leaderPostId']);
    }
    
    public function getCentralBank()
    {
        return $this->hasOne(Company::className(), ['id' => 'centralBankId']);
    }
    
    private $_religion = null;
    public function getReligion()
    {
        if (is_null($this->_religion)) {
            $this->_religion = Religion::findOne($this->religionId);
        }
        return $this->_religion;
    }
    
    public function getLicenses()
    {
        return $this->hasMany(StateConstitutionLicense::className(), ['stateId' => 'stateId']);
    }
    
}
