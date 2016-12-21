<?php

namespace app\models\politics\constitution;

/**
 * 
 */
abstract class ConstitutionArticleType
{
    
    /**
     * Пост лидера 
     */
    const LEADER_POST = 1;
    
    /**
     * Способ назначения (у поста)
     */
    const DESTIGNATION_TYPE = 2;
        
    /**
     * 
     * @param integer $type
     * @return string
     */
    public static function getClassNameByType(int $type) : string
    {
        $classes = [
            static::LEADER_POST => 'LeaderPost',
            static::DESTIGNATION_TYPE => 'postsonly\\DestignationType',
        ];
        
        return '\\app\\models\\politics\\constitution\\articles\\'.$classes[$type];
    }
    
}

