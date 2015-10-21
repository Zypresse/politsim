<?php

namespace app\components;

/**
 * 
 *
 * @author ilya
 * 
 * @property int $unnp ИНН
 */
interface NalogPayer {
    
    public function getUnnp();
    
    /*public function getStocks();
    {
        return $this->hasMany('app\models\Stock', array('unnp' => 'unnp'));
    }*/
    
    public function isGoverment($stateId);
    /*{
        return false;
    }*/
    
}
