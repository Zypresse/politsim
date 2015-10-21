<?php

namespace app\models\objects\proto;

use app\components\MyModel;

/**
 * 
 *
 * @property integer $id
 * @property string $name
 * @property string $class_name
 */
abstract class ObjectProto extends MyModel
{
        
    public static function instantiate($row)
    {
        return new $row['class_name'];
    }
    
    public function init()
    {
        $this->class_name = static::class;
        parent::init();
    }
        
    public function beforeSave($insert)
    {
        $this->class_name = static::class;
        return parent::beforeSave($insert);
    }
}