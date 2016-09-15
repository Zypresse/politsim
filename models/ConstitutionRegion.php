<?php

namespace app\models;

use Yii,
    app\components\MyModel;

/**
 * Конституция региона
 * 
 * @property integer $regionId
 * @property integer $leaderPostId
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
 * 
 * @property Region $region
 * @property Post $leaderPost
 * 
 */
class ConstitutionRegion extends MyModel
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'constitutions-regions';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['regionId', 'localBusinessPolicy', 'foreignBusinessPolicy', 'npcBusinessPolicy'], 'required'],
            [['regionId', 'leaderPostId', 'localBusinessPolicy', 'foreignBusinessPolicy', 'npcBusinessPolicy', 'retirementAge'], 'integer', 'min' => 0],
            [['localBusinessRegistrationTax', 'localBusinessMinCapital', 'localBusinessMaxCapital', 'foreignBusinessRegistrationTax', 'foreignBusinessMinCapital', 'foreignBusinessMaxCapital', 'npcBusinessRegistrationTax', 'npcBusinessMinCapital', 'npcBusinessMaxCapital', 'minWage'], 'number', 'min' => 0],
            [['regionId'], 'unique'],
//            [['leaderPostId'], 'exist', 'skipOnError' => true, 'targetClass' => Post::className(), 'targetAttribute' => ['leaderPostId' => 'id']],
            [['regionId'], 'exist', 'skipOnError' => true, 'targetClass' => Region::className(), 'targetAttribute' => ['regionId' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'leaderPostId' => Yii::t('app', 'Leader post'),
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
        ];
    }
    
    public static function generate(Constitution $stateConstitution)
    {
        return new static([
            'localBusinessPolicy' => $stateConstitution->localBusinessPolicy,
            'localBusinessRegistrationTax' => $stateConstitution->localBusinessRegistrationTax,
            'localBusinessMinCapital' => $stateConstitution->localBusinessMinCapital,
            'localBusinessMaxCapital' => $stateConstitution->localBusinessMaxCapital,
            'foreignBusinessPolicy' => $stateConstitution->foreignBusinessPolicy,
            'foreignBusinessRegistrationTax' => $stateConstitution->foreignBusinessRegistrationTax,
            'foreignBusinessMinCapital' => $stateConstitution->foreignBusinessMinCapital,
            'foreignBusinessMaxCapital' => $stateConstitution->foreignBusinessMaxCapital,
            'npcBusinessPolicy' => $stateConstitution->npcBusinessPolicy,
            'npcBusinessRegistrationTax' => $stateConstitution->npcBusinessRegistrationTax,
            'npcBusinessMinCapital' => $stateConstitution->npcBusinessMinCapital,
            'npcBusinessMaxCapital' => $stateConstitution->npcBusinessMaxCapital,
            'minWage' => $stateConstitution->minWage,
            'retirementAge' => $stateConstitution->retirementAge,
        ]);
    }
    
    public static function primaryKey()
    {
        return 'regionId';
    }
    
    public function getRegion()
    {
        return $this->hasOne(Region::className(), ['id' => 'regionId']);
    }
    
    public function getLeaderPost()
    {
        return $this->hasOne(Post::className(), ['id' => 'leaderPostId']);
    }
}
