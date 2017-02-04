<?php

namespace app\models\economics\units;

use Yii;

/**
 * 
 */
abstract class BuildingProto extends BaseUnitProto
{
    
    const OFFICE = 1;
    
    public static function getList()
    {
        return [
            static::OFFICE => Yii::t('app', 'Office'),
        ];
    }
    
    public static function getClassNameByType(int $id)
    {
        $classes = [
            static::OFFICE => 'base\\Office',
        ];
        
        return '\\app\\models\\economics\\units\\buildings\\'.$classes[$id];
    }
    
}
