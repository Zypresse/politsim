<?php

namespace app\models\base;

use yii\base\Object,
    yii\base\ErrorException;

/**
 * 
 */
abstract class ObjectWithFixedPrototypes extends Object
{
    
    abstract protected static function getList();
    
    /**
     * 
     * @param integer $id
     * @return \static
     */
    public static function findOne(int $id)
    {
        foreach (static::getList() as $object) {
            if ($object['id'] == $id) {
                return new static($object);
            }
        }
        
        return null;
    }
    
    /**
     * 
     * @return \static[]
     */
    public static function findAll()
    {
        $list = [];
        foreach (static::getList() as $params) {
            $list[] = new static($params);
        }
        return $list;
    }
    
    /**
     * 
     * @param integer $id
     * @return boolean
     */
    public static function exist(int $id)
    {
        foreach (static::getList() as $params) {
            if ($params['id'] == $id) {
                return true;
            }
        }
        return false;
    }
    
    /**
     * 
     * @param integer $id
     * @param string $colName
     * @return mixed
     */
    public static function getCol(int $id, string $colName)
    {
        $model = static::findOne($id);
        return $model->$colName;
    }
    
}
