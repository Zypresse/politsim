<?php

namespace app\models\politics\elections;

/**
 * Кто выбирает
 */
abstract class ElectionWhoType
{
    /**
     * Население государства
     */
    const STATE = 1;
    
    /**
     * Население электорального округа
     */
    const ELECTORAL_DISTRICT = 2;
    
}
