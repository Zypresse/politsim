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
abstract class UnitProto extends Model
{
    
    const CONSTRUCTION_FIRM = 1;
    
    public static function getList()
    {
        return [
            static::CONSTRUCTION_FIRM => Yii::t('app', 'Construction firm'),
        ];
    }
    
    public static function exist(int $id)
    {
        return isset(static::getList()[$id]);
    }

    public static function getClassNameByType(int $id)
    {
        $classes = [
            static::OFFICE => 'ConstructionFirm',
        ];
        
        return '\\app\\models\\economics\\units\\units\\'.$classes[$id];
    }
    
    public static function instantiate(int $id)
    {
        $className = static::getClassNameByType($id);
        return new $className;
    }
    
    abstract public function getName();
    
    abstract public function getBuildResourcesPacks();
    
}
