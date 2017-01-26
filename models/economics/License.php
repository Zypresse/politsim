<?php

namespace app\models\economics;

use Yii,
    app\models\base\MyActiveRecord,
    app\models\politics\State;

/**
 * Выданные лицензии
 *
 * @property integer $id
 * @property integer $protoId
 * @property integer $stateId
 * @property integer $companyId
 * @property integer $datePending
 * @property integer $dateGranted
 * @property integer $dateExpired
 * 
 * @property Company $company
 * @property State $state
 * 
 * @property boolean $isActive
 * 
 */
class License extends MyActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'licenses';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['protoId', 'stateId', 'companyId', 'datePending'], 'required'],
            [['protoId'], 'integer'],
            [['stateId', 'companyId', 'datePending', 'dateGranted', 'dateExpired'], 'integer', 'min' => 0],
            [['companyId'], 'exist', 'skipOnError' => true, 'targetClass' => Company::className(), 'targetAttribute' => ['companyId' => 'id']],
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
            'protoId' => Yii::t('app', 'Proto ID'),
            'stateId' => Yii::t('app', 'State ID'),
            'companyId' => Yii::t('app', 'Company ID'),
            'datePending' => Yii::t('app', 'Date Pending'),
            'dateGranted' => Yii::t('app', 'Date Granted'),
            'dateExpired' => Yii::t('app', 'Date Expired'),
        ];
    }
    
    public function getCompany()
    {
        return $this->hasOne(Company::className(), ['id' => 'companyId']);
    }
    
    public function getState()
    {
        return $this->hasOne(State::className(), ['id' => 'stateId']);
    }
    
    public function getIsActive()
    {
        return $this->dateGranted && (!$this->dateExpired || $this->dateExpired < time());
    }
}
