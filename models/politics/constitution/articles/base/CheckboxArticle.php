<?php

namespace app\models\politics\constitution\articles\base;

use Yii,
    app\models\politics\constitution\ConstitutionArticle;

/**
 * 
 */
abstract class CheckboxArticle extends ConstitutionArticle
{
    
    public function rules()
    {
        $rules = parent::rules();
        $rules[3][0] = ['value2', 'value3'];
        $rules[] = [['value'], 'boolean'];
        return $rules;
    }
    
    public function getName()
    {
        return $this->value ? Yii::t('yii', 'Yes') : Yii::t('yii', 'No');
    }
    
}
