<?php

namespace app\models\politics\constitution\articles\base;

use app\models\politics\constitution\ConstitutionArticle;

/**
 * 
 */
abstract class UnsignedIntegerArticle extends ConstitutionArticle
{
    
    public function rules()
    {
        $rules = parent::rules();
        $rules[3][0] = ['value2', 'value3'];
        $rules[] = [['value'], 'integer', 'min' => 0];
        return $rules;
    }
    
}
