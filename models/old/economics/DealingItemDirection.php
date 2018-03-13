<?php


namespace app\models\economics;

/**
 * 
 */
abstract class DealingItemDirection
{
    
    /**
     * от иницаиатора к ресиверу
     */
    const DIRECTION_INITIATOR_TO_RECIVIER = false;
    
    /**
     * от ресивера к инициатору
     */
    const DIRECTION_RECIVIER_TO_INITIATOR = true;
    
}
