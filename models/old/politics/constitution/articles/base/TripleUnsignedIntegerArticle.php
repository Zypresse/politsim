<?php

namespace app\models\politics\constitution\articles\base;

use app\models\politics\constitution\ConstitutionArticle;

/**
 * 
 */
abstract class TripleUnsignedIntegerArticle extends ConstitutionArticle
{
    
    public function rules()
    {
        $rules = parent::rules();
        unset($rules[3]);
        $rules[] = [['value', 'value2', 'value3'], 'integer', 'min' => 0];
        return $rules;
    }
    
}
