<?php

namespace app\models\objects\proto;

use app\components\MyModel;

/**
 * 
 *
 * @property integer $id
 * @property string $name
 * @property string $class
 */
abstract class ObjectProto extends MyModel
{
    /*
    public static function instantiate($row)
    {
        return new $row['class'];
    }
    
    public function init()
    {
        $this->class = static::class;
        parent::init();
    }
        
    public function beforeSave($insert)
    {
        $this->class = static::class;
        return parent::beforeSave($insert);
    }*/
}