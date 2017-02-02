<?php

namespace app\models\economics\units;

use Yii;

/**
 * 
 * @property string $name
 * @property \app\models\economics\ResourcePack[] $buildResourcesPacks
 * 
 */
abstract class BuildingTwotiledProto extends BaseUnitProto
{
    
    const POWER_LINE = 1;
    
    public static function getList()
    {
        return [
            static::POWER_LINE => Yii::t('app', 'Power line'),
        ];
    }
    
    public static function getClassNameByType(int $id)
    {
        $classes = [
            static::POWER_LINE => 'PowerLine',
        ];
        
        return '\\app\\models\\economics\\units\\twotiled\\'.$classes[$id];
    }
        
}
