<?php

namespace app\models\economics;

use Yii,
    app\models\base\MyActiveRecord,
    yii\behaviors\TimestampBehavior;

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
 * @property CompanyDecisionProto $proto
 * @property Company $company
 * @property TaxPayer $initiator
 * @property CompanyDecisionVote[] $votes
 * 
 * @property boolean $isFinished
 * 
 */
class CompanyDecision extends MyActiveRecord
{
    
    public $dataArray = null;
    
    public function behaviors()
    {
        return [
            [
                'class' => TimestampBehavior::className(),
                'createdAtAttribute' => 'dateCreated',
                'updatedAtAttribute' => false,
            ],
        ];
    }
    
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
            [['companyId', 'protoId'], 'required'],
            [['companyId', 'protoId', 'initiatorId', 'dateCreated', 'dateVotingFinished', 'dateFinished', 'votesPlus', 'votesAbstain', 'votesMinus'], 'integer', 'min' => 0],
            [['isApproved'], 'boolean'],
            [['initiatorId'], 'exist', 'skipOnError' => true, 'targetClass' => Utr::className(), 'targetAttribute' => ['initiatorId' => 'id']],
            [['companyId'], 'exist', 'skipOnError' => true, 'targetClass' => Company::className(), 'targetAttribute' => ['companyId' => 'id']],
            [['data'], 'string'],
            [['dataArray'], 'validateData'],
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
    
    public function getProto()
    {
        return CompanyDecisionProto::instantiate($this->protoId);
    }
    
    public function getCompany()
    {
        return $this->hasOne(Company::className(), ['id' => 'companyId']);
    }
    
    public function getInitiator()
    {
        return $this->hasOne(Utr::className(), ['id' => 'initiatorId'])->one()->getObject();
    }
    
    public function getVotes()
    {
        return $this->hasMany(CompanyDecisionVote::className(), ['decisionId' => 'id']);
    }
    
    /**
     * @param string $attribute the attribute currently being validated
     * @param mixed $params the value of the "params" given in the rule
     */
    public function validateData($attribute, $params)
    {
        return $this->proto->validate($this);
    }
    
    public function beforeSave($insert)
    {
        $this->data = json_encode($this->dataArray);
        if ($insert) {
            $this->dateVotingFinished = time() + 24 * 60 * 60;
        }
        return parent::beforeSave($insert);
    }
    
    /**
     * 
     * @return boolean
     */
    public function accept() : bool
    {
        $this->isApproved = true;
        $this->dateFinished = time();
        return $this->save() && $this->proto->accept($this);
    }
    
    /**
     * 
     * @return boolean
     */
    public function decline(): bool
    {
        $this->isApproved = false;
        $this->dateFinished = time();
        return $this->save();
    }
        
    public static function instantiate($row)
    {
        $model = parent::instantiate($row);
        $model->dataArray = json_decode($row['data'], true);
        return $model;
    }
    
    /**
     * Отображает название и суть законопроекта
     */
    public function render() : string
    {
        return $this->proto->render($this);
    }
    
    /**
     * Отображает суть законопроекта
     */
    public function renderFull() : string
    {
        return $this->proto->renderFull($this);
    }
    
    /**
     * 
     * @param integer $variant
     * @param boolean $save
     */
    public function addVote(int $variant, $save = true)
    {
        switch ($variant) {
            case CompanyDecisionVote::VARIANT_PLUS:
                $this->votesPlus++;
                break;
            case CompanyDecisionVote::VARIANT_MINUS:
                $this->votesMinus++;
                break;
            case CompanyDecisionVote::VARIANT_ABSTAIN:
                $this->votesAbstain++;
                break;
        }
        if ($save) {
            $this->save();
        }
    }
    
    public function getIsFinished()
    {
        return !!$this->dateFinished;
    }
}
