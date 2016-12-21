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
     * Разрешение одному юзеру занимать несколько должностей в государстве
     */
    const MULTIPOST = 3;
    
    /**
     * Политика в отношении партий
     */
    const PARTIES = 4;
        
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
            static::MULTIPOST => 'statesonly\\Multipost',
            static::PARTIES => 'statesonly\\Parties',
        ];
        
        return '\\app\\models\\politics\\constitution\\articles\\'.$classes[$type];
    }
    
}

