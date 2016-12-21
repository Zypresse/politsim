<?php

namespace app\models\politics\constitution\articles\base;

/**
 * 
 */
abstract class DropdownArticle extends UnsignedIntegerArticle
{
    
    /**
     * Список вариантов
     */
    abstract public static function getList() : array;

    public function rules()
    {
        $rules = parent::rules();
        $rules[4] = [['value'], 'integer', 'min' => 1, 'max' => count(static::getList())];
        return $rules;
    }
    
    public function getName()
    {
        return static::getList()[(int) $this->value];
    }
    
}
