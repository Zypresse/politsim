<?php

namespace app\models\politics\constitution\articles\statesonly;

use Yii,
    app\models\politics\Party,
    app\models\politics\constitution\articles\base\DropdownArticle;

/**
 * 
 */
class Parties extends DropdownArticle
{
    
    const FORBIDDEN = 1;
    const ONLY_RULING = 2;
    const NEED_CONFIRM = 3;
    const ALLOWED = 4;
    
    public function rules()
    {
        $type = (int) $this->value;
        $rules = parent::rules();
        unset($rules[3]);
        if ($type == static::ALLOWED || $type == static::NEED_CONFIRM) {
            $rules[0][0][] = 'value3';
            $rules[] = [['value3'], 'number', 'min' => 0];
            $rules[] = [['value3'], 'default', 'value' => 0];
        } else if ($type == static::ONLY_RULING) {
            $rules[0][0][] = 'value2';
            $rules[] = [['value2'], 'exist', 'skipOnError' => false, 'targetClass' => Party::className(), 'targetAttribute' => ['value2' => 'id'], 'message' => 'Value2 must be valid Party ID'];
            $rules[] = ['value2', 'validateParty'];
        }
        return $rules;
    }
    
    public static function getList(): array
    {
        return [
            static::FORBIDDEN => Yii::t('app', 'Forbidden'),
            static::ONLY_RULING => Yii::t('app', 'Only ruling party'),
            static::NEED_CONFIRM => Yii::t('app', 'Registration party needs goverment confirmation'),
            static::ALLOWED => Yii::t('app', 'Allowed'),
        ];
    }
    
}
