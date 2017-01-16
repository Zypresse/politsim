<?php

namespace app\models\politics\constitution\articles\postsonly\powers;

use Yii;

/**
 * 
 */
final class Parties extends Powers
{
    use \app\models\politics\constitution\articles\base\NoSubtypesArticle;
        
    /**
     * Подтверждать регистрацию
     */
    const ACCEPT = 1;
        
    /**
     * Отзывать регистрацию
     */
    const REVOKE = 2;
    
    public static function getList(): array
    {
        return [
            static::ACCEPT => Yii::t('app', 'Accept parties registration'),
            static::REVOKE => Yii::t('app', 'Revoke parties'),
        ];
    }

}
