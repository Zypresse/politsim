<?php

namespace app\models\politics\elections;

/**
 * 
 */
abstract class ElectionStatus
{
    
    /**
     * Регистрация на выборы ещё не началась
     */
    const NOT_STARTED = 0;
    
    /**
     * идёт регистрация на выборы
     */
    const REGISTRATION = 1;
    
    /**
     * Регистрация на выборы завершена
     */    
    const REGISTRATION_ENDED = 2;
    
    /**
     * идёт голосование
     */
    const VOTING = 3;
    
    /**
     * идёт подвод итогов выборов
     */
    const CALCULATING = 4;
    
    /**
     * выборы окончены
     */
    const ENDED = 5;
    
}
