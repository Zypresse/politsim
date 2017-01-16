<?php

namespace app\models\politics\bills;

use Yii;

/**
 * Прототип законопроекта
 */
abstract class BillProto implements BillProtoInterface
{
    
    /**
     * Переименовать государство
     */
    const RENAME_STATE = 1;
    
    /**
     * Переименовать регион
     */
    const RENAME_REGION = 2;
    
    /**
     * Переименовать город
     */
    const RENAME_CITY = 3;
    
    public static function findAll()
    {
        return [
            static::RENAME_STATE => Yii::t('app/bills', 'Rename state'),
            static::RENAME_REGION => Yii::t('app/bills', 'Rename region'),
            static::RENAME_CITY => Yii::t('app/bills', 'Rename city'),
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
            static::RENAME_REGION => 'RenameRegion',
            static::RENAME_CITY => 'RenameCity',
        ];
        
        return '\\app\\models\\politics\\bills\\prototypes\\'.$classes[$type];
    }
    
    /**
     * 
     * @param integer $type
     * @return string
     */
    public static function getViewByType(int $type) : string
    {
        $views = [
            static::RENAME_STATE => 'rename-state',
            static::RENAME_REGION => 'rename-region',
            static::RENAME_CITY => 'rename-city',
        ];
        
        return '/work/bills/'.$views[$type];
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
