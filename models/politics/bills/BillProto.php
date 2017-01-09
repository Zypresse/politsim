<?php

namespace app\models\politics\bills;

/**
 * Прототип законопроекта
 */
abstract class BillProto
{
    
    /**
     * Переименовать государство
     */
    const RENAME_STATE = 1;
    
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
