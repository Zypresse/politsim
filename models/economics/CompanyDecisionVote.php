<?php

namespace app\models\economics;

use Yii,
    app\models\base\MyActiveRecord;

/**
 * Голос по решению компании
 *
 * @property integer $decisionId
 * @property integer $shareholderId
 * @property integer $variant
 * 
 * @property CompanyDecision $decision
 * @property TaxPayer $shareholder
 * 
 */
class CompanyDecisionVote extends MyActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'companiesDecisionsVotes';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['decisionId', 'shareholderId', 'variant'], 'required'],
            [['decisionId', 'shareholderId', 'variant'], 'integer', 'min' => 0],
            [['decisionId', 'shareholderId'], 'unique', 'targetAttribute' => ['decisionId', 'shareholderId'], 'message' => 'The combination of Decision ID and Shareholder ID has already been taken.'],
            [['shareholderId'], 'exist', 'skipOnError' => true, 'targetClass' => Utr::className(), 'targetAttribute' => ['shareholderId' => 'id']],
            [['decisionId'], 'exist', 'skipOnError' => true, 'targetClass' => CompanyDecision::className(), 'targetAttribute' => ['decisionId' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'decisionId' => Yii::t('app', 'Decision ID'),
            'shareholderId' => Yii::t('app', 'Shareholder ID'),
            'variant' => Yii::t('app', 'Variant'),
        ];
    }
    
    public static function primaryKey()
    {
        return ['decisionId', 'shareholderId'];
    }
    
    public function getDecision()
    {
        return $this->hasOne(CompanyDecision::className(), ['id' => 'decisionId']);
    }
    
    public function getShareholder()
    {
        return $this->hasOne(Utr::className(), ['id' => 'shareholderId'])->one()->getObject;
    }
    
}
