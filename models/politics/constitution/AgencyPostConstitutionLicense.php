<?php

namespace app\models\politics\constitution;

use app\models\politics\AgencyPost,
    app\models\base\MyActiveRecord;

/**
 * 
 * 
 * @property integer $postId
 * @property integer $licenseProtoId
 * @property integer $powers
 * 
 * @property AgencyPost $post
 * @property LicenseProto $licenseProto
 * 
 */
class AgencyPostConstitutionLicense extends MyActiveRecord
{
    
    
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'constitutions-agencies-posts-licenses';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['postId', 'licenseProtoId', 'powers'], 'required'],
            [['postId', 'licenseProtoId', 'powers'], 'integer', 'min' => 0],
            [['postId'], 'unique'],
            [['postId', 'licenseProtoId'], 'unique', 'targetAttribute' => ['postId', 'licenseProtoId']],
            [['postId'], 'exist', 'skipOnError' => true, 'targetClass' => AgencyPost::className(), 'targetAttribute' => ['postId' => 'id']],
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
        return ['postId'];
    }
    
    public function getPost()
    {
        return $this->hasOne(AgencyPost::className(), ['id' => 'postId']);
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
