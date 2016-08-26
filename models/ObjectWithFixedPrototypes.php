<?php

namespace app\models;

use Yii,
    yii\base\Object;

/**
 * Description of ObjectWithFixedPrototypes
 *
 * @author ilya
 */
abstract class ObjectWithFixedPrototypes extends Object
{
    
    abstract protected static function getList();
    
    public static function findOne($id)
    {
        return new static(static::getList()[$id]);
    }
    
    public static function findAll()
    {
        $list = [];
        foreach (static::getList() as $params) {
            $list[] = new static($params);
        }
        return $list;
    }
}
