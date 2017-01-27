<?php

namespace app\models\politics\constitution\articles\postsonly\powers;

use Yii,
    app\models\economics\LicenseProto,
    app\models\politics\constitution\articles\postsonly\Powers;

/**
 * 
 */
final class Licenses extends Powers
{
    
    use \app\models\politics\constitution\articles\base\NoSubtypesArticle;
    
    /**
     * Подтверждать регистрацию
     */
    const ACCEPT = 1;
        
    /**
     * Отзывать регистрацию
     */
    const REVOKE = 2;
    
    public static function getList(): array
    {
        return [
            static::ACCEPT => Yii::t('app', 'Accept licenses request'),
            static::REVOKE => Yii::t('app', 'Revoke licenses'),
        ];
    }
    
    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'value2' => Yii::t('app', 'Licenses management'),
            'value' => Yii::t('app', 'Licenses powers'),
        ];
    }

    public function rules()
    {
        $rules = parent::rules();
        $rules[3][0] = ['value3'];
        $rules[] = [['value2'], 'validateLicenseIds'];
        return $rules;
    }
    
    public function afterFind()
    {
        $this->value2 = explode(',', $this->value2);
        return parent::afterFind();
    }


    public function beforeSave($insert)
    {
        if (is_array($this->value2)) {
            $this->value2 = implode(',', $this->value2);
        }
        return parent::beforeSave($insert);
    }

    public function validateLicenseIds($attribute, $params)
    {
        if (is_string($this->$attribute)) {
            $this->$attribute = explode(',', $this->$attribute);
        }
        
        foreach ($this->$attribute as $id) {
            if (!LicenseProto::exist($id)) {
                $this->addError($attribute, Yii::t('app', "License proto #{$id} not exist!"));
            }
        }
        return !count($this->getErrors($attribute));
    }
    
}
