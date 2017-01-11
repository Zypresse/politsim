<?php

namespace app\models\politics\constitution\articles\postsonly;

use Yii;

/**
 * 
 */
final class Bills extends Powers
{
    use \app\models\politics\constitution\articles\base\NoSubtypesArticle;
    
    /**
     * Голосовать
     */
    const VOTE = 1;
    
    /**
     * Создавать
     */
    const CREATE = 2;
    
    /**
     * Принимать
     */
    const ACCEPT = 4;
    
    /**
     * Отменять
     */
    const VETO = 8;
    
    public static function getList(): array
    {
        return [
            static::VOTE => Yii::t('app', 'Voting for bills'),
            static::CREATE => Yii::t('app', 'Creating new bills'),
            static::ACCEPT => Yii::t('app', 'Accept bills'),
            static::VETO => Yii::t('app', 'Veto against bills'),
        ];
    }

}
