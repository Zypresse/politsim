<?php

namespace app\models\politics\bills;

use Yii;

/**
 * Прототип законопроекта
 */
class BillProto
{
    
    /**
     * Переименовать государство
     */
    const RENAME_STATE = 1;
    
    public static function findAll()
    {
        return [
            static::RENAME_STATE => Yii::t('app', 'Rename state'),
        ];
    }
    
    /**
     * 
     * @param integer $type
     * @return string
     */
    public static function getClassNameByType(int $type) : string
    {
        $classes = [
            static::RENAME_STATE => 'RenameState',
        ];
        
        return '\\app\\models\\politics\\bills\\prototypes\\'.$classes[$type];
    }
    
    /**
     * 
     * @param int $id
     * @return \static
     */
    public static function instantiate(int $id)
    {
        $className = static::getClassNameByType($id);
        return new $className();
    }
    
}
