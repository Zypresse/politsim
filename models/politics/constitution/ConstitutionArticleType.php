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
     * Срок полномочий
     */
    const TERMS_OF_OFFICE = 5;
    
    /**
     * Сроки выборов
     */
    const TERMS_OF_ELECTION = 6;
    
    /**
     * Права
     */
    const POWERS = 7;
    
    /**
     * Настройки законопроектов государства
     */
    const BILLS = 8;
    
    /**
     * Можно ли одному юзеру состоять в нескольких партиях
     */
    const MULTIMEMBERSHIP = 9;
    
    /**
     * Официальная валюта
     */
    const CURRENCY = 10;
    
    /**
     * Разрешения деятельности частных компаний
     */
    const BUSINESS = 11;
        
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
            static::TERMS_OF_OFFICE => 'postsonly\\TermsOfOffice',
            static::TERMS_OF_ELECTION => 'postsonly\\TermsOfElection',
            static::MULTIPOST => 'statesonly\\Multipost',
            static::PARTIES => 'statesonly\\Parties',
            static::POWERS => 'postsonly\\Powers',
            static::BILLS => 'statesonly\\Bills',
            static::MULTIMEMBERSHIP => 'statesonly\\Multimembership',
            static::CURRENCY => 'statesonly\\Currency',
            static::BUSINESS => 'statesonly\\Business',
        ];
        
        return '\\app\\models\\politics\\constitution\\articles\\'.$classes[$type];
    }
    
}

