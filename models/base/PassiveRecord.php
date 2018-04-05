<?php

namespace app\models\base;

use yii\base\Model;

/**
 * 
 */
abstract class PassiveRecord extends Model
{
    
    /**
     *
     * @var integer
     */
    public $id;
    
    /**
     * 
     * [
     *      self::TYPE_ONE => [
     *          'className' => ClassOne::class,
     *          'someData' => 'someValue',
     *      ],
     *      ...
     * ]
     * @return array
     */
    abstract protected static function getList();
    
    /**
     * 
     * @param integer $id
     * @return \static
     */
    public static function findOne(int $id)
    {
        
        if (isset(static::getList()[$id])) {
            $data = static::getList()[$id];
            $className = $data['className'];
            unset($data['className']);
            $data['id'] = $id;
            return new $className($data);
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
        foreach (static::getList() as $id => $data) {
            $className = $data['className'];
            unset($data['className']);
            $data['id'] = $id;
            $list[] = new $className($data);
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
        return isset(static::getList()[$id]);
    }
    
}
