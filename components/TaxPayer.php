<?php

namespace app\components;

/**
 * 
 *
 * @author ilya
 * 
 * @property int $unnp ИНН
 */
interface TaxPayer {
    
    public function getUnnp();
    
    /*public function getStocks();
    {
        return $this->hasMany('app\models\Stock', array('unnp' => 'unnp'));
    }*/
    
    public function isGoverment($stateId);
    
    public function getBalance();
    
    public function changeBalance($delta);
    
    public function getHtmlName();
    
    public function getUnnpType();
    
    public function getTaxStateId();
    
    public function isTaxedInState($stateId);
    
}
