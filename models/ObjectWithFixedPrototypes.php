<?php

namespace app\models;

use Yii,
    yii\base\Object,
    yii\base\ErrorException;

/**
 * Description of ObjectWithFixedPrototypes
 *
 * @author ilya
 */
abstract class ObjectWithFixedPrototypes extends Object
{
    
    protected static function getList()
    {
        throw new ErrorException("Method ".static::className()."::getList() should be overrided!");
    }
    
    public static function findOne($id)
    {
        return new static(static::getList()[intval($id)]);
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
