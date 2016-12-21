<?php

namespace app\models\politics\constitution\articles\base;

use app\models\politics\constitution\ConstitutionArticle;

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
    
}
