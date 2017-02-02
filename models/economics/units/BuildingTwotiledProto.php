<?php

namespace app\models\economics\units;

use Yii,
    yii\base\Model;

/**
 * 
 * @property string $name
 * @property \app\models\economics\ResourcePack[] $buildResourcesPacks
 * 
 */
abstract class BuildingTwotiledProto extends Model
{
    
    const POWER_LINE = 1;
    
    public static function getList()
    {
        return [
            static::POWER_LINE => Yii::t('app', 'Power line'),
        ];
    }
    
    public static function exist(int $id)
    {
        return isset(static::getList()[$id]);
    }

    public static function getClassNameByType(int $id)
    {
        $classes = [
            static::OFFICE => 'PowerLine',
        ];
        
        return '\\app\\models\\economics\\units\\twotiled\\'.$classes[$id];
    }
    
    public static function instantiate(int $id)
    {
        $className = static::getClassNameByType($id);
        return new $className;
    }
    
    abstract public function getName();
    
    abstract public function getBuildResourcesPacks();
    
}
