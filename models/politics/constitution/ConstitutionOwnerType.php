<?php

namespace app\models\politics\constitution;

/**
 * 
 */
abstract class ConstitutionOwnerType
{
    
    /**
     * Государство
     */
    const STATE = 1;
    
    /**
     * Регион
     */
    const REGION = 2;
    
    /**
     * Город
     */
    const CITY = 3;
    
    /**
     * Гос. организация
     */
    const AGENCY = 4;
    
    /**
     * Пост в гос. организации
     */
    const POST = 5;
    
    /**
     * 
     * @param integer $type
     * @return string
     */
    public static function getClassNameByType(int $type) : string
    {
        $classes = [
            static::STATE => 'State',
            static::REGION => 'Region',
            static::CITY => 'City',
            static::AGENCY => 'Agency',
            static::POST => 'AgencyPost',
        ];
        
        return '\\app\\models\\politics\\'.$classes[$type];
    }
    
}
