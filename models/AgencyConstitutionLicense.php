<?php

namespace app\models;

use Yii,
    app\components\MyModel;

/**
 * 
 * 
 * @property integer $agencyId
 * @property integer $licenseProtoId
 * @property integer $powers
 * 
 * @property Agency $agency
 * @property LicenseProto $licenseProto
 * 
 */
class AgencyConstitutionLicense extends MyModel
{
    
    /**
     * заниматься деятельностью по лицензии
     */
    const POWER_MAKE_BUISNESS = 1;
    
    /**
     * выдавать лицензии
     */
    const POWER_GIVE = 2;
    
    /**
     * продлять лицензии
     */
    const POWER_PROLONG = 4;
    
    /**
     * отзывать лицензии
     */
    const POWER_REVOKE = 8;
    
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'constitutions-agencies-licenses';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['agencyId', 'licenseProtoId', 'powers'], 'required'],
            [['agencyId', 'licenseProtoId', 'powers'], 'integer', 'min' => 0],
            [['agencyId'], 'unique'],
            [['agencyId', 'licenseProtoId'], 'unique', 'targetAttribute' => ['stateId', 'licenseProtoId']],
            [['agencyId'], 'exist', 'skipOnError' => true, 'targetClass' => Agency::className(), 'targetAttribute' => ['agencyId' => 'id']],
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
        return ['agencyId'];
    }
    
    public function getAgency()
    {
        return $this->hasOne(Agency::className(), ['id' => 'agencyId']);
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
