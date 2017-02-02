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
abstract class BuildingProto extends Model
{
    
    const OFFICE = 1;
    
    public static function getList()
    {
        return [
            static::OFFICE => Yii::t('app', 'Office'),
        ];
    }
    
    public static function exist(int $id)
    {
        return isset(static::getList()[$id]);
    }

    public static function getClassNameByType(int $id)
    {
        $classes = [
            static::OFFICE => 'base\\Office',
        ];
        
        return '\\app\\models\\economics\\units\\buildings\\'.$classes[$id];
    }
    
    public static function instantiate(int $id)
    {
        $className = static::getClassNameByType($id);
        return new $className;
    }
    
    abstract public function getName();
    
    abstract public function getBuildResourcesPacks();
    
}
