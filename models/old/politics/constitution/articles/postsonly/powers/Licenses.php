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

    public function validateLicenseIds($attribute, $params)
    {
        if (is_array($this->$attribute)) {
            $val = $this->$attribute;
        } else {
            $val = explode(',', $this->$attribute);
        }
        
        foreach ($val as $id) {
            if (!LicenseProto::exist($id)) {
                $this->addError($attribute, Yii::t('app', "License proto #{$id} not exist!"));
            }
        }
        return !count($this->getErrors($attribute));
    }
    
    public function getName()
    {
        if ($this->value2) {
            $names = [];
            foreach ($this->value2 as $id) {
                $names[] = LicenseProto::findOne($id)->name;
            }
            return parent::getName().' '.Yii::t('app', 'for licenses types:').' '.implode(',', $names);
        } else {
            return parent::getName();
        }
    }
    
}
