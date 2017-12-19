<?php

namespace app\models\politics\elections;

use app\models\User,
    app\models\politics\PartyList;

/**
 * 
 */
abstract class ElectionRequestType
{
    
    /**
     * Юзер сам себя
     */
    const USER_SELF = 1;
    
    /**
     * Партия выставляет список
     */
    const PARTY_LIST = 2;
    
    /** 
     * Вариант "против всех"
     */
    const NONE_OF_THE_ABOVE = 3;
    
    /**
     * 
     * @param integer $type
     * @return string
     */
    public static function getClassByType(int $type)
    {
        switch ($type) {
            case static::USER_SELF:
                return User::className();
            case static::PARTY_LIST:
                return PartyList::className();
            case static::NONE_OF_THE_ABOVE:
                return null;
        }
    }
    
}
