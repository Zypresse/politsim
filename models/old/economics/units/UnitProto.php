<?php

namespace app\models\economics\units;

use Yii;

/**
 * 
 * @property string $name
 * @property \app\models\economics\ResourcePack[] $buildResourcesPacks
 * 
 */
abstract class UnitProto extends BaseUnitProto
{
    
    const CONSTRUCTION_FIRM = 1;
    
    public static function getList()
    {
        return [
            static::CONSTRUCTION_FIRM => Yii::t('app', 'Construction firm'),
        ];
    }

    public static function getClassNameByType(int $id)
    {
        $classes = [
            static::CONSTRUCTION_FIRM => 'ConstructionFirm',
        ];
        
        return '\\app\\models\\economics\\units\\units\\'.$classes[$id];
    }
    
}
