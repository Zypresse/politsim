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
        throw new ErrorException("Method ".static::className()."::getList() not overrided in class ".static::className()."!");
    }
    
    public static function findOne($id)
    {
        foreach (static::getList() as $object) {
            if ($object['id'] == $id) {
                return new static($object);
            }
        }
        
        return null;
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
