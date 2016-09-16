<?php

namespace app\models;

use Yii,
    app\components\TaxPayerModel;

/**
 * This is the model class for table "agencies".
 *
 * @property integer $id
 * @property integer $stateId
 * @property string $name
 * @property string $nameShort
 * @property integer $dateCreated
 * @property integer $dateDeleted
 * @property integer $utr
 * 
 * @property State $state
 * 
 */
class Agency extends TaxPayerModel
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'agencies';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['stateId', 'name', 'nameShort'], 'required'],
            [['stateId', 'dateCreated', 'dateDeleted', 'utr'], 'integer', 'min' => 0],
            [['name'], 'string', 'max' => 255],
            [['nameShort'], 'string', 'max' => 6],
            [['utr'], 'exist', 'skipOnError' => true, 'targetClass' => Utr::className(), 'targetAttribute' => ['utr' => 'id']],
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
            'name' => Yii::t('app', 'Name'),
            'nameShort' => Yii::t('app', 'Name Short'),
            'dateCreated' => Yii::t('app', 'Date Created'),
            'dateDeleted' => Yii::t('app', 'Date Deleted'),
            'utr' => Yii::t('app', 'Utr'),
        ];
    }
    
    public function getUtrType()
    {
        return Utr::TYPE_AGENCY;
    }

    public function getTaxStateId()
    {
        return $this->stateId;
    }

    public function isTaxedInState($stateId)
    {
        return $this->stateId == $stateId;
    }

    public function isGoverment($stateId)
    {
        return $this->stateId == $stateId;
    }
    
    public function getUserControllerId()
    {
        return false;
    }

    public function isUserController($userId)
    {
        return false;
    }
    
    public function getState()
    {
        return $this->hasOne(State::className(), ['id' => 'stateId']);
    }
    
    public function beforeSave($insert)
    {
        
        if ($insert) {
            $this->dateCreated = time();
        }
        
        return parent::beforeSave($insert);
    }

}
