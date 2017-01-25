<?php

namespace app\models\economics;

use Yii,
    app\models\base\MyActiveRecord;

/**
 * Решение компании
 *
 * @property integer $id
 * @property integer $companyId
 * @property integer $protoId
 * @property integer $initiatorId
 * @property string $data
 * @property integer $dateCreated
 * @property integer $dateVotingFinished
 * @property integer $dateFinished
 * @property boolean $isApproved
 * @property integer $votesPlus
 * @property integer $votesAbstain
 * @property integer $votesMinus
 * 
 * @property Company $company
 * @property TaxPayer $initiator
 * 
 */
class CompanyDecision extends MyActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'companiesDecisions';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['companyId', 'protoId', 'dateCreated', 'dateVotingFinished'], 'required'],
            [['companyId', 'protoId', 'initiatorId', 'data', 'dateCreated', 'dateVotingFinished', 'dateFinished', 'votesPlus', 'votesAbstain', 'votesMinus'], 'integer', 'min' => 0],
            [['isApproved'], 'boolean'],
            [['initiatorId'], 'exist', 'skipOnError' => true, 'targetClass' => Utr::className(), 'targetAttribute' => ['initiatorId' => 'id']],
            [['companyId'], 'exist', 'skipOnError' => true, 'targetClass' => Company::className(), 'targetAttribute' => ['companyId' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'companyId' => Yii::t('app', 'Company ID'),
            'protoId' => Yii::t('app', 'Proto ID'),
            'initiatorId' => Yii::t('app', 'Initiator ID'),
            'data' => Yii::t('app', 'Data'),
            'dateCreated' => Yii::t('app', 'Date Created'),
            'dateVotingFinished' => Yii::t('app', 'Date Voting Finished'),
            'dateFinished' => Yii::t('app', 'Date Finished'),
            'isApproved' => Yii::t('app', 'Is Approved'),
            'votesPlus' => Yii::t('app', 'Votes Plus'),
            'votesAbstain' => Yii::t('app', 'Votes Abstain'),
            'votesMinus' => Yii::t('app', 'Votes Minus'),
        ];
    }
    
    public function getCompany()
    {
        return $this->hasOne(Company::className(), ['id' => 'companyId']);
    }
    
    public function getInitiator()
    {
        return $this->hasOne(Utr::className(), ['id' => 'initiatorId'])->one()->getObject();
    }
    
}
