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
        $rules[4] = [['value'], 'integer', 'min' => 1, 'max' => max(array_keys(static::getList()))];
        return $rules;
    }
    
    public function getName()
    {
        return static::getNameStatic($this->value);
    }
    
    public static function getNameStatic(int $type)
    {
        foreach (static::getList() as $id => $name) {
            if ($id == $type) {
                return $name;
            }
        }
        return 'Undefined';
    }
    
}
