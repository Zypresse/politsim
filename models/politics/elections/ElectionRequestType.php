<?php

namespace app\models\politics\elections;

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
    
}
