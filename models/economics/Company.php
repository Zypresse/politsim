<?php

namespace app\models\economics;

use Yii,
    app\models\economics\TaxPayerModel,
    app\models\User,
    app\models\politics\State;

/**
 * Акционерные общества
 *
 * @property integer $id
 * @property integer $stateId
 * @property integer $mainOfficeId
 * @property integer $directorId
 * @property string $name
 * @property string $nameShort
 * @property string $flag
 * @property double $efficiencyManagement
 * @property double $capitalization
 * @property double $sharesPrice
 * @property integer $sharesIssued
 * @property boolean $isGoverment
 * @property integer $dateCreated
 * @property integer $dateDeleted
 * @property integer $utr
 * 
 * @property State $state
 * @property Building $mainOffice
 * @property User $director
 * 
 */
class Company extends TaxPayerModel
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'companies';
    }

    /**
     * @inheritdoc
     */
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
    public function rules()
    {
        return [
            [['name', 'nameShort', 'flag', 'sharesIssued', 'dateCreated'], 'required'],
            [['stateId', 'mainOfficeId', 'directorId', 'sharesIssued', 'dateCreated', 'dateDeleted', 'utr'], 'integer', 'min' => 0],
            [['efficiencyManagement', 'capitalization', 'sharesPrice'], 'number', 'min' => 0],
            [['name', 'flag'], 'string', 'max' => 255],
            [['nameShort'], 'string', 'max' => 6],
            [['isGoverment'], 'boolean'],
            [['utr'], 'exist', 'skipOnError' => true, 'targetClass' => Utr::className(), 'targetAttribute' => ['utr' => 'id']],
            [['directorId'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['directorId' => 'id']],
//            [['mainOfficeId'], 'exist', 'skipOnError' => true, 'targetClass' => Building::className(), 'targetAttribute' => ['mainOfficeId' => 'id']],
            [['stateId'], 'exist', 'skipOnError' => true, 'targetClass' => State::className(), 'targetAttribute' => ['stateId' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'stateId' => Yii::t('app', 'State ID'),
            'mainOfficeId' => Yii::t('app', 'Main Office ID'),
            'directorId' => Yii::t('app', 'Director ID'),
            'name' => Yii::t('app', 'Name'),
            'nameShort' => Yii::t('app', 'Name Short'),
            'flag' => Yii::t('app', 'Flag'),
            'efficiencyManagement' => Yii::t('app', 'Efficiency Management'),
            'capitalization' => Yii::t('app', 'Capitalization'),
            'sharesPrice' => Yii::t('app', 'Shares Price'),
            'sharesIssued' => Yii::t('app', 'Shares Issued'),
            'dateCreated' => Yii::t('app', 'Date Created'),
            'dateDeleted' => Yii::t('app', 'Date Deleted'),
            'utr' => Yii::t('app', 'Utr'),
        ];
    }
    
    public function getDirector()
    {
        return $this->hasOne(User::className(), ['id' => 'directorId']);
    }
    
    public function getMainOffice()
    {
        return $this->hasOne(Building::className(), ['id' => 'mainOfficeId']);
    }
    
    public function getState()
    {
        return $this->hasOne(State::className(), ['id' => 'stateId']);
    }

    public function getTaxStateId(): int
    {
        return $this->stateId;
    }

    public function getUserControllerId(): int
    {
        return $this->directorId;
    }

    public function getUtrType(): int
    {
        return UtrType::COMPANY;
    }

    public function isGoverment($stateId): bool
    {
        return $this->isGoverment && $this->stateId == $stateId;
    }

    public function isTaxedInState($stateId): bool
    {
        return $this->stateId == $stateId;
    }

    public function isUserController($userId): bool
    {
        return $this->directorId == $userId;
    }
    
    public function delete()
    {
        $this->dateDeleted = time();
        return $this->save();
    }

}