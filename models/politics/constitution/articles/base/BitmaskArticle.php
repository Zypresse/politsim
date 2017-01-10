<?php

namespace app\models\politics\constitution\articles\base;

/**
 * 
 */
abstract class BitmaskArticle extends UnsignedIntegerArticle
{
    
    /**
     * Список вариантов
     */
    abstract public static function getList() : array;
    
    public function rules()
    {
        $rules = parent::rules();
        $rules[4]['max'] = static::getMaxValue();
        return $rules;
    }
    
    protected static function getMaxValue()
    {
        $sum = 0;
        foreach (static::getList() as $val => $name) {
            $sum |= $val;
        }
        return $sum;
    }
    
    public function getName()
    {
        $value = (int) $this->value;
        $names = [];
        foreach (static::getList() as $val => $name) {
            if ($value & $val) {
                $names[] = $name;
            }
        }
        return count($names) ? implode(', ', $names) : \Yii::t('yii', '(not set)');
    }
    
    public function getSelected()
    {
        $value = (int) $this->value;
        $selected = [];
        foreach (static::getList() as $val => $name) {
            if ($value & $val) {
                $selected[$val] = $name;
            }
        }
        return $selected;
    }
    
}
