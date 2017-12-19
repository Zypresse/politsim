<?php

namespace app\models\politics\constitution\articles\base;

use app\models\politics\constitution\ConstitutionArticle;

/**
 * 
 */
abstract class PairUnsignedIntegerArticle extends ConstitutionArticle
{
    
    public function rules()
    {
        $rules = parent::rules();
        $rules[3][0] = ['value3'];
        $rules[] = [['value', 'value2'], 'integer', 'min' => 0];
        return $rules;
    }
    
}
