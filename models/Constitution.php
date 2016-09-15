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
 * @property integer $centralBankId
 * @property integer $currencyId
 * @property boolean $isAllowSetExchangeRateManually
 * @property double $taxBase
 * @property integer $localBusinessPolicy
 * @property double $localBusinessRegistrationTax
 * @property double $localBusinessMinCapital
 * @property double $localBusinessMaxCapital
 * @property integer $foreignBusinessPolicy
 * @property double $foreignBusinessRegistrationTax
 * @property double $foreignBusinessMinCapital
 * @property double $foreignBusinessMaxCapital
 * @property integer $npcBusinessPolicy
 * @property double $npcBusinessRegistrationTax
 * @property double $npcBusinessMinCapital
 * @property double $npcBusinessMaxCapital
 * @property double $minWage
 * @property integer $retirementAge
 * @property integer $religionId
 * 
 * @property State $state
 * @property Party $rulingParty
 * @property Post $leaderPost
 * @property Company $centralBank
 * @property Currency $currency
 * @property Religion $religion
 */
class Constitution extends MyModel
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
     * запрещено владение бизнесом
     */
    const BUISNESS_FORBIDDEN = 0;
    
    /**
     * разрешено владение бизнесом, запрещена регистрация компаний
     */
    const BUISNESS_ALLOW_REGISTERED = 1;
    
    /**
     * разрешено владение и регистрация компаний
     */
    const BUISNESS_FREE = 2;
    
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
            [['stateId', 'partyPolicy', 'isAllowMultipost', 'localBusinessPolicy', 'foreignBusinessPolicy', 'npcBusinessPolicy'], 'required'],
            [['stateId', 'partyPolicy', 'rulingPartyId', 'leaderPostId', 'centralBankId', 'currencyId', 'localBusinessPolicy', 'foreignBusinessPolicy', 'npcBusinessPolicy', 'retirementAge', 'religionId'], 'integer', 'min' => 0],
            [['partyRegistrationTax', 'taxBase', 'localBusinessRegistrationTax', 'localBusinessMinCapital', 'localBusinessMaxCapital', 'foreignBusinessRegistrationTax', 'foreignBusinessMinCapital', 'foreignBusinessMaxCapital', 'npcBusinessRegistrationTax', 'npcBusinessMinCapital', 'npcBusinessMaxCapital', 'minWage'], 'number', 'min' => 0],
            [['isAllowMultipost', 'isAllowSetExchangeRateManually'], 'boolean'],
            [['stateId'], 'unique'],
//            [['currencyId'], 'exist', 'skipOnError' => true, 'targetClass' => Currency::className(), 'targetAttribute' => ['currencyId' => 'id']],
//            [['centralBankId'], 'exist', 'skipOnError' => true, 'targetClass' => Company::className(), 'targetAttribute' => ['centralBankId' => 'id']],
//            [['leaderPostId'], 'exist', 'skipOnError' => true, 'targetClass' => Post::className(), 'targetAttribute' => ['leaderPostId' => 'id']],
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
            'localBusinessPolicy' => Yii::t('app', 'Local business policy'),
            'localBusinessRegistrationTax' => Yii::t('app', 'Local business registration tax'),
            'localBusinessMinCapital' => Yii::t('app', 'Local business minimum starting capital'),
            'localBusinessMaxCapital' => Yii::t('app', 'Local business maximum starting capital'),
            'foreignBusinessPolicy' => Yii::t('app', 'Foreign business policy'),
            'foreignBusinessRegistrationTax' => Yii::t('app', 'Foreign business registration tax'),
            'foreignBusinessMinCapital' => Yii::t('app', 'Foreign business minimum starting capital'),
            'foreignBusinessMaxCapital' => Yii::t('app', 'Foreign business maximum starting capital'),
            'npcBusinessPolicy' => Yii::t('app', 'Npc business policy'),
            'npcBusinessRegistrationTax' => Yii::t('app', 'Npc business registration tax'),
            'npcBusinessMinCapital' => Yii::t('app', 'Npc business minimum starting capital'),
            'npcBusinessMaxCapital' => Yii::t('app', 'Npc business maximum starting capital'),
            'minWage' => Yii::t('app', 'Minimum wage'),
            'retirementAge' => Yii::t('app', 'Retirement age'),
            'stateReligionId' => Yii::t('app', 'State religion'),
        ];
    }
    
    public static function generate()
    {
        return new static([
            'partyPolicy' => static::PARTY_POLICY_FREE,
            'isAllowMultipost' => false,
            'localBusinessPolicy' => static::BUISNESS_FREE, 
            'foreignBusinessPolicy' => static::BUISNESS_FREE, 
            'npcBusinessPolicy' => static::BUISNESS_FREE
        ]);
    }
    
    public static function primaryKey()
    {
        return 'stateId';
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
        return $this->hasOne(Post::className(), ['id' => 'leaderPostId']);
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
    
}
