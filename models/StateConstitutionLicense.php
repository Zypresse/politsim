<?php

namespace app\models;

use Yii,
    app\components\MyModel;

/**
 * 
 * 
 * @property integer $stateId
 * @property integer $licenseProtoId
 * @property integer $policy
 * @property double $localRegistrationTax
 * @property double $foreignRegistrationTax
 * @property double $localMinCapital
 * @property double $foreignMinCapital
 * 
 * @property State $state
 * @property LicenseProto $licenseProto
 * 
 */
class StateConstitutionLicense extends MyModel
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'constitutions-licenses';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['stateId', 'licenseProtoId', 'policy'], 'required'],
            [['stateId', 'licenseProtoId', 'policy'], 'integer', 'min' => 0],
            [['localRegistrationTax', 'foreignRegistrationTax', 'localMinCapital', 'foreignMinCapital'], 'number', 'min' => 0],
            [['stateId'], 'unique'],
            [['stateId', 'licenseProtoId'], 'unique', 'targetAttribute' => ['stateId', 'licenseProtoId']],
            [['stateId'], 'exist', 'skipOnError' => true, 'targetClass' => State::className(), 'targetAttribute' => ['stateId' => 'id']],
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
    
    public static function primaryKey()
    {
        return 'stateId';
    }
    
    public function getState()
    {
        return $this->hasOne(State::className(), ['id' => 'stateId']);
    }
    
    private $_licenseProto = null;
    public function getLicenseProto()
    {
        if (is_null($this->_licenseProto)) {
            $this->_licenseProto = LicenseProto::findOne($this->licenseProtoId);
        }
        return $this->_licenseProto;
    }
}
