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
    
    /**
     * Создать агенство по шаблону
     */
    const CREATE_AGENCY = 4;
    
    /**
     * Переименовать агенство
     */
    const RENAME_AGENCY = 5;
    
    /**
     * Сменить флаг государства
     */
    const CHANGE_FLAG_STATE = 6;
    
    /**
     * Сменить флаг региона
     */
    const CHANGE_FLAG_REGION = 7;
    
    /**
     * Сменить флаг города
     */
    const CHANGE_FLAG_CITY = 8;
    
    /**
     * Сменить гимн государства
     */
    const CHANGE_ANTHEM_STATE = 9;
    
    /**
     * Сменить гимн региона
     */
    const CHANGE_ANTHEM_REGION = 10;
    
    /**
     * Сменить гимн города
     */
    const CHANGE_ANTHEM_CITY = 11;
    
    public static function findAll()
    {
        return [
            static::RENAME_STATE => Yii::t('app/bills', 'Rename state'),
            static::RENAME_REGION => Yii::t('app/bills', 'Rename region'),
            static::RENAME_CITY => Yii::t('app/bills', 'Rename city'),
            static::CREATE_AGENCY => Yii::t('app/bills', 'Create new agency'),
            static::RENAME_AGENCY => Yii::t('app/bills', 'Rename agency'),
            static::CHANGE_FLAG_STATE => Yii::t('app/bills', 'Change state flag'),
            static::CHANGE_FLAG_REGION => Yii::t('app/bills', 'Change region flag'),
            static::CHANGE_FLAG_CITY => Yii::t('app/bills', 'Change city flag'),
            static::CHANGE_ANTHEM_STATE => Yii::t('app/bills', 'Change state anthem'),
            static::CHANGE_ANTHEM_REGION => Yii::t('app/bills', 'Change region anthem'),
            static::CHANGE_ANTHEM_CITY => Yii::t('app/bills', 'Change city anthem'),
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
            static::CREATE_AGENCY => 'CreateAgency',
            static::RENAME_AGENCY => 'RenameAgency',
            static::CHANGE_FLAG_STATE => 'ChangeFlagState',
            static::CHANGE_FLAG_REGION => 'ChangeFlagRegion',
            static::CHANGE_FLAG_CITY => 'ChangeFlagCity',
            static::CHANGE_ANTHEM_STATE => 'ChangeAnthemState',
            static::CHANGE_ANTHEM_REGION => 'ChangeAnthemRegion',
            static::CHANGE_ANTHEM_CITY => 'ChangeAnthemCity',
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
            static::CREATE_AGENCY => 'create-agency',
            static::RENAME_AGENCY => 'rename-agency',
            static::CHANGE_FLAG_STATE => 'change-flag-state',
            static::CHANGE_FLAG_REGION => 'change-flag-region',
            static::CHANGE_FLAG_CITY => 'change-flag-city',
            static::CHANGE_ANTHEM_STATE => 'change-anthem-state',
            static::CHANGE_ANTHEM_REGION => 'change-anthem-region',
            static::CHANGE_ANTHEM_CITY => 'change-anthem-city',
        ];
        
        return '/work/bills/'.$views[$type];
    }
    
    public static function getCategory(int $type) : string
    {
        switch ($type) {
            case static::CREATE_AGENCY:
            case static::RENAME_AGENCY:
                return Yii::t('app', 'Agencies');
            case static::RENAME_REGION:
            case static::CHANGE_FLAG_REGION:
            case static::CHANGE_ANTHEM_REGION:
                return Yii::t('app', 'Regions');
            case static::RENAME_CITY:
            case static::CHANGE_FLAG_CITY:
            case static::CHANGE_ANTHEM_CITY:
                return Yii::t('app', 'Cities');
            default:
                return Yii::t('app', 'Basic bill types');
        }
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
