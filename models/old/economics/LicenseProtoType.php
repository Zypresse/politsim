<?php

namespace app\models\economics;

/**
 * 
 */
abstract class LicenseProtoType
{
    
    /**
     * Банковская деятельность
     */
    const BANK = 1;
    
    /**
     * Эмиссия собственной валюты
     */
    const CURRENCY_EMISSION = 2;
    
    /**
     * Постройка знаний
     */
    const BUILDING_CONSTRUCTION = 3;
    
}
